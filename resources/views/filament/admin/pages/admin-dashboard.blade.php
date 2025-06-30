<x-filament-panels::page>
    {{-- Filament secara otomatis mencetak judul halaman di atas. Konten kita dimulai setelah itu. --}}

    @if (auth()->user()->hasRole('admin'))
        {{-- Konten Dashboard untuk Administrator --}}
        <div class="space-y-6">
            {{-- Bagian ini adalah placeholder visual di Blade. Widget nyata dirender via getHeaderWidgets(). --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Ringkasan Laporan Sistem</h3>
                <p class="text-gray-600 dark:text-gray-400">Di sini Anda dapat menampilkan grafik atau tabel ringkasan kinerja sistem secara keseluruhan.</p>
                <div class="mt-4 border-2 border-dashed border-gray-300 dark:border-gray-700 p-8 rounded-lg text-center text-gray-500">
                    Area ini sekarang kosong karena widget dihandle oleh getHeaderWidgets().
                </div>
            </div>

            {{-- Anda bisa menambahkan bagian lain di sini, seperti daftar aktivitas terbaru, dll. --}}
        </div>

    @elseif (auth()->user()->hasRole('atasan'))
        {{-- Konten Dashboard untuk Supervisor/Atasan --}}
        <div class="space-y-6">
            {{-- Bagian ini adalah placeholder visual di Blade. Widget nyata dirender via getHeaderWidgets(). --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Permintaan Izin Tim Terbaru</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Berikut adalah daftar permintaan izin terbaru dari tim Anda yang perlu perhatian.</p>
                <div class="mt-4 border-2 border-dashed border-gray-300 dark:border-gray-700 p-8 rounded-lg text-center text-gray-500">
                    Area ini sekarang kosong karena widget dihandle oleh getHeaderWidgets().
                </div>
            </div>

            {{-- Anda bisa menambahkan bagian lain di sini, seperti laporan kinerja bawahan, dll. --}}
        </div>

    @else
        {{-- Konten default jika user tidak memiliki peran admin atau atasan --}}
        <div class="p-6 bg-white rounded-lg shadow-md">
            <p class="text-gray-700 dark:text-gray-300">Selamat datang di Dashboard Anda. Tidak ada konten khusus yang tersedia untuk peran Anda saat ini.</p>
        </div>
    @endif

    {{-- Hook ini akan merender widget yang dikembalikan oleh getHeaderWidgets() di kelas halaman Anda.
         INI ADALAH TEMPAT WIDGET STATISTIK DAN GRAFIK ANDA DIRENDER SECARA OTOMATIS. --}}
    {{ \Filament\Facades\Filament::renderHook('panels::page.header.widgets.after') }}
</x-filament-panels::page>
