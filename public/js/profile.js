// =======================
// Modal Konfirmasi Umum
// =======================
function showModal(message, title = "Konfirmasi", confirmText = "Ya", confirmCallback = null) {
    let existing = document.getElementById('globalConfirmModal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'globalConfirmModal';
    modal.className = 'fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50';

    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">${title}</h2>
            <p class="text-sm text-gray-600 mb-4">${message}</p>
            <div class="flex justify-center gap-3">
                <button class="px-4 py-2 bg-gray-300 text-gray-800 rounded cancelBtn">Batal</button>
                <button class="px-4 py-2 bg-red-600 text-white rounded confirmBtn">${confirmText}</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    modal.querySelector('.cancelBtn').onclick = () => modal.remove();
    modal.querySelector('.confirmBtn').onclick = () => {
        modal.remove();
        if (typeof confirmCallback === 'function') confirmCallback();
    };
}

// =======================
// Preview & Update Foto Profil
// =======================
function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const imageUrl = e.target.result;

        document.getElementById('profilePreview')?.setAttribute('src', imageUrl);
        document.querySelectorAll('.user-avatar').forEach(avatar => avatar.setAttribute('src', imageUrl));

        const deleteInput = document.getElementById('deleteAvatarInput');
        if (deleteInput) deleteInput.value = '0';

        showToast("Foto berhasil diubah", "success");
    };
    reader.readAsDataURL(file);
}

// =======================
// Hapus Foto Profil
// =======================
function removePhoto() {
    showModal("Apakah Anda yakin ingin menghapus foto profil?", "Hapus Foto", "Hapus", confirmDeletePhoto);
}

function confirmDeletePhoto() {
    const defaultAvatar = `https://ui-avatars.com/api/?name=S&background=B89F83&color=fff&t=${Date.now()}`;

    document.getElementById('profilePreview')?.setAttribute('src', defaultAvatar);
    document.querySelectorAll('.user-avatar').forEach(avatar => avatar.setAttribute('src', defaultAvatar));

    const deleteInput = document.getElementById('deleteAvatarInput');
    if (deleteInput) {
        deleteInput.value = '1';
    }

    showToast("Foto berhasil dihapus.", "info");
}

// =======================
// Toggle Password Visibility
// =======================
function togglePassword() {
    const input = document.getElementById('password');
    const icon = input?.nextElementSibling?.querySelector('i');

    if (!input || !icon) return;

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

// =======================
// Submit Form Profil (AJAX)
// =======================
function handleSubmit(event) {
    event.preventDefault();
    const form = event.target;

    if (!form.checkValidity()) {
        showToast("Form tidak lengkap atau ada kesalahan.", "error");
        return;
    }

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSaveSuccessModal(data.message || "Profil berhasil disimpan.");
        } else {
            showToast(data.message || "Gagal menyimpan profil.", "error");
        }
    })
    .catch(() => {
        showToast("Terjadi kesalahan saat menyimpan profil.", "error");
    });
}

// =======================
// Modal Sukses Simpan
// =======================
function showSaveSuccessModal(message = "Profil berhasil disimpan.") {
    const overlay = document.getElementById('save-success-overlay');
    const modal = document.getElementById('saveSuccessModal');
    const messageElement = document.getElementById('saveSuccessMessage');

    if (messageElement) messageElement.textContent = message;

    if (overlay) overlay.classList.remove('hidden');
    if (modal) modal.classList.remove('hidden');
}

function hideSaveSuccessModal() {
    document.getElementById('saveSuccessModal')?.classList.add('hidden');
    document.getElementById('save-success-overlay')?.classList.add('hidden');
}

function goToDashboard() {
    window.location.href = "/dashboard";
}

// =======================
// Toast Notification
// =======================
function showToast(message, type = "success") {
    const toastContainer = document.getElementById("toastContainer");
    if (!toastContainer) return;

    const colors = {
        success: "bg-green-500",
        error: "bg-red-500",
        info: "bg-blue-500",
        warning: "bg-yellow-500"
    };

    const toast = document.createElement("div");
    toast.className = `${colors[type] || "bg-gray-800"} text-white px-4 py-2 rounded shadow-md animate-fade-in`;
    toast.textContent = message;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("opacity-0");
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 3000);
}

// =======================
// Event Listeners
// =======================
document.addEventListener("DOMContentLoaded", () => {
    const saveBtn = document.getElementById('saveSuccessBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', () => {
            hideSaveSuccessModal();
            goToDashboard();
        });
    }

    const form = document.getElementById('profileForm');
    if (form) {
        form.addEventListener('submit', handleSubmit);
    }
});
