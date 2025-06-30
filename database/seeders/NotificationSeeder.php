<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => 'manual',
            'notifiable_type' => User::class,
            'notifiable_id' => 1, // sesuaikan ID user yang ada
            'data' => json_encode([
                'title' => 'Tes Notifikasi',
                'message' => 'Ini notifikasi seeder pertama.'
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
