document.addEventListener('alpine:init', () => {
	window.Alpine.data('countdownTimerAppJs', (seconds = 0) => ({
		remaining: Number(seconds),

		init() {
			if (this.remaining <= 0) {
				return;
			}

			setInterval(() => {
				this.remaining = Math.max(this.remaining - 1, 0);
			}, 1000);
		},

		format(value) {
			const hours = String(Math.floor(value / 3600)).padStart(2, '0');
			const minutes = String(Math.floor((value % 3600) / 60)).padStart(2, '0');
			const secondsLeft = String(value % 60).padStart(2, '0');

			return `${hours}:${minutes}:${secondsLeft}`;
		},
	}));
});

/* Theme logic disabled by request */
window.sipirangTheme = {
	toggle() { return false; },
	setDark() { },
	setLight() { },
	isDark() { return false; }
};

document.addEventListener('DOMContentLoaded', () => {
	document.documentElement.classList.remove('dark-mode');
});
