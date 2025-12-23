<?php

declare(strict_types=1);

use Ddr\FilamentCaptcha\Drivers\RecaptchaV3Driver;
use Illuminate\Support\Facades\Http;

test('returns site key from config', function (): void {
    $driver = new RecaptchaV3Driver(['sitekey' => 'test-site-key']);

    expect($driver->getSiteKey())->toBe('test-site-key');
});

test('returns null when site key is not configured', function (): void {
    $driver = new RecaptchaV3Driver([]);

    expect($driver->getSiteKey())->toBeNull();
});

test('returns script url with site key', function (): void {
    $driver = new RecaptchaV3Driver(['sitekey' => 'test-site-key']);

    expect($driver->getScriptUrl())->toBe('https://www.google.com/recaptcha/api.js?render=test-site-key');
});

test('returns view name', function (): void {
    $driver = new RecaptchaV3Driver([]);

    expect($driver->getView())->toBe('filament-captcha::drivers.recaptcha-v3');
});

test('passes verification when secret is not configured', function (): void {
    $driver = new RecaptchaV3Driver([]);

    expect($driver->verify('any-token'))->toBeTrue();
});

test('passes verification when api returns success with high score', function (): void {
    Http::fake([
        'www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.9,
        ]),
    ]);

    $driver = new RecaptchaV3Driver(['secret' => 'test-secret']);

    expect($driver->verify('valid-token'))->toBeTrue();
});

test('fails verification when score is below minimum', function (): void {
    Http::fake([
        'www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.3,
        ]),
    ]);

    $driver = new RecaptchaV3Driver(['secret' => 'test-secret', 'score' => 0.5]);

    expect($driver->verify('low-score-token'))->toBeFalse();
});

test('uses default minimum score of 0.5', function (): void {
    Http::fake([
        'www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.6,
        ]),
    ]);

    $driver = new RecaptchaV3Driver(['secret' => 'test-secret']);

    expect($driver->verify('valid-token'))->toBeTrue();
});

test('uses custom minimum score', function (): void {
    Http::fake([
        'www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.8,
        ]),
    ]);

    $driver = new RecaptchaV3Driver(['secret' => 'test-secret', 'score' => 0.7]);

    expect($driver->verify('valid-token'))->toBeTrue();
});

test('fails verification when api returns failure', function (): void {
    Http::fake([
        'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false]),
    ]);

    $driver = new RecaptchaV3Driver(['secret' => 'test-secret']);

    expect($driver->verify('invalid-token'))->toBeFalse();
});

test('fails verification when http request fails', function (): void {
    Http::fake([
        'www.google.com/recaptcha/api/siteverify' => Http::response([], 500),
    ]);

    $driver = new RecaptchaV3Driver(['secret' => 'test-secret']);

    expect($driver->verify('any-token'))->toBeFalse();
});

test('uses custom verify url when configured', function (): void {
    Http::fake([
        'custom.verify.url' => Http::response([
            'success' => true,
            'score' => 0.9,
        ]),
    ]);

    $driver = new RecaptchaV3Driver([
        'secret' => 'test-secret',
        'verify_url' => 'https://custom.verify.url',
    ]);

    expect($driver->verify('valid-token'))->toBeTrue();
});
