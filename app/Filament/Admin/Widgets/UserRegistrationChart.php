<?php
// Pastikan tidak ada spasi atau karakter di atas <?php

namespace App\Filament\Admin\Widgets; // Pastikan namespace ini benar

use Filament\Widgets\ChartWidget;
use App\Models\User; // Pastikan model User di-import
use Carbon\Carbon; // Pastikan Carbon di-import

class UserRegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'Pendaftaran Pengguna Baru (6 Bulan Terakhir)'; // Judul Widget
    protected static ?int $sort = 2; // Urutan widget
    protected static string $color = 'info'; // Warna widget

    protected function getType(): string
    {
        return 'line'; // Tipe grafik
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Loop untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y'); // Contoh: Jan 2025, Feb 2025

            $count = User::whereMonth('created_at', $month->month)
                         ->whereYear('created_at', $month->year)
                         ->count();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pengguna Baru',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)', // Warna latar belakang area grafik
                    'borderColor' => 'rgba(54, 162, 235, 1)', // Warna garis grafik
                    'borderWidth' => 2,
                    'fill' => true, // Mengisi area di bawah garis
                    'tension' => 0.3, // Kehalusan garis
                ],
            ],
        ];
    }

    // Mengatur opsi grafik (opsional)
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1, // Agar skala Y menunjukkan angka bulat
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false, // Sembunyikan legend
                ],
            ],
        ];
    }
}
