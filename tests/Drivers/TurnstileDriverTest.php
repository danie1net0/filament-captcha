<?php

declare(strict_types=1);

use Ddr\FilamentCaptcha\Drivers\TurnstileDriver;
use Illuminate\Support\Facades\Http;

test('returns site key from config', function (): void {
    $driver = new TurnstileDriver(['sitekey' => 'test-site-key']);

    expect($driver->getSiteKey())->toBe('test-site-key');
});

test('returns null when site key is not configured', function (): void {
    $driver = new TurnstileDriver([]);

    expect($driver->getSiteKey())->toBeNull();
});

test('returns script url', function (): void {
    $driver = new TurnstileDriver([]);

    expect($driver->getScriptUrl())->toBe('https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit');
});

test('returns view name', function (): void {
    $driver = new TurnstileDriver([]);

    expect($driver->getView())->toBe('filament-captcha::drivers.turnstile');
});

test('passes verification when secret is not configured', function (): void {
    $driver = new TurnstileDriver([]);

    expect($driver->verify('any-token'))->toBeTrue();
});

test('passes verification when api returns success', function (): void {
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true]),
    ]);

    $driver = new TurnstileDriver(['secret' => 'test-secret']);

    expect($driver->verify('valid-token'))->toBeTrue();
});

test('fails verification when api returns failure', function (): void {
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => false]),
    ]);

    $driver = new TurnstileDriver(['secret' => 'test-secret']);

    expect($driver->verify('invalid-token'))->toBeFalse();
});

test('fails verification when http request fails', function (): void {
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([], 500),
    ]);

    $driver = new TurnstileDriver(['secret' => 'test-secret']);

    expect($driver->verify('any-token'))->toBeFalse();
});

test('uses custom verify url when configured', function (): void {
    Http::fake([
        'custom.verify.url' => Http::response(['success' => true]),
    ]);

    $driver = new TurnstileDriver([
        'secret' => 'test-secret',
        'verify_url' => 'https://custom.verify.url',
    ]);

    expect($driver->verify('valid-token'))->toBeTrue();
});
