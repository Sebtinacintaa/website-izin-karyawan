document.addEventListener("DOMContentLoaded", function () {
    const notificationList = document.getElementById("notification-list");
    const clearBtn = document.getElementById("clearNotificationsBtn");
    const confirmClearBtn = document.getElementById("confirm-clear-btn");
    const modal = document.getElementById("confirmClearModal");

    const userId = notificationList?.dataset.userId;

    if (!notificationList) return;

    // === Buka Modal Konfirmasi Clear Semua Notifikasi ===
    if (clearBtn && modal) {
        clearBtn.addEventListener("click", function (e) {
            e.preventDefault();
            modal.classList.remove("hidden");
        });
    }

    // === Tutup Modal (klik luar atau tombol batal) ===
    if (modal) {
        modal.addEventListener("click", function (e) {
            const target = e.target;
            if (target === modal || target.closest("[data-close-modal]")) {
                modal.classList.add("hidden");
            }
        });
    }

    // === Kirim Request Clear Semua Notifikasi ===
    if (confirmClearBtn && modal) {
        confirmClearBtn.addEventListener("click", async function () {
            const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content;

            try {
                const response = await fetch(window.routes.clear, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                });

                const result = await response.json();

                if (result.status === "success") {
                    showToast("Semua notifikasi telah dihapus.");
                    notificationList.innerHTML = `<p class="text-gray-500 text-center mt-10 italic">Tidak ada notifikasi.</p>`;
                    modal.classList.add("hidden");
                } else {
                    showToast("Gagal menghapus notifikasi.", "error");
                }
            } catch (error) {
                console.error("Error:", error);
                showToast("Terjadi kesalahan saat menghapus notifikasi.", "error");
            }
        });
    }

    // === Filter Tabs ===
    document.querySelectorAll("[data-type]").forEach(tab => {
        tab.addEventListener("click", function () {
            const type = this.getAttribute("data-type");
            const pollUrl = window.routes.poll.replace('__USER_ID__', userId);

            fetch(`${pollUrl}?type=${type}`)
                .then(res => res.json())
                .then(data => {
                    renderNotifications(data.notifications);
                    highlightActiveTab(type);
                })
                .catch(() => alert("Tidak dapat memuat notifikasi."));
        });
    });

    // === Highlight Tab Aktif ===
    function highlightActiveTab(type) {
        document.querySelectorAll("[data-type]").forEach(tab => {
            tab.classList.remove("text-blue-600", "border-b", "border-blue-600");
        });
        const activeTab = document.querySelector(`[data-type='${type}']`);
        if (activeTab) {
            activeTab.classList.add("text-blue-600", "border-b", "border-blue-600");
        }
    }

    // === Render Notifikasi ===
    function renderNotifications(notifications) {
        if (!notifications || !notifications.length) {
            notificationList.innerHTML = `<p class="text-gray-500 text-center mt-10 italic">Tidak ada notifikasi.</p>`;
            return;
        }

        notificationList.innerHTML = notifications.map(notif => {
            const title = notif.title ?? 'Tanpa Judul';
            const message = notif.message ?? 'Tidak ada pesan.';
            const date = notif.date ?? '-';
            const isRead = notif.read;
            const notifId = notif.id;

            const statusLabel = isRead
                ? `<span class="status-badge read bg-green-100 text-green-700 text-xs px-2 py-1 rounded">Sudah Dibaca</span>`
                : `<span class="status-badge new bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">Baru</span>`;

            const boxBg = isRead ? 'bg-green-50' : 'bg-gray-50';

            return `
                <div class="notification-item flex items-start gap-4 p-4 ${boxBg} hover:bg-gray-100 transition duration-200 rounded-lg shadow-sm border border-gray-200 mb-3 cursor-pointer"
                     data-read="${isRead}" 
                     data-id="${notifId}">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full mr-4 ${getBgColor(title)}">
                        ${getIcon(title)}
                    </div>
                    <div class="flex-1 ml-3">
                        <p class="font-semibold text-sm">${title}</p>
                        <p class="text-xs text-gray-600 mt-1">${message}</p>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        ${statusLabel}
                        <span class="text-xs text-gray-500 whitespace-nowrap">${date}</span>
                        <button class="delete-notification text-red-500 hover:text-red-700" data-id="${notifId}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join("");
    }

    // === Hapus Satu Notifikasi ===
    notificationList.addEventListener("click", function (e) {
        const deleteBtn = e.target.closest(".delete-notification");
        if (deleteBtn) {
            const notifId = deleteBtn.dataset.id;
            const notifItem = deleteBtn.closest(".notification-item");

            if (confirm("Hapus notifikasi ini?")) {
                const deleteUrl = window.routes.delete.replace('__ID__', notifId);

                fetch(deleteUrl, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]')?.content,
                        "Content-Type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(() => {
                        notifItem.classList.add("opacity-0");
                        setTimeout(() => {
                            notifItem.remove();
                            if (notificationList.children.length === 0) {
                                notificationList.innerHTML = `<p class="text-gray-500 text-center mt-10 italic">Tidak ada notifikasi.</p>`;
                            }
                        }, 300);
                    })
                    .catch(() => showToast("Gagal menghapus notifikasi.", "error"));
            }
            return;
        }

        // === Klik Notifikasi = Tandai Dibaca ===
        const notifItem = e.target.closest(".notification-item");
        if (notifItem && notifItem.dataset.read === "false") {
            const notifId = notifItem.dataset.id;
            const readUrl = window.routes.read.replace('__ID__', notifId);

            fetch(readUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]')?.content,
                    "Accept": "application/json"
                }
            })
                .then(res => res.json())
                .then(() => {
                    notifItem.classList.replace("bg-gray-50", "bg-green-50");
                    notifItem.dataset.read = "true";

                    const statusSpan = notifItem.querySelector(".status-badge");
                    if (statusSpan) {
                        statusSpan.classList.replace("new", "read");
                        statusSpan.classList.replace("bg-gray-200", "bg-green-100");
                        statusSpan.classList.replace("text-gray-700", "text-green-700");
                        statusSpan.textContent = "Sudah Dibaca";
                    }
                })
                .catch(err => console.error("Gagal tandai dibaca", err));
        }
    });

    // === Polling Otomatis Setiap 10 Detik ===
    function poll() {
        const pollUrl = window.routes.poll.replace('__USER_ID__', userId);
        fetch(pollUrl + "?type=all")
            .then(res => res.json())
            .then(data => renderNotifications(data.notifications))
            .catch(() => console.warn("Polling gagal"));
    }

    setInterval(poll, 10000); // polling setiap 10 detik
    poll(); // load awal

    // === Helper untuk Warna & Ikon ===
    function getBgColor(title) {
        title = title.toLowerCase();
        if (title.includes("approved")) return 'bg-green-100 text-green-600';
        if (title.includes("rejected")) return 'bg-red-100 text-red-600';
        if (title.includes("updated")) return 'bg-yellow-100 text-yellow-600';
        if (title.includes("submitted")) return 'bg-blue-100 text-blue-600';
        return 'bg-orange-100 text-orange-600';
    }

    function getIcon(title) {
        title = title.toLowerCase();
        if (title.includes("approved")) return '<i class="fas fa-check-circle text-lg"></i>';
        if (title.includes("rejected")) return '<i class="fas fa-times-circle text-lg"></i>';
        if (title.includes("updated")) return '<i class="fas fa-hourglass-half text-lg"></i>';
        if (title.includes("submitted")) return '<i class="fas fa-info-circle text-lg"></i>';
        return '<i class="fas fa-bell text-lg"></i>';
    }

    // === Fungsi Toast Notification - Muncul di pojok atas kanan ===
    function showToast(message, type = "success") {
        const toast = document.createElement("div");
        toast.className = `fixed top-4 right-4 ${type === "success" ? "bg-green-600" : "bg-red-600"} text-white px-4 py-2 rounded shadow-lg z-50 transition-opacity duration-300`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});