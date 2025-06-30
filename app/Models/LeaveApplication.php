<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $table = 'leave_applications';

    protected $fillable = [
        'user_id',
        'reason',
        'start_date',
        'end_date'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
