<?php

namespace App\Services;

use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class NotificationService
{
    protected $messaging;

    public function __construct(string $credentialKey = 'pratihari', ?string $credentialsPath = null)
    {
        $path = $credentialsPath ?: config("services.firebase.{$credentialKey}.credentials");

        if (!$path) {
            throw new \InvalidArgumentException("Firebase credentials path is empty for key: {$credentialKey}");
        }

        // If relative path given (like storage/app/...), resolve from Laravel base path
        if (!Str::startsWith($path, ['/','\\']) && !preg_match('/^[A-Za-z]:\\\\/', $path)) {
            $path = base_path($path);
        }

        if (!file_exists($path)) {
            throw new \InvalidArgumentException("Firebase credentials file not found at: {$path}");
        }

        $factory = (new Factory)->withServiceAccount($path);
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body, array $data = [])
    {
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(FcmNotification::create($title, $body))
            ->withData($this->stringifyValues($data));

        return $this->messaging->send($message);
    }

    public function sendBulkNotifications(array $deviceTokens, $title, $body, array $data = [])
    {
        $messages = [];

        foreach ($deviceTokens as $deviceToken) {
            if (!is_string($deviceToken) || empty($deviceToken)) {
                \Log::warning('Invalid device token encountered.', ['deviceToken' => $deviceToken]);
                continue;
            }

            $messages[] = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification(FcmNotification::create($title, $body))
                ->withData($this->stringifyValues($data));
        }

        if (empty($messages)) {
            \Log::info('No valid messages to send.');
            return null;
        }

        return $this->messaging->sendAll($messages);
    }

    private function stringifyValues(array $data): array
    {
        return array_map(fn($v) => is_scalar($v) ? (string)$v : json_encode($v), $data);
    }
}
