<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha;

use Ddr\FilamentCaptcha\Drivers\CaptchaDriver;
use Ddr\FilamentCaptcha\Drivers\{HCaptchaDriver, RecaptchaV2Driver, RecaptchaV3Driver, TurnstileDriver};
use InvalidArgumentException;

class CaptchaManager
{
    /** @var array<string, CaptchaDriver> */
    protected array $drivers = [];

    public function driver(?string $name = null): CaptchaDriver
    {
        $name ??= $this->getDefaultDriver();

        if (! isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->createDriver($name);
        }

        return $this->drivers[$name];
    }

    protected function getDefaultDriver(): string
    {
        /** @var string */
        return config('captcha.driver', 'hcaptcha');
    }

    protected function createDriver(string $name): CaptchaDriver
    {
        /** @var array<string, mixed> */
        $config = config("captcha.{$name}", []);

        return match ($name) {
            'hcaptcha' => new HCaptchaDriver($config),
            'recaptcha_v2' => new RecaptchaV2Driver($config),
            'recaptcha_v3' => new RecaptchaV3Driver($config),
            'turnstile' => new TurnstileDriver($config),
            default => throw new InvalidArgumentException("Driver [{$name}] is not supported."),
        };
    }
}
