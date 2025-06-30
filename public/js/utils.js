// utils.js

/**
 * Fungsi menampilkan overlay + modal dinamis
 */
function showModal(message, title = "Konfirmasi", confirmText = "Ya", onConfirm = null) {
    const messageElement = document.getElementById('modal-message');
    const titleElement = document.getElementById('modal-title');
    const confirmButton = document.getElementById('modal-confirm-button');

    if (messageElement) messageElement.textContent = message;
    if (titleElement) titleElement.textContent = title;

    if (confirmButton && onConfirm) {
        confirmButton.textContent = confirmText;
        confirmButton.onclick = function(event) {
            event.preventDefault();
            event.stopPropagation();
            onConfirm(event);
        };
    }

    showOverlay();
}

function hideOverlay() {
    const overlay = document.getElementById("modal-overlay");
    if (overlay) {
        overlay.classList.add("hidden");
    }
}

function showOverlay(message, onConfirm) {
    const overlay = document.getElementById("modal-overlay");
    const messageEl = document.getElementById("modal-message");
    const confirmBtn = document.getElementById("modal-confirm-button");

    if (!overlay || !messageEl || !confirmBtn) return;

    messageEl.textContent = message;
    confirmBtn.onclick = function () {
        onConfirm();
        overlay.classList.add("hidden");
    };

    overlay.classList.remove("hidden");
}