<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha\Rules;

use Closure;
use Ddr\FilamentCaptcha\CaptchaManager;
use Illuminate\Contracts\Validation\ValidationRule;

class Captcha implements ValidationRule
{
    public function __construct(protected ?string $driver = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            $fail(__('filament-captcha::messages.required'));

            return;
        }

        /** @var CaptchaManager $manager */
        $manager = resolve(CaptchaManager::class);

        $driver = $manager->driver($this->driver);

        if (! $driver->verify($value)) {
            $fail(__('filament-captcha::messages.failed'));
        }
    }
}
