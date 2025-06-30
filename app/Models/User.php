<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\LeaveRequest;
use Spatie\Permission\Traits\HasRoles; // Pastikan ini ada
use Illuminate\Support\Facades\Auth; // Pastikan ini ada

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // Pastikan HasRoles ada di sini

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'phone',
        'nip',
        'position',
        'first_name',
        'last_name',
        'tanggal_lahir',
        'avatar',
        // 'role', // Pastikan ini DIKOMENTARI atau DIHAPUS. Kita menggunakan Spatie Roles.
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Relasi Leave Request
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Digunakan oleh Filament untuk otorisasi akses panel.
     * Sekarang menggunakan Spatie Roles.
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Pengguna harus terotentikasi DAN memiliki setidaknya satu peran yang diizinkan untuk mengakses panel Filament.
        // Dalam kasus ini, kita izinkan 'admin' atau 'atasan' untuk mengakses panel.
        return Auth::check() && ($this->hasRole('admin') || $this->hasRole('atasan'));
    }
}
