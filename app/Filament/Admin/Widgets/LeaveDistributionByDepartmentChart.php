<?php // PASTI TIDAK ADA KARAKTER APAPUN DI ATAS BARIS INI

namespace App\Filament\Admin\Widgets; // PASTI BARIS INI TEPAT DI BAWAH <?php, TANPA SPASI

use Filament\Widgets\ChartWidget; // Pastikan ini ChartWidget
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaveDistributionByDepartmentChart extends ChartWidget // Pastikan ini extends ChartWidget
{
    protected static ?string $heading = 'Statistik Departemen'; // Judul yang lebih pendek
    protected static ?int $sort = 5;
    protected static string $color = 'success';

    protected array|string|int $columnSpan = 1; // Mengambil 1 kolom untuk berdampingan

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        // --- DATA DUMMY SEMENTARA UNTUK MEMASTIKAN GRAFIK MUNCUL ---
        $labels = ['IT', 'HR', 'Marketing', 'Finance'];
        $totalIzin = [5, 3, 7, 2];
        // --- AKHIR DATA DUMMY ---

        // Setelah grafik muncul, Anda bisa kembalikan ke query database sebenarnya:
        /*
        $data = User::select(
                DB::raw('COALESCE(COUNT(leave_requests.id), 0) as total_izin'),
                'users.department'
            )
            ->leftJoin('leave_requests', 'leave_requests.user_id', '=', 'users.id')
            ->whereNotNull('users.department') // Hanya departemen yang tidak null
            ->groupBy('users.department')
            ->get();

        $labels = $data->pluck('department')->toArray();
        $totalIzin = $data->pluck('total_izin')->toArray();

        // Fallback jika tidak ada data dari DB (misal semua department null)
        if (empty($labels) || array_sum($totalIzin) === 0) { // Jika semua 0, pakai dummy
            $labels = ['Departemen A', 'Departemen B'];
            $totalIzin = [1, 1]; // Pastikan ada nilai agar grafik terlihat
        }
        */

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Permintaan Izin',
                    'data' => $totalIzin,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(201, 203, 207, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(201, 203, 207, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'aspectRatio' => 1, // <<< SET KE 1 UNTUK UKURAN SERAGAM (PERSEGI)
        ];
    }
}
