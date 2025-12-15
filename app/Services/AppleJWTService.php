<?php

namespace App\Services;

use Firebase\JWT\JWT;

class AppleJWTService
{
    public static function generate()
    {
        $privateKey = file_get_contents(storage_path('apple/AuthKey.p8'));

        return JWT::encode([
            'iss' => env('APPLE_TEAM_ID'),
            'iat' => time(),
            'exp' => time() + (86400 * 180),
            'aud' => 'https://appleid.apple.com',
            'sub' => env('APPLE_CLIENT_ID'),
        ], $privateKey, 'ES256', env('APPLE_KEY_ID'));
    }
}
