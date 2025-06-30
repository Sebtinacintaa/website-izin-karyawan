<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use App\Models\User; // <<< UBAH DARI App\Models\Admin MENJADI App\Models\User
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika belum ada

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        // 1. Cari user di model App\Models\User
        $user = User::where('email', $data['email'])->first();

        // 2. Verifikasi password DAN cek apakah user memiliki peran yang diizinkan untuk login ke panel admin
        if (
            ! $user || // User tidak ditemukan
            ! Hash::check($data['password'], $user->password) || // Password tidak cocok
            ! ($user->hasRole('admin') || $user->hasRole('atasan')) // TIDAK memiliki peran admin atau atasan
        ) {
            throw ValidationException::withMessages([
                'email' => __('filament-panels::pages/auth/login.messages.invalid_credentials'),
            ]);
        }

        // 3. Login user
        Auth::login($user, $data['remember'] ?? false); // Gunakan Auth::login() dengan user dari model App\Models\User

        // 4. Redirect ke halaman yang benar
        // Filament biasanya menangani redirect ini secara otomatis setelah Auth::login()
        // Jadi return app(LoginResponse::class) ini tetap oke.
        return app(LoginResponse::class);
    }
}
