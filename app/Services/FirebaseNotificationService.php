<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use App\Models\UserDevice;

class FirebaseNotificationService
{
    protected array $credentials;
    protected string $projectId;

    public function __construct()
    {
        $this->credentials = json_decode(env('FIREBASE_CREDENTIALS_JSON'), true);
        $this->projectId   = $this->credentials['project_id'];
    }

    protected function getAccessToken(): string
    {
        $now = time();

        $payload = [
            'iss'   => $this->credentials['client_email'],
            'sub'   => $this->credentials['client_email'],
            'aud'   => $this->credentials['token_uri'],
            'iat'   => $now,
            'exp'   => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ];

        $jwt = JWT::encode(
            $payload,
            $this->credentials['private_key'],
            'RS256'
        );

        $response = Http::asForm()->post(
            $this->credentials['token_uri'],
            [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]
        );

        return $response->json('access_token');
    }

    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $device = UserDevice::where('user_id', $userId)->first();

        if (!$device || !$device->device_token) {
            return;
        }

        $this->send($device->device_token, $title, $body, $data);
    }

    protected function send(string $token, string $title, string $body, array $data = []): void
    {
        $accessToken = $this->getAccessToken();

        Http::withToken($accessToken)
            ->post(
                "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
                [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'data' => array_map('strval', $data),
                    ],
                ]
            );
    }
}
