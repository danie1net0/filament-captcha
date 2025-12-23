@php
  $siteKey = $getSiteKey();
@endphp

@if ($siteKey)
  <div
    class="flex justify-center"
    wire:ignore
    x-data="{
      response: $wire.$entangle('{{ $getStatePath() }}'),
      init() {
        if (typeof turnstile === 'undefined') {
          const script = document.createElement('script')
          script.src = '{{ $getScriptUrl() }}'
          script.async = true
          script.defer = true
          script.onload = () => this.renderCaptcha()
          document.head.appendChild(script)
        } else {
          this.renderCaptcha()
        }
      },
      renderCaptcha() {
        turnstile.render(this.$refs.captcha, {
          sitekey: '{{ $siteKey }}',
          callback: (token) => {
            this.response = token
          },
          'expired-callback': () => {
            this.response = ''
          },
          'error-callback': () => {
            this.response = ''
          },
        })
      },
    }"
  >
    <div x-ref="captcha"></div>
  </div>
@else
  <div class="flex justify-center text-sm text-gray-500">
    {{ __("filament-captcha::messages.not_configured_turnstile") }}
  </div>
@endif
