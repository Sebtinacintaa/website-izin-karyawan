<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Baris ini tidak perlu jika Anda selalu menggunakan `php artisan migrate:fresh`
        // karena `migrate:fresh` akan menghapus semua tabel termasuk `users` dan datanya.
        // User::query()->delete(); 

        // Buat user untuk peran 'karyawan'
        // Lengkapi dengan semua kolom yang NOT NULL di tabel users Anda (sesuai migrasi)
        User::firstOrCreate(
            ['email' => 'karyawan@example.com'], // Kondisi untuk mencari user
            [
                'name' => 'User Karyawan Biasa',
                'password' => Hash::make('password'), 
                'department' => 'Marketing', // Contoh
                'phone' => '08111111111',     // Contoh
                'nip' => 'KRY001',            // Contoh
                'position' => 'Staff',        // Contoh
                'first_name' => 'Karyawan',   // Contoh
                'last_name' => 'Biasa',       // Contoh
                'tanggal_lahir' => '1995-01-01', // Contoh
                'email_verified_at' => now(),
            ]
        );

        // Buat user untuk peran 'admin'
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Sistem',
                'password' => Hash::make('admin123'), 
                'department' => 'IT',
                'phone' => '08222222222',
                'nip' => 'ADM001',
                'position' => 'Administrator',
                'first_name' => 'Admin',
                'last_name' => 'Sistem',
                'tanggal_lahir' => '1980-01-01',
                'email_verified_at' => now(),
            ]
        );

        // Buat user untuk peran 'atasan'
        User::firstOrCreate(
            ['email' => 'atasan@example.com'],
            [
                'name' => 'Bapak Supervisor',
                'password' => Hash::make('password'), 
                'department' => 'Operations',
                'phone' => '08333333333',
                'nip' => 'SUP001',
                'position' => 'Supervisor',
                'first_name' => 'Supervisor',
                'last_name' => 'Bapak',
                'tanggal_lahir' => '1985-01-01',
                'email_verified_at' => now(),
            ]
        );

        // User tambahan jika dibutuhkan, tapi tidak akan diberi peran khusus di RolesAndPermissionsSeeder
        User::firstOrCreate(
            ['email' => 'abcintaah19@gmail.com'],
            [
                'name' => 'Sebtinacintaa',
                'password' => Hash::make('12345678'),
                'department' => 'Finance',
                'phone' => '08444444444',
                'nip' => 'STF002',
                'position' => 'Staff',
                'first_name' => 'Sebtina',
                'last_name' => 'Cintaa',
                'tanggal_lahir' => '1998-01-01',
                'email_verified_at' => now(),
            ]
        );
    }
}