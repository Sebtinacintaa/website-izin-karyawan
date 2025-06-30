<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leaveRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct($leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; 
    }

    /**
     * Menyimpan notifikasi ke database
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Cuti Disetujui',
            'message' => 'Permohonan cuti Anda telah disetujui oleh atasan.',
            'url' => route('riwayat.pengajuan'),
            'created_at' => now() ->toDateTimeString(),
            'read' => false,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Cuti Disetujui')
                    ->greeting('Halo!')
                    ->line('Permohonan cuti Anda telah disetujui.')
                    ->action('Lihat Riwayat', url('/riwayat'))
                    ->line('Terima kasih telah menggunakan sistem cuti kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Cuti Disetujui',
            'message' => 'Permohonan cuti Anda telah disetujui.',
            'url' => route('riwayat.pengajuan'),
            'created_at' => now(),
            'read' => false,
        ];
    }

    
}
