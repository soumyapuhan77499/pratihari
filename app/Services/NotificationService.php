<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class NotificationService
{
    private Messaging $messaging;
    private bool $dryRun;

    /**
     * @param string $appKey The firebase app key under config('services.firebase.apps.*')
     * @param string|null $credentialsPath Optional override path
     */
    public function __construct(string $appKey = null, ?string $credentialsPath = null)
    {
        $appKey = $appKey ?: config('services.firebase.default', 'pratihari');

        $path = $credentialsPath ?: config("services.firebase.apps.{$appKey}.credentials");

        if (!$path) {
            throw new \InvalidArgumentException("Firebase credentials path is empty for app key: {$appKey}");
        }

        // Resolve relative paths from Laravel base path
        if (!Str::startsWith($path, ['/','\\']) && !preg_match('/^[A-Za-z]:\\\\/', $path)) {
            $path = base_path($path);
        }

        if (!file_exists($path)) {
            throw new \InvalidArgumentException("Firebase credentials file not found at: {$path}");
        }

        $factory = (new Factory())->withServiceAccount($path);
        $this->messaging = $factory->createMessaging();

        $this->dryRun = filter_var(env('FCM_DRY_RUN', false), FILTER_VALIDATE_BOOL);
    }

    /**
     * Send a single device token notification.
     */
    public function sendNotification(string $deviceToken, string $title, string $body, array $data = []): string
    {
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(FcmNotification::create($title, $body))
            ->withData($this->stringifyValues($data));

        // dryRun=false sends; dryRun=true validates only
        return $this->messaging->send($message, $this->dryRun);
    }

    /**
     * Send to multiple tokens safely (chunks of 500), log/report failures.
     * Returns summary array for your logs/UI.
     */
    public function sendBulkNotifications(array $deviceTokens, string $title, string $body, array $data = []): array
    {
        $deviceTokens = array_values(array_unique(array_filter($deviceTokens, fn($t) => is_string($t) && trim($t) !== '')));

        if (empty($deviceTokens)) {
            return [
                'success' => 0,
                'failure' => 0,
                'invalid_tokens' => [],
                'errors' => ['No valid device tokens provided.'],
            ];
        }

        $messages = array_map(function (string $token) use ($title, $body, $data) {
            return CloudMessage::withTarget('token', $token)
                ->withNotification(FcmNotification::create($title, $body))
                ->withData($this->stringifyValues($data));
        }, $deviceTokens);

        $success = 0;
        $failure = 0;
        $invalidTokens = [];
        $errors = [];

        // Firebase multicast limit is typically 500 per request; keep chunking safe.
        foreach (array_chunk($messages, 500) as $chunkIndex => $chunk) {
            try {
                $report = $this->messaging->sendAll($chunk, $this->dryRun);

                $success += $report->successes()->count();
                $failure += $report->failures()->count();

                foreach ($report->failures()->getItems() as $failureItem) {
                    $target = $failureItem->target(); // token target object
                    $token = method_exists($target, 'value') ? $target->value() : null;

                    $e = $failureItem->error();
                    $msg = $e ? $e->getMessage() : 'Unknown error';

                    // Many invalid-token scenarios surface as "registration token is not valid"
                    if ($token) {
                        $invalidTokens[] = $token;
                    }

                    $errors[] = $msg;
                }
            } catch (\Throwable $e) {
                Log::error('FCM sendAll failed for chunk', [
                    'chunk_index' => $chunkIndex,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $e->getMessage();
            }
        }

        $invalidTokens = array_values(array_unique(array_filter($invalidTokens)));

        return [
            'success' => $success,
            'failure' => $failure,
            'invalid_tokens' => $invalidTokens,
            'errors' => array_values(array_unique($errors)),
        ];
    }

    private function stringifyValues(array $data): array
    {
        $out = [];
        foreach ($data as $k => $v) {
            if (is_null($v)) {
                $out[$k] = '';
            } elseif (is_scalar($v)) {
                $out[$k] = (string) $v;
            } else {
                $out[$k] = json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        return $out;
    }
}
