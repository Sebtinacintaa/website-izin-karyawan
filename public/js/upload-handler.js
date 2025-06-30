document.addEventListener("DOMContentLoaded", function () {
    // ==== Elemen Upload ====
    const fileInput = document.getElementById('fileInput');
    const fileNamePreview = document.getElementById('fileNamePreview');
    const previewImage = document.getElementById('previewImage');
    const previewPdf = document.getElementById('previewPdf');
    const previewText = document.getElementById('previewText');
    const previewContainer = document.getElementById('filePreview');
    const uploadIcon = document.getElementById('uploadIcon');
    const dragText = document.getElementById('dragText');

    // ==== Elemen Tanggal Cuti ====
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const totalDaysInput = document.getElementById("total_days");

    // ==== Pencarian ====
    const searchInput = document.getElementById('searchInput');
    const leavesTable = document.querySelector('table');

    // ==== Reset Preview ====
    function resetPreview() {
        previewImage?.classList.add('hidden');
        previewPdf?.classList.add('hidden');
        previewText?.classList.add('hidden');

        if (previewImage) previewImage.src = '';
        if (previewPdf) previewPdf.src = '';
        if (previewText) previewText.textContent = '';

        previewContainer?.classList.remove('opacity-0', 'pointer-events-none');
    }

    // ==== Validasi & Preview File ====
    function validateAndPreviewFile(file) {
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        const maxSize = 2 * 1024 * 1024; // 2 MB

        resetPreview();
        fileNamePreview.textContent = '';
        fileNamePreview.classList.remove('text-red-500', 'text-gray-600');

        if (!allowedTypes.includes(file.type)) {
            fileNamePreview.textContent = 'Format file tidak didukung (hanya PDF, JPG, PNG).';
            fileNamePreview.classList.add('text-red-500');
            return false;
        }

        if (file.size > maxSize) {
            fileNamePreview.textContent = 'Ukuran file terlalu besar. Maksimal 2 MB.';
            fileNamePreview.classList.add('text-red-500');
            return false;
        }

        fileNamePreview.textContent = `File dipilih: ${file.name}`;
        fileNamePreview.classList.add('text-gray-600');
        uploadIcon?.classList.add('hidden');
        if (dragText) dragText.style.display = 'none';

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            previewPdf.src = URL.createObjectURL(file);
            previewPdf.classList.remove('hidden');
        } else {
            previewText.textContent = 'Dokumen tidak didukung.';
            previewText.classList.remove('hidden');
        }

        previewContainer.classList.add('opacity-100');
        return true;
    }

    // ==== Event File Input ====
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && validateAndPreviewFile(file)) showSuccessMessage();
        });
    }

    // ==== Drag & Drop Upload ====
    const dropZone = document.querySelector('[ondragover]');
    if (dropZone) {
        dropZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropZone.classList.add('border-blue-400');
        });

        dropZone.addEventListener('dragleave', function () {
            dropZone.classList.remove('border-blue-400');
        });

        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400');
            const file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                if (validateAndPreviewFile(file)) showSuccessMessage();
            }
        });
    }

    // =======================================================================
    // ==== BAGIAN YANG DIPERBAIKI: Hitung Hari Cuti (Hanya Hari Kerja) ====
    // =======================================================================
    function calculateDays() {
        // Hanya jalankan jika semua elemen input tanggal ada
        if (!startDateInput || !endDateInput || !totalDaysInput) {
            return;
        }

        const startDateValue = startDateInput.value;
        const endDateValue = endDateInput.value;

        // Kosongkan total hari jika salah satu tanggal tidak diisi
        if (!startDateValue || !endDateValue) {
            totalDaysInput.value = '';
            return;
        }

        const start = new Date(startDateValue);
        const end = new Date(endDateValue);

        // Validasi jika tanggal yang dimasukkan tidak valid
        if (isNaN(start) || isNaN(end)) {
            totalDaysInput.value = '';
            return;
        }

        // Validasi jika tanggal mulai lebih besar dari tanggal selesai
        if (start > end) {
            endDateInput.setCustomValidity("Tanggal selesai harus setelah tanggal mulai.");
            endDateInput.reportValidity(); // Tampilkan pesan error bawaan browser
            totalDaysInput.value = '';
            return;
        } else {
            endDateInput.setCustomValidity(""); // Hapus pesan error jika valid
        }

        let workDays = 0;
        let currentDate = new Date(start);

        // Lakukan perulangan dari tanggal mulai hingga tanggal selesai
        while (currentDate <= end) {
            const dayOfWeek = currentDate.getDay(); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu

            // Cek jika hari itu BUKAN Minggu (0) dan BUKAN Sabtu (6)
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                workDays++; // Tambah hitungan jika hari kerja
            }

            // Pindah ke hari berikutnya
            currentDate.setDate(currentDate.getDate() + 1);
        }

        totalDaysInput.value = workDays;
    }
    // =======================================================================
    // ==== AKHIR BAGIAN YANG DIPERBAIKI ====
    // =======================================================================

    // Tambahkan event listener ke input tanggal untuk memanggil fungsi di atas
    startDateInput?.addEventListener("change", calculateDays);
    endDateInput?.addEventListener("change", calculateDays);


    // ==== Notifikasi ====
    function showSuccessMessage() {
        const successAlert = document.getElementById('uploadSuccess');
        if (successAlert) {
            successAlert.classList.remove('hidden');
            setTimeout(() => closeAlert('uploadSuccess'), 3000);
        }
    }

    function closeAlert(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('animate-fade-out');
            setTimeout(() => el.classList.add('hidden'), 500);
        }
    }

    window.closeAlert = closeAlert;

    // ==== Pencarian Riwayat Cuti ====
    if (searchInput && leavesTable) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const rows = leavesTable.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const match = Array.from(cells).some(cell =>
                    cell.textContent.toLowerCase().includes(query)
                );
                row.style.display = match ? '' : 'none';
            });
        });
    }

    // ==== Tombol Filter dan Overlay (Manual Modal Tanpa Alpine) ====
    const filterButton = document.querySelector('[data-filter-button]');
    const filterModal = document.querySelector('.filter-modal');
    const overlay = document.querySelector('.overlay');

    if (filterButton && filterModal && overlay) {
        filterButton.addEventListener('click', function (e) {
            e.preventDefault();
            overlay.classList.remove('hidden');
            filterModal.classList.remove('hidden');
        });

        overlay.addEventListener('click', function () {
            overlay.classList.add('hidden');
            filterModal.classList.add('hidden');
        });

        const resetButton = filterModal.querySelector('[data-reset-button]');
        if (resetButton) {
            resetButton.addEventListener('click', function () {
                overlay.classList.add('hidden');
                filterModal.classList.add('hidden');
            });
        }
    }
});