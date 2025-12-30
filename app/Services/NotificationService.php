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

        $factory = (new Factory)->withServiceAccount($path);
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Convert env/config path into an absolute filesystem path.
     *
     * Supports:
     * - absolute: /var/www/.../storage/app/firebase/pratihari.json
     * - relative: storage/app/firebase/pratihari.json
     * - relative: app/firebase/pratihari.json (will map to storage/app/...)
     */
    private function resolveFirebaseCredentialsPath(?string $path): ?string
    {
        if (!$path) return null;

        $path = trim($path);

        // Already absolute path (Linux)
        if (str_starts_with($path, '/')) {
            return $path;
        }

        // If user gives "storage/app/...." or "storage/...."
        if (str_starts_with($path, 'storage/')) {
            return base_path($path);
        }

        // If user gives "app/firebase/...." treat as storage/app/firebase/....
        if (str_starts_with($path, 'app/')) {
            return storage_path($path);
        }

        // If user gives just "firebase/pratihari.json" treat as storage/app/firebase/...
        return storage_path('app/' . ltrim($path, '/'));
    }

    // Your existing methods below (unchanged)
    public function sendBulkNotifications(array $tokens, string $title, string $body, array $data = []): array
    {
        return $this->sendBulkNotificationsDetailed($tokens, $title, $body, $data, null);
    }

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
            'results' => [],
        ];

        if (empty($tokens)) {
            return $summary;
        }

        $notif = FcmNotification::create($title, $body);

        if ($imageUrl && method_exists($notif, 'withImageUrl')) {
            $notif = $notif->withImageUrl($imageUrl);
        }

        $message = CloudMessage::new()
            ->withNotification($notif)
            ->withData($this->stringifyData($data));

        foreach (array_chunk($tokens, 500) as $chunk) {
            try {
                $report = $this->messaging->sendMulticast($message, $chunk);

                foreach ($report->responses() as $i => $sendReport) {
                    $token = $chunk[$i] ?? null;
                    if (!$token) continue;

                    if ($sendReport->isSuccess()) {
                        $summary['success']++;
                        $summary['results'][] = [
                            'token' => $token,
                            'status' => 'success',
                            'error_code' => null,
                            'error_message' => null,
                        ];
                    } else {
                        $summary['failure']++;

                        $e = $sendReport->error();
                        $errorMessage = $e ? (string) $e->getMessage() : 'Unknown failure';
                        $errorCode = $e ? (string) get_class($e) : null;

                        if ($this->looksLikeInvalidToken($errorMessage)) {
                            $summary['invalid_tokens'][] = $token;
                        }

                        $summary['results'][] = [
                            'token' => $token,
                            'status' => 'failure',
                            'error_code' => $errorCode,
                            'error_message' => $errorMessage,
                        ];
                    }
                }
            } catch (MessagingException|FirebaseException|\Throwable $e) {
                foreach ($chunk as $token) {
                    $summary['failure']++;
                    $summary['results'][] = [
                        'token' => $token,
                        'status' => 'failure',
                        'error_code' => get_class($e),
                        'error_message' => $e->getMessage(),
                    ];
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
                $out[$k] = (string) ($v ?? '');
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
