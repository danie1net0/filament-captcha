<?php

declare(strict_types=1);

use Ddr\FilamentCaptcha\Rules\Captcha;
use Illuminate\Support\Facades\Http;

test('fails when value is empty', function (): void {
    $rule = new Captcha();
    $failed = false;

    $rule->validate('captcha', '', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('fails when value is not a string', function (): void {
    $rule = new Captcha();
    $failed = false;

    $rule->validate('captcha', null, function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('passes when secret is not configured', function (): void {
    config()->set('captcha.hcaptcha.secret');

    $rule = new Captcha();
    $failed = false;

    $rule->validate('captcha', 'valid-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('passes when verification is successful', function (): void {
    Http::fake([
        'hcaptcha.com/siteverify' => Http::response(['success' => true]),
    ]);

    $rule = new Captcha();
    $failed = false;

    $rule->validate('captcha', 'valid-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('fails when verification fails', function (): void {
    Http::fake([
        'hcaptcha.com/siteverify' => Http::response(['success' => false]),
    ]);

    $rule = new Captcha();
    $failed = false;

    $rule->validate('captcha', 'invalid-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('fails when http response is not successful', function (): void {
    Http::fake([
        'hcaptcha.com/siteverify' => Http::response([], 500),
    ]);

    $rule = new Captcha();
    $failed = false;

    $rule->validate('captcha', 'valid-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('works with recaptcha v2 driver', function (): void {
    Http::fake([
        'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
    ]);

    config()->set('captcha.recaptcha_v2.secret', 'test-secret');

    $rule = new Captcha('recaptcha_v2');
    $failed = false;

    $rule->validate('captcha', 'valid-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('works with turnstile driver', function (): void {
    Http::fake([
        'challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true]),
    ]);

    config()->set('captcha.turnstile.secret', 'test-secret');

    $rule = new Captcha('turnstile');
    $failed = false;

    $rule->validate('captcha', 'valid-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});
