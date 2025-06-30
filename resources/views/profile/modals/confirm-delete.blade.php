<!-- Confirm Delete Modal -->
<div id="confirm-delete-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-auto text-center px-6 py-8 relative">
        <span id="confirm-delete-close" onclick="hideOverlay()" class="absolute top-2 right-2 text-xl cursor-pointer">&times;</span>
        <h3 id="confirm-delete-title" class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
        <p id="confirm-delete-message" class="text-gray-600 mb-6">Apakah Anda yakin ingin melanjutkan?</p>
        <div id="confirm-delete-actions" class="mt-4 flex justify-end gap-4">
            <button onclick="hideOverlay()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
            <button id="confirm-delete-button" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
        </div>
    </div>
</div>

<script>
    function showConfirmDelete(message, confirmText = "Hapus", onConfirm = null) {
        document.getElementById('confirm-delete-message').textContent = message;
        document.getElementById('confirm-delete-button').textContent = confirmText;

        if (onConfirm && typeof onConfirm === 'function') {
            document.getElementById('confirm-delete-button').onclick = function(event) {
                event.preventDefault();
                event.stopPropagation();
                onConfirm(event);
            };
        }

        document.getElementById('confirm-delete-modal').classList.remove('hidden');
    }
</script>