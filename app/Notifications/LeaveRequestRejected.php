<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LeaveRequestRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leaveRequest;

    public function __construct($leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pengajuan Cuti Ditolak',
            'message' => 'Permohonan cuti Anda ditolak oleh atasan.',
            'url' => route('riwayat.pengajuan'),
            'created_at' => now(),
            'read' => false,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Cuti Ditolak',
            'message' => 'Permohonan cuti Anda ditolak oleh atasan.',
            'url' => route('riwayat.pengajuan'),
            'created_at' => now(),
            'read' => false,
        ];
    }
}