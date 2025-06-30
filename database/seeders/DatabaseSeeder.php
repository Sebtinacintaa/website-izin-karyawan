<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lain yang Anda butuhkan di sini
        $this->call([
            UserSeeder::class, // Jika Anda memiliki UserSeeder yang membuat user
            RolesAndPermissionsSeeder::class, // <<< INI YANG HARUS DITAMBAHKAN
            LeaveTypeSeeder::class,
        ]);
    }
}