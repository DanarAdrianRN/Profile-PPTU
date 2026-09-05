import './bootstrap';
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.flash-alert').forEach((alert) => {
        const dismiss = () => alert.remove();
        alert.querySelector('.flash-alert__close')?.addEventListener('click', dismiss);
        window.setTimeout(dismiss, 6000);
    });

    const modalEl = document.getElementById("promoModal");

    if (modalEl) {
        const myModal = new bootstrap.Modal(modalEl);
        myModal.show();
    }

    document.addEventListener('click', async (event) => {
        const shareButton = event.target.closest('[data-share]');

        if (!shareButton) return;

        event.preventDefault();

        const shareData = {
            title: shareButton.dataset.shareTitle || document.title,
            text: shareButton.dataset.shareText || document.title,
            url: shareButton.dataset.shareUrl || window.location.href,
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
                return;
            }

            await navigator.clipboard.writeText(`${shareData.text} ${shareData.url}`);
            window.alert('Tautan berhasil disalin.');
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.prompt('Salin tautan berikut:', shareData.url);
            }
        }
    });
});
