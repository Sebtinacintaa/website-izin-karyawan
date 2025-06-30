<?php // PASTI TIDAK ADA KARAKTER APAPUN DI ATAS BARIS INI

namespace App\Filament\Admin\Widgets; // PASTI BARIS INI TEPAT DI BAWAH <?php, TANPA SPASI

use Filament\Widgets\Widget; // Pastikan ini Widget
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On; // Untuk Livewire listener

class LeaveRequestStatusChart extends Widget
{
    protected static ?string $heading = 'Status Permintaan Izin'; // Judul Widget
    protected static ?int $sort = 3;
    protected static string $color = 'info'; // Warna widget

    protected array|string|int $columnSpan = 1; // Mengambil 1 kolom

    public ?string $filter = null; // Properti Livewire untuk filter waktu

    protected static string $view = 'filament.admin.widgets.leave-request-status-chart'; // View kustom untuk widget ini

    public function mount(): void // Ubah protected ke public
    {
        if (is_null($this->filter)) {
            $this->filter = 'total'; // Ubah default filter untuk doughnut
        }
    }

    public function getHeading(): string // Metode ini diperlukan karena extends Widget
    {
        return static::$heading;
    }

    // Metode untuk mengambil data grafik (dipanggil di Blade widget)
    public function getChartData(): array // Ubah protected ke public
    {
        // --- DATA DUMMY SEMENTARA UNTUK MEMASTIKAN GRAFIK MUNCUL ---
        // Setelah grafik muncul, Anda bisa kembalikan ke query database sebenarnya
        $pendingCount = 5; 
        $approvedCount = 10;
        $rejectedCount = 2;
        // --- AKHIR DATA DUMMY ---

        // Untuk doughnut chart, kembalikan data sebagai satu array tunggal untuk dataset
        $dataToReturn = [
            'labels' => ['Menunggu', 'Disetujui', 'Ditolak'], // Label untuk setiap bagian pie
            'data' => [$pendingCount, $approvedCount, $rejectedCount], // Data nilai
            'backgroundColor' => [ // Warna latar belakang
                'rgba(255, 159, 64, 0.8)', // Oranye untuk Menunggu
                'rgba(75, 192, 192, 0.8)', // Hijau untuk Disetujui
                'rgba(255, 99, 132, 0.8)', // Merah untuk Ditolak
            ],
            'borderColor' => 'rgba(255, 255, 255, 1)', // Warna border antar segmen
            'borderWidth' => 2,
            'hoverOffset' => 4, // Efek sedikit saat di-hover
        ];
        
        // dd($dataToReturn); // Uncomment this line temporarily to check data in browser

        return $dataToReturn;
    }

    // Opsi Chart.js - ini akan dipanggil di Blade
    public function getChartOptions(): array // Ubah protected ke public
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom', // Legend di bawah grafik
                    'labels' => [
                        'font' => [
                            'size' => 12,
                        ],
                        'color' => '#333',
                    ],
                ],
                'tooltip' => [ // Mengatur tooltip saat di-hover
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.label || "";
                            if (label) {
                                label += ": ";
                            }
                            if (context.parsed) {
                                label += context.parsed + " permintaan";
                            }
                            return label;
                        }',
                    ],
                ],
            ],
            // Aspek rasio untuk mengontrol bentuk grafik
            'aspectRatio' => 1, // 1:1, membuat grafik berbentuk lingkaran sempurna (persegi)
            'cutout' => '70%', // Mengatur ukuran lubang di tengah untuk doughnut chart
            // Event listener untuk klik (memanggil metode Livewire)
            'onClick' => 'function(evt, element) {
                if (element.length > 0) {
                    let index = element[0].index;
                    let label = this.data.labels[index];
                    Livewire.dispatch(\'chartSegmentClicked\', { label: label, value: this.data.datasets[0].data[index] }); // Event baru untuk klik segmen
                }
            }',
        ];
    }

    protected function getFilters(): ?array
    {
        // Untuk doughnut chart, kita mungkin tidak butuh filter Bulanan/Triwulanan/Tahunan.
        // Jika Anda ingin filter untuk melihat status izin dalam rentang waktu tertentu,
        // Anda bisa mengaktifkan filter di sini dan mengimplementasikan logikanya di getChartData().
        return [
            'total' => 'Total', // Contoh filter sederhana untuk total semua waktu
            // 'monthly' => 'Bulanan', // Jika Anda ingin filter waktu
        ];
    }
    
    // Metode untuk mengubah filter (jika ada tombol filter di Blade)
    public function setFilter(string $filter): void 
    {
        $this->filter = $filter;
        // Tidak perlu dispatch 'chartUpdated' lagi, karena Livewire akan me-render ulang komponen
        // dan chart akan diinisialisasi ulang dengan data baru.
    }

    // Listener untuk event klik segmen chart
    #[On('chartSegmentClicked')] 
    public function onChartSegmentClicked(array $data): void
    {
        \Filament\Notifications\Notification::make()
            ->title('Segmen Grafik Diklik!')
            ->body('Status: ' . $data['label'] . ' (' . $data['value'] . ' permintaan)')
            ->info()
            ->send();
    }
}
