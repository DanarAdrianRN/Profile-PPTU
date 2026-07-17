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
});
