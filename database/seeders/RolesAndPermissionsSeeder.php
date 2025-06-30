<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Pastikan ini ada dan merujuk ke model User Anda

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        // Ini memastikan setiap perubahan pada roles/permissions akan segera terlihat
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- BUAT PERMISSIONS ---
        // Definisikan semua izin yang mungkin ada dalam aplikasi Anda
        // Izin umum untuk Admin
        Permission::firstOrCreate(['name' => 'manage users']); // CRUD user
        Permission::firstOrCreate(['name' => 'manage roles']); // CRUD roles
        Permission::firstOrCreate(['name' => 'manage permissions']); // CRUD permissions
        Permission::firstOrCreate(['name' => 'view all reports']); // Melihat semua laporan sistem
        Permission::firstOrCreate(['name' => 'manage system settings']); // Mengatur pengaturan sistem

        // Izin khusus untuk Atasan (Supervisor/Manager)
        Permission::firstOrCreate(['name' => 'view team reports']); // Melihat laporan tim sendiri
        Permission::firstOrCreate(['name' => 'approve leave requests']); // Menyetujui/menolak permintaan izin
        Permission::firstOrCreate(['name' => 'view all leave requests']); // Melihat semua permintaan izin

        // Izin untuk Karyawan (User biasa)
        Permission::firstOrCreate(['name' => 'create leave requests']); // Membuat permintaan izin
        Permission::firstOrCreate(['name' => 'view own leave requests']); // Melihat permintaan izinnya sendiri

        // --- BUAT ROLES DAN BERIKAN PERMISSIONS ---
        // Gunakan firstOrCreate agar tidak membuat role duplikat jika sudah ada
        
        // Role Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // Berikan semua permission yang ada kepada admin
        $adminRole->givePermissionTo(Permission::all());
        $this->command->info('Role "admin" dibuat dan diberi semua permission.');


        // Role Atasan (Supervisor/Manager)
        $atasanRole = Role::firstOrCreate(['name' => 'atasan']);
        $atasanRole->givePermissionTo([
            'view team reports',
            'approve leave requests',
            'view all leave requests', 
        ]);
        $this->command->info('Role "atasan" dibuat dan diberi permission spesifik.');


        // Role Karyawan (User Biasa)
        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan']);
        $karyawanRole->givePermissionTo([
            'create leave requests',
            'view own leave requests',
        ]);
        $this->command->info('Role "karyawan" dibuat dan diberi permission spesifik.');


        // --- TETAPKAN ROLES KE USER YANG ADA ---
        // Cari user berdasarkan email dan berikan peran yang sesuai
        
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
            $this->command->info('User ' . $adminUser->email . ' diberi peran "admin".');
        } else {
            $this->command->warn('User admin@example.com tidak ditemukan. Pastikan UserSeeder dijalankan terlebih dahulu atau buat user ini secara manual.');
        }

        $atasanUser = User::where('email', 'atasan@example.com')->first();
        if ($atasanUser) {
            $atasanUser->assignRole('atasan');
            $this->command->info('User ' . $atasanUser->email . ' diberi peran "atasan".');
        } else {
            $this->command->warn('User atasan@example.com tidak ditemukan. Pastikan UserSeeder dijalankan terlebih dahulu atau buat user ini secara manual.');
        }

        $karyawanUser = User::where('email', 'karyawan@example.com')->first();
        if ($karyawanUser) {
            $karyawanUser->assignRole('karyawan');
            $this->command->info('User ' . $karyawanUser->email . ' diberi peran "karyawan".');
        } else {
            $this->command->warn('User karyawan@example.com tidak ditemukan. Pastikan UserSeeder dijalankan terlebih dahulu atau buat user ini secara manual.');
        }

        // Contoh user tambahan yang mungkin tidak diberi peran khusus di sini, tapi ada di DB
        $sebtinaUser = User::where('email', 'abcintaah19@gmail.com')->first();
        if ($sebtinaUser && !$sebtinaUser->hasAnyRole(['admin', 'atasan', 'karyawan'])) {
            // Jika user ini tidak punya peran yang diatur di atas, kita bisa kasih peran default 'karyawan'
            $sebtinaUser->assignRole('karyawan'); 
            $this->command->info('User ' . $sebtinaUser->email . ' diberi peran "karyawan" (default).');
        }
    }
}