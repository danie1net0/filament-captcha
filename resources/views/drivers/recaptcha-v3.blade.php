@php
  $siteKey = $getSiteKey();
@endphp

@if ($siteKey)
  <div
    wire:ignore
    x-data="{
      response: $wire.$entangle('{{ $getStatePath() }}'),
      siteKey: '{{ $siteKey }}',
      init() {
        if (typeof grecaptcha === 'undefined') {
          const script = document.createElement('script')
          script.src = '{{ $getScriptUrl() }}'
          script.async = true
          script.defer = true
          script.onload = () => {
            grecaptcha.ready(() => {
              this.executeRecaptcha()
            })
          }
          document.head.appendChild(script)
        } else {
          grecaptcha.ready(() => {
            this.executeRecaptcha()
          })
        }
      },
      executeRecaptcha() {
        grecaptcha.execute(this.siteKey, { action: 'submit' }).then((token) => {
          this.response = token
        })
      },
    }"
  >
    <input type="hidden" x-model="response" />
  </div>
@else
  <div class="flex justify-center text-sm text-gray-500">
    {{ __("filament-captcha::messages.not_configured_recaptcha_v3") }}
  </div>
@endif
