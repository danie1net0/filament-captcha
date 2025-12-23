<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha\Drivers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RecaptchaV3Driver extends CaptchaDriver
{
    public function getSiteKey(): ?string
    {
        return $this->config['sitekey'] ?? null;
    }

    public function verify(string $token): bool
    {
        /** @var string|null $secret */
        $secret = $this->config['secret'] ?? null;

        if (! $secret) {
            return true;
        }

        /** @var string $verifyUrl */
        $verifyUrl = $this->config['verify_url'] ?? 'https://www.google.com/recaptcha/api/siteverify';

        /** @var Response $response */
        $response = Http::asForm()->post($verifyUrl, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->successful() || $response->json('success') !== true) {
            return false;
        }

        /** @var float $score */
        $score = $response->json('score') ?? 0;

        /** @var float $minimumScore */
        $minimumScore = $this->config['score'] ?? 0.5;

        return $score >= $minimumScore;
    }

    public function getScriptUrl(): string
    {
        $siteKey = $this->getSiteKey();

        return "https://www.google.com/recaptcha/api.js?render={$siteKey}";
    }

    public function getView(): string
    {
        return 'filament-captcha::drivers.recaptcha-v3';
    }
}
