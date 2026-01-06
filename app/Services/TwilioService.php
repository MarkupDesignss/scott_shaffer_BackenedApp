<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendOtp(string $phone, string $message): void
    {
        $this->client->messages->create(
            $phone,
            [
                'from' => config('services.twilio.from'),
                'body' => $message,
            ]
        );
    }
}
