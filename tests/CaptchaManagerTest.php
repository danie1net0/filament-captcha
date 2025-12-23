<?php

declare(strict_types=1);

use Ddr\FilamentCaptcha\CaptchaManager;
use Ddr\FilamentCaptcha\Drivers\{HCaptchaDriver, RecaptchaV2Driver, RecaptchaV3Driver, TurnstileDriver};

test('returns default driver when no name is provided', function (): void {
    config()->set('captcha.driver', 'hcaptcha');

    $manager = new CaptchaManager();

    expect($manager->driver())->toBeInstanceOf(HCaptchaDriver::class);
});

test('returns hcaptcha driver', function (): void {
    $manager = new CaptchaManager();

    expect($manager->driver('hcaptcha'))->toBeInstanceOf(HCaptchaDriver::class);
});

test('returns recaptcha v2 driver', function (): void {
    $manager = new CaptchaManager();

    expect($manager->driver('recaptcha_v2'))->toBeInstanceOf(RecaptchaV2Driver::class);
});

test('returns recaptcha v3 driver', function (): void {
    $manager = new CaptchaManager();

    expect($manager->driver('recaptcha_v3'))->toBeInstanceOf(RecaptchaV3Driver::class);
});

test('returns turnstile driver', function (): void {
    $manager = new CaptchaManager();

    expect($manager->driver('turnstile'))->toBeInstanceOf(TurnstileDriver::class);
});

test('caches driver instances', function (): void {
    $manager = new CaptchaManager();

    $driver1 = $manager->driver('hcaptcha');
    $driver2 = $manager->driver('hcaptcha');

    expect($driver1)->toBe($driver2);
});

test('throws exception for unsupported driver', function (): void {
    $manager = new CaptchaManager();

    $manager->driver('invalid-driver');
})->throws(InvalidArgumentException::class, 'Driver [invalid-driver] is not supported.');
