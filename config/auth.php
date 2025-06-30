<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Default guard dan password reset setting.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Guard digunakan untuk mengatur sesi login.
    | Kita hanya menggunakan 'web' guard untuk semua jenis user (admin, atasan, karyawan)
    | dan membedakannya melalui roles Spatie.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        // Konfigurasi 'admin' guard yang terpisah DIHAPUS di sini.
        // Semua user akan diautentikasi melalui 'web' guard dan model App\Models\User.
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Provider menentukan bagaimana user diambil dari database.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Ini adalah model User tunggal Anda
        ],
        // Konfigurasi 'admins' provider yang terpisah DIHAPUS di sini.
        // Semua user akan diambil dari provider 'users'.
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset
    |--------------------------------------------------------------------------
    |
    | Konfigurasi reset password untuk masing-masing provider.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        // Konfigurasi reset password 'admins' yang terpisah DIHAPUS di sini.
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Waktu timeout sebelum diminta konfirmasi ulang password (dalam detik).
    |
    */

    'password_timeout' => 10800,

];
