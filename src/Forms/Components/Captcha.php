<?php

declare(strict_types=1);

namespace Ddr\FilamentCaptcha\Forms\Components;

use Ddr\FilamentCaptcha\CaptchaManager;
use Ddr\FilamentCaptcha\Drivers\CaptchaDriver;
use Ddr\FilamentCaptcha\Rules\Captcha as CaptchaRule;
use Filament\Forms\Components\Field;

class Captcha extends Field
{
    protected string $view = 'filament-captcha::forms.components.captcha';

    protected ?string $driver = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->required();
        $this->rules([new CaptchaRule($this->driver)]);
        $this->dehydrated(false);
        $this->extraFieldWrapperAttributes(['class' => 'items-center text-center']);
    }

    public function driver(string $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriver(): CaptchaDriver
    {
        /** @var CaptchaManager $manager */
        $manager = resolve(CaptchaManager::class);

        return $manager->driver($this->driver);
    }

    public function getSiteKey(): ?string
    {
        return $this->getDriver()->getSiteKey();
    }

    public function getScriptUrl(): string
    {
        return $this->getDriver()->getScriptUrl();
    }

    public function getDriverView(): string
    {
        return $this->getDriver()->getView();
    }
}
