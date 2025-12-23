<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha\Drivers;

abstract class CaptchaDriver
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(protected array $config)
    {
    }

    abstract public function getSiteKey(): ?string;

    abstract public function verify(string $token): bool;

    abstract public function getScriptUrl(): string;

    abstract public function getView(): string;
}
