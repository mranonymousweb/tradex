<span id="send-otp-btn-{{ $attributes->get('id') ?? uniqid() }}"
      class="mt-5 text-sm text-primary cursor-pointer"
      data-url="{{ $url }}">
    @lang('general.send_code')
</span>
<span id="otp-timer-{{ $attributes->get('id') ?? uniqid() }}" class="text-sm text-gray-500 ml-2"></span>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sendBtn = document.querySelector('[data-url="{{ $url }}"]');
            const timerSpan = sendBtn.nextElementSibling; // assumes timer span is next
            const cooldown = {{ $cooldown }};
            let timer = null;

            function disableBtn(disabled = true) {
                if (disabled) {
                    sendBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    sendBtn.style.pointerEvents = 'none';
                } else {
                    sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    sendBtn.style.pointerEvents = 'auto';
                }
            }

            function updateTimer(value) {
                timerSpan.textContent = value > 0 ? `(${value}s)` : '';
            }

            function resetTimer() {
                clearInterval(timer);
                updateTimer(0);
                disableBtn(false);
            }

            function startTimer(seconds) {
                let remaining = seconds;
                disableBtn(true);
                updateTimer(remaining);

                timer = setInterval(() => {
                    remaining--;
                    updateTimer(remaining);
                    if (remaining <= 0) resetTimer();
                }, 1000);
            }

            async function sendCode() {
                try {
                    const res = await fetch(sendBtn.dataset.url, {
                        method: 'GET',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    const data = await res.json();
                    toastAlert(data.message, data.success ? 'success' : 'error');
                    if (data.success) startTimer(cooldown);
                } catch (err) {
                    console.error("OTP Send Error:", err);
                }
            }

            sendBtn.addEventListener('click', sendCode);
            @if($send)
            sendCode();
            @endif
        });
    </script>
@endpush
