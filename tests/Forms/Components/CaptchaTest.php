<?php

declare(strict_types=1);

use Ddr\FilamentCaptcha\Forms\Components\Captcha;

test('component is required by default', function (): void {
    $component = Captcha::make('captcha');

    expect($component->isRequired())->toBeTrue();
});

test('component is not dehydrated', function (): void {
    $component = Captcha::make('captcha');

    expect($component->isDehydrated())->toBeFalse();
});

test('returns site key from default driver', function (): void {
    $component = Captcha::make('captcha');

    expect($component->getSiteKey())->toBe('test-site-key');
});

test('returns site key from specific driver', function (): void {
    config()->set('captcha.recaptcha_v2.sitekey', 'recaptcha-site-key');

    $component = Captcha::make('captcha')->driver('recaptcha_v2');

    expect($component->getSiteKey())->toBe('recaptcha-site-key');
});

test('returns null when site key is not configured', function (): void {
    config()->set('captcha.hcaptcha.sitekey');

    $component = Captcha::make('captcha');

    expect($component->getSiteKey())->toBeNull();
});

test('returns script url from driver', function (): void {
    $component = Captcha::make('captcha');

    expect($component->getScriptUrl())->toBe('https://js.hcaptcha.com/1/api.js?render=explicit');
});

test('returns driver view', function (): void {
    $component = Captcha::make('captcha');

    expect($component->getDriverView())->toBe('filament-captcha::drivers.hcaptcha');
});

test('can set custom driver', function (): void {
    config()->set('captcha.turnstile.sitekey', 'turnstile-site-key');

    $component = Captcha::make('captcha')->driver('turnstile');

    expect($component->getSiteKey())->toBe('turnstile-site-key')
        ->and($component->getDriverView())->toBe('filament-captcha::drivers.turnstile');
});
