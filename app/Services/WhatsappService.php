<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function sendOtp($phone, $otp)
    {
        $payload = [
            "integrated_number" => "91" . $phone,
            "content_type" => "template",
            "payload" => [
                "messaging_product" => "whatsapp",
                "type" => "template",
                "template" => [
                    "name" => env('MSG91_WA_TEMPLATE'),
                    "language" => [
                        "code" => "en_US",
                        "policy" => "deterministic"
                    ],
                    "namespace" => env('MSG91_WA_NAMESPACE'),
                    "to_and_components" => [
                        [
                            "to" => ["91" . $phone],
                            "components" => [
                                "body_1" => [
                                    "type" => "text",
                                    "value" => $otp
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'authkey' => env('MSG91_AUTH_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/', $payload);

        Log::info("MSG91 WhatsApp OTP Response", [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return $response;
    }
}
