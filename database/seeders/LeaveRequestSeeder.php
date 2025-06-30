<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('leave_requests')->insert([
            'full_name' => 'Sebtina Cinta Anugrahini',
            'department' => 'Datin',
            'phone_number' => '081584229881',
            'nip' => '2210512016',
            'date' => '2025-06-01',
            'reason' => 'Cuti tahunan',
            'document' => 'dummy_file.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}


