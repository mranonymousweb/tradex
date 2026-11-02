class ResendButton {
    constructor(button) {
        this.button = button;
        this.buttonText = button.querySelector('.button_text');
        this.timerDisplay = button.querySelector('.timer');
        this.spinner = button.querySelector('.spinner');
        this.tokenTimeLeft = parseInt(button.dataset.timeleft) || 0;
        this.sendUrl = button.dataset.url;

        this.countdownInterval = null;

        this.init();
    }

    init() {
        this.button.addEventListener('click', () => this.sendMobileCode());

        if (this.tokenTimeLeft > 0) {
            this.setCountdown(this.tokenTimeLeft);
        }
    }

    setCountdown(seconds) {
        clearInterval(this.countdownInterval);
        let remaining = Math.floor(seconds);

        this.button.disabled = true;
        this.buttonText.classList.add('hidden');
        this.timerDisplay.classList.remove('hidden');
        this.spinner.classList.add('hidden');

        this.countdownInterval = setInterval(() => {
            const min = String(Math.floor(remaining / 60)).padStart(2, '0');
            const sec = String((remaining % 60)).padStart(2, '0');
            this.timerDisplay.innerText = `${min}:${sec}`;

            if (--remaining < 0) {
                clearInterval(this.countdownInterval);
                this.timerDisplay.classList.add('hidden');
                this.buttonText.classList.remove('hidden');
                this.button.disabled = false;
            }
        }, 1000);
    }

    sendMobileCode() {
        $.ajax({
            url: this.sendUrl,
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            beforeSend: () => {
                this.button.disabled = true;
                this.buttonText.classList.add('hidden');
                this.spinner.classList.remove('hidden');
                this.timerDisplay.classList.add('hidden');
            },
            success: (response) => {
                this.spinner.classList.add('hidden');
                this.setCountdown(response.time);
            },
            error: (err) => {
                console.error(err);
                this.spinner.classList.add('hidden');
                this.buttonText.classList.remove('hidden');
                this.button.disabled = false;
            }
        });
    }
}
