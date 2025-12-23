<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha;

use Spatie\LaravelPackageTools\{Package, PackageServiceProvider};

class FilamentCaptchaServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-captcha';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile('captcha')
            ->hasViews()
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(CaptchaManager::class, fn (): CaptchaManager => new CaptchaManager());
    }
}
