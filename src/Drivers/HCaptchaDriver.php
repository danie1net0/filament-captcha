<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha\Drivers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HCaptchaDriver extends CaptchaDriver
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
        $verifyUrl = $this->config['verify_url'] ?? 'https://hcaptcha.com/siteverify';

        /** @var Response $response */
        $response = Http::asForm()->post($verifyUrl, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        return $response->successful() && $response->json('success') === true;
    }

    public function getScriptUrl(): string
    {
        return 'https://js.hcaptcha.com/1/api.js?render=explicit';
    }

    public function getView(): string
    {
        return 'filament-captcha::drivers.hcaptcha';
    }
}
