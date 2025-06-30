<!-- Alert Modal -->
<div id="alert-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-auto text-center px-6 py-8 relative">
        <span id="alert-close" onclick="hideOverlay()" class="absolute top-2 right-2 text-xl cursor-pointer">&times;</span>
        <h3 id="alert-title" class="text-lg font-semibold mb-4">Informasi</h3>
        <p id="alert-message" class="text-gray-600 mb-6">Pesan alert akan ditampilkan di sini.</p>
        <div id="alert-actions" class="mt-4 flex justify-end gap-4">
            <button onclick="hideOverlay()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Oke</button>
        </div>
    </div>
</div>

<script>
    function showCustomAlert(message) {
        document.getElementById('alert-message').textContent = message;
        document.getElementById('alert-modal').classList.remove('hidden');
    }
</script>