/**
 * Fungsi logout utama (dipanggil dari tombol)
 */
function logout(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    // Tampilkan modal konfirmasi universal
    showModal(
        "Apakah Anda yakin ingin keluar dari akun?",
        "Konfirmasi Logout",
        "Keluar",
        proceedLogout
    );
}

/**
 * Fungsi untuk menampilkan modal universal
 * @param {string} message - Isi pesan modal
 * @param {string} title - Judul modal
 * @param {string} confirmText - Teks tombol konfirmasi
 * @param {Function} onConfirm - Fungsi yang dipanggil saat klik "Ya"
 */
function showModal(message, title, confirmText, onConfirm) {
    const modal = document.getElementById('universal-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalConfirmBtn = document.getElementById('modal-confirm-btn');

    if (!modal || !modalTitle || !modalMessage || !modalConfirmBtn) return;

    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modalConfirmBtn.textContent = confirmText;
    modalConfirmBtn.onclick = function () {
        onConfirm(event);
    };

    modal.classList.remove('hidden');
}

/**
 * Fungsi untuk menutup overlay/modal
 */
function hideOverlay() {
    const modal = document.getElementById('universal-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Fungsi untuk membersihkan cache user (opsional)
 */
function clearUserCache() {
    const avatarElement = document.getElementById('userAvatar');
    if (avatarElement) {
        avatarElement.src = "https://ui-avatars.com/api/?name=Guest&background=cccccc&color=fff";
    }
}

/**
 * Fungsi untuk melanjutkan proses logout
 */
function proceedLogout(event) {
    // Tutup modal jika ada
    hideOverlay();

    // Tampilkan toast sukses
    showToast("Anda telah keluar dari akun.", "success");

    // Hapus token lokal jika tersedia
    if (typeof Storage !== 'undefined') {
        localStorage.removeItem('authToken');
    }

    const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content;

    // Kirim request ke server
    fetch(window.logoutUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal menghubungi server');
        return response.json();
    })
    .then(data => {
        if (data?.success) {
            // Bersihkan data user (jika diperlukan)
            clearUserCache();

            // Redirect setelah 1 detik
            setTimeout(() => {
                window.location.href = "/dashboard";
            }, 1000);
        } else {
            showToast("Logout gagal. Silakan coba lagi.", "error");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast("Terjadi kesalahan saat logout.", "error");
    });
}

/**
 * Toggle visibility input password
 */
function togglePassword() {
    const input = document.getElementById('password');
    const icon = input?.nextElementSibling?.querySelector('i');

    if (!input || !icon) return;

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

/**
 * Fungsi untuk menampilkan Toast Notification
 * @param {string} message - Pesan yang ditampilkan
 * @param {string} type - success / error / info
 */
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) return;

    const colorMap = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };

    const toast = document.createElement('div');
    toast.className = `px-4 py-2 rounded text-white ${colorMap[type] || colorMap['info']} animate-fade-in`;
    toast.innerText = message;
    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.replace('animate-fade-in', 'animate-fade-out');
        setTimeout(() => {
            if (toast && toast.parentNode === toastContainer) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}