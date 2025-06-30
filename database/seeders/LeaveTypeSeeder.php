<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType; // Pastikan model LeaveType Anda di-import

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama sebelum menambahkan yang baru (jika tidak pakai migrate:fresh)
        // LeaveType::query()->delete(); 

        // Buat beberapa jenis izin
        LeaveType::firstOrCreate(
            ['name' => 'Cuti Tahunan'],
            ['days_allotted' => 12]
        );

        LeaveType::firstOrCreate(
            ['name' => 'Izin Sakit'],
            ['days_allotted' => null] // Tidak ada alokasi hari spesifik
        );

        LeaveType::firstOrCreate(
            ['name' => 'Izin Pribadi'],
            ['days_allotted' => 3]
        );

        LeaveType::firstOrCreate(
            ['name' => 'Cuti Melahirkan'],
            ['days_allotted' => 90]
        );

        $this->command->info('Jenis-jenis izin dasar telah ditambahkan.');
    }
}
