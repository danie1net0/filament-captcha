# Filament Captcha

[![Latest Version](https://img.shields.io/packagist/v/ddr/filament-captcha.svg?style=flat-square)](https://packagist.org/packages/ddr/filament-captcha)
[![Total Downloads](https://img.shields.io/packagist/dt/ddr/filament-captcha.svg?style=flat-square)](https://packagist.org/packages/ddr/filament-captcha)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ddr/filament-captcha/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/ddr/filament-captcha/actions?query=workflow%3Atests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ddr/filament-captcha/code-style.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/ddr/filament-captcha/actions?query=workflow%3A"code-style"+branch%3Amaster)
[![License](https://img.shields.io/packagist/l/ddr/filament-captcha.svg?style=flat-square)](https://packagist.org/packages/ddr/filament-captcha)

Multi-provider captcha integration for Filament forms, supporting hCaptcha, reCAPTCHA v2, reCAPTCHA v3, and Cloudflare Turnstile.

## Features

- 🔒 Multiple captcha providers (hCaptcha, reCAPTCHA v2, reCAPTCHA v3, Turnstile)
- 🎨 Seamless integration with Filament forms
- ⚙️ Driver-based architecture for easy extension
- 🧪 Comprehensive test coverage
- 📦 Compatible with Filament v3 and v4
- 🔧 Development mode support

## Installation

```bash
composer require ddr/filament-captcha
```

Publish the configuration file (optional):

```bash
php artisan vendor:publish --tag="captcha-config"
```

## Configuration

Add the following environment variables to your `.env` file based on your chosen provider:

### hCaptcha

```env
CAPTCHA_DRIVER=hcaptcha
HCAPTCHA_SITEKEY=your-site-key
HCAPTCHA_SECRET=your-secret-key
```

Get your keys at [hCaptcha Dashboard](https://dashboard.hcaptcha.com/).

### Google reCAPTCHA v2

```env
CAPTCHA_DRIVER=recaptcha_v2
RECAPTCHA_V2_SITEKEY=your-site-key
RECAPTCHA_V2_SECRET=your-secret-key
```

Get your keys at [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin).

### Google reCAPTCHA v3

```env
CAPTCHA_DRIVER=recaptcha_v3
RECAPTCHA_V3_SITEKEY=your-site-key
RECAPTCHA_V3_SECRET=your-secret-key
RECAPTCHA_V3_SCORE=0.5
```

Get your keys at [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin).

The `RECAPTCHA_V3_SCORE` determines the minimum score required to pass validation (0.0 - 1.0, default: 0.5).

### Cloudflare Turnstile

```env
CAPTCHA_DRIVER=turnstile
TURNSTILE_SITEKEY=your-site-key
TURNSTILE_SECRET=your-secret-key
```

Get your keys at [Cloudflare Turnstile Dashboard](https://dash.cloudflare.com/?to=/:account/turnstile).

## Usage

### In Filament Forms

```php
use Ddr\FilamentCaptcha\Forms\Components\Captcha;

public function form(Form $form): Form
{
    return $form
        ->schema([
            // ... other fields
            Captcha::make('captcha'),
        ]);
}
```

### Specifying a Driver

You can override the default driver:

```php
Captcha::make('captcha')->driver('recaptcha_v2')
```

### Custom Login Page

Create a custom login page extending Filament's base login:

```php
<?php

namespace App\Filament\Pages\Auth;

use Ddr\FilamentCaptcha\Forms\Components\Captcha;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Captcha::make('captcha')->hiddenLabel(),
            ]);
    }
}
```

Then register it in your `AdminPanelProvider`:

```php
->login(\App\Filament\Pages\Auth\Login::class)
```

### Validation Rule

You can use the captcha validation rule independently:

```php
use Ddr\FilamentCaptcha\Rules\Captcha;

$request->validate([
    'captcha' => ['required', new Captcha('hcaptcha')],
]);
```

## Development Mode

When the secret key is not configured for your chosen driver, the captcha will be displayed but validation will be skipped. This is useful for local development.

## Customization

### Publishing Assets

You can publish and customize the package's configuration, views, and translations:

```bash
# Publish configuration file
php artisan vendor:publish --tag="captcha-config"

# Publish views
php artisan vendor:publish --tag="filament-captcha-views"

# Publish translations
php artisan vendor:publish --tag="filament-captcha-translations"
```

### Customizing Views

After publishing views, they will be available in `resources/views/vendor/filament-captcha/`. You can customize:

- **Driver-specific widgets**: `resources/views/vendor/filament-captcha/drivers/*.blade.php`
- **Main component**: `resources/views/vendor/filament-captcha/forms/components/captcha.blade.php`

Laravel will automatically use your published views instead of the package defaults.

### Customizing Configuration

After publishing the config file, you can customize driver settings, verify URLs, and other options in `config/captcha.php`.

## Provider Comparison

| Feature          | hCaptcha     | reCAPTCHA v2       | reCAPTCHA v3       | Turnstile    |
| ---------------- | ------------ | ------------------ | ------------------ | ------------ |
| Privacy-focused  | ✅           | ❌                 | ❌                 | ✅           |
| User interaction | ✅ Checkbox  | ✅ Checkbox        | ❌ Invisible       | ⚡ Smart     |
| Score-based      | ❌           | ❌                 | ✅                 | ✅           |
| Free tier        | ✅ Unlimited | ✅ 1M/month        | ✅ 1M/month        | ✅ Unlimited |
| GDPR compliant   | ✅           | ⚠️ Requires config | ⚠️ Requires config | ✅           |

## Testing

```bash
composer test          # Run tests
composer lint          # Check code style
composer lint:fix      # Fix code style
composer analyse       # Run static analysis
composer check         # Run all checks
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
