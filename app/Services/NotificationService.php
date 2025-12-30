<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\FirebaseException;

class NotificationService
{
    protected $messaging;

    public function __construct(string $credentialKey = 'pratihari', ?string $credentialsPath = null)
    {
        $path = $credentialsPath ?: config("services.firebase.{$credentialKey}.credentials");
        $path = $this->resolveFirebaseCredentialsPath($path);

        if (!$path || !is_file($path)) {
            throw new \InvalidArgumentException("Firebase credentials file not found at: {$path}");
        }
        if (!is_readable($path)) {
            throw new \InvalidArgumentException("Firebase credentials file exists but is not readable: {$path}");
        }

        $factory = (new Factory)->withServiceAccount($path);
        $this->messaging = $factory->createMessaging();
    }

    private function resolveFirebaseCredentialsPath(?string $path): ?string
    {
        if (!$path) return null;
        $path = trim($path);

        if (str_starts_with($path, '/')) {
            return $path;
        }
        if (str_starts_with($path, 'storage/')) {
            return base_path($path);
        }
        if (str_starts_with($path, 'app/')) {
            return storage_path($path);
        }
        return storage_path('app/' . ltrim($path, '/'));
    }

    /**
     * Reliable, token-wise sending (best for device-wise logs).
     * Works on all Kreait versions and avoids MulticastSendReport::responses() entirely.
     */
    public function sendBulkNotificationsDetailed(
        array $tokens,
        string $title,
        string $body,
        array $data = [],
        ?string $imageUrl = null
    ): array {
        $tokens = array_values(array_unique(array_filter($tokens)));

        $summary = [
            'success' => 0,
            'failure' => 0,
            'invalid_tokens' => [],
            'results' => [], // each: token,status,error_code,error_message
        ];

        if (empty($tokens)) {
            return $summary;
        }

        $notification = FcmNotification::create($title, $body);

        // Some Kreait versions support image in Notification via withImageUrl()
        if ($imageUrl && method_exists($notification, 'withImageUrl')) {
            $notification = $notification->withImageUrl($imageUrl);
        }

        $baseMessage = CloudMessage::new()
            ->withNotification($notification)
            ->withData($this->stringifyData($data));

        // Chunk to avoid very long runtimes; 200 is a safe operational chunk
        foreach (array_chunk($tokens, 200) as $chunk) {
            foreach ($chunk as $token) {
                try {
                    $msg = $baseMessage->withChangedTarget('token', $token);
                    $this->messaging->send($msg);

                    $summary['success']++;
                    $summary['results'][] = [
                        'token' => $token,
                        'status' => 'success',
                        'error_code' => null,
                        'error_message' => null,
                    ];
                } catch (MessagingException|FirebaseException|\Throwable $e) {
                    $summary['failure']++;

                    $message = (string) $e->getMessage();
                    $summary['results'][] = [
                        'token' => $token,
                        'status' => 'failure',
                        'error_code' => get_class($e),
                        'error_message' => $message,
                    ];

                    // Common “invalid/expired/unregistered” token signals
                    if ($this->looksLikeInvalidToken($message)) {
                        $summary['invalid_tokens'][] = $token;
                    }
                }
            }
        }

        $summary['invalid_tokens'] = array_values(array_unique($summary['invalid_tokens']));
        return $summary;
    }

    private function stringifyData(array $data): array
    {
        $out = [];
        foreach ($data as $k => $v) {
            if (is_bool($v)) {
                $out[$k] = $v ? '1' : '0';
            } elseif (is_scalar($v) || $v === null) {
                $out[$k] = (string)($v ?? '');
            } else {
                $out[$k] = json_encode($v, JSON_UNESCAPED_UNICODE);
            }
        }
        return $out;
    }

    private function looksLikeInvalidToken(string $message): bool
    {
        $m = strtolower($message);

        return str_contains($m, 'registration token') ||
               str_contains($m, 'not a valid') ||
               str_contains($m, 'invalid argument') ||
               str_contains($m, 'requested entity was not found') ||
               str_contains($m, 'unregistered');
    }
}
