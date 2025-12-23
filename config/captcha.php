<?php

declare(strict_types=1);

return [
    /**
     * Default captcha driver to use.
     * Supported: "hcaptcha", "recaptcha_v2", "recaptcha_v3", "turnstile"
     */
    'driver' => env('CAPTCHA_DRIVER', 'hcaptcha'),

    /**
     * hCaptcha configuration
     */
    'hcaptcha' => [
        'sitekey' => env('HCAPTCHA_SITEKEY'),
        'secret' => env('HCAPTCHA_SECRET'),
        'verify_url' => env('HCAPTCHA_VERIFY_URL', 'https://hcaptcha.com/siteverify'),
    ],

    /**
     * Google reCAPTCHA v2 configuration
     */
    'recaptcha_v2' => [
        'sitekey' => env('RECAPTCHA_V2_SITEKEY'),
        'secret' => env('RECAPTCHA_V2_SECRET'),
        'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
    ],

    /**
     * Google reCAPTCHA v3 configuration
     */
    'recaptcha_v3' => [
        'sitekey' => env('RECAPTCHA_V3_SITEKEY'),
        'secret' => env('RECAPTCHA_V3_SECRET'),
        'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
        'score' => env('RECAPTCHA_V3_SCORE', 0.5),
    ],

    /**
     * Cloudflare Turnstile configuration
     */
    'turnstile' => [
        'sitekey' => env('TURNSTILE_SITEKEY'),
        'secret' => env('TURNSTILE_SECRET'),
        'verify_url' => env('TURNSTILE_VERIFY_URL', 'https://challenges.cloudflare.com/turnstile/v0/siteverify'),
    ],
];
