<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha\Tests;

use Ddr\FilamentCaptcha\FilamentCaptchaServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            SupportServiceProvider::class,
            FilamentCaptchaServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('captcha.driver', 'hcaptcha');
        $app['config']->set('captcha.hcaptcha.sitekey', 'test-site-key');
        $app['config']->set('captcha.hcaptcha.secret', 'test-secret-key');
        $app['config']->set('captcha.hcaptcha.verify_url', 'https://hcaptcha.com/siteverify');
    }
}
