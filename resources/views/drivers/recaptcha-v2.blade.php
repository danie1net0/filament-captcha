<div
  class="flex justify-center"
  wire:ignore
  x-data="{
    response: $wire.$entangle('{{ $getStatePath() }}'),
    init() {
      if (typeof grecaptcha === 'undefined') {
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
      grecaptcha.render(this.$refs.captcha, {
        sitekey: '{{ $getSiteKey() }}',
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
