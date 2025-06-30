<?php

namespace App\Notifications; // Pastikan namespace ini benar

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LeaveRequest; // Pastikan ini di-import
use App\Filament\Resources\LeaveRequestResource; // Pastikan ini di-import untuk URL Filament
use Carbon\Carbon; // Pastikan ini di-import untuk format tanggal di toMail()

class LeaveRequestSubmittedNotification extends Notification implements ShouldQueue 
{
    use Queueable;

    protected $leaveRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(LeaveRequest $leaveRequest)
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
        // <<< PERBAIKAN DI SINI >>>
        return ['database']; // HANYA kirim ke database, hapus 'mail'
        // <<< AKHIR PERBAIKAN >>>
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pengajuan Cuti Baru: ' . ($this->leaveRequest->user->name ?? 'N/A'))
                    ->greeting('Halo,')
                    ->line('Permohonan cuti baru telah diajukan dan sedang menunggu persetujuan Anda.')
                    ->line('Diajukan oleh: **' . ($this->leaveRequest->user->name ?? 'N/A') . '**')
                    ->line('Jenis Izin: **' . ($this->leaveRequest->leave_type ?? 'N/A') . '**')
                    ->line('Tanggal: **' . Carbon::parse($this->leaveRequest->start_date)->format('d M Y') . ' - ' . Carbon::parse($this->leaveRequest->end_date)->format('d M Y') . '**')
                    ->action('Tinjau Permintaan Izin', LeaveRequestResource::getUrl('edit', ['record' => $this->leaveRequest]))
                    ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification (for database storage).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Cuti Baru', 
            'message' => 'Permohonan cuti dari ' . ($this->leaveRequest->user->name ?? 'N/A') . ' sedang menunggu persetujuan.', 
            'url' => LeaveRequestResource::getUrl('edit', ['record' => $this->leaveRequest]), 

            'leave_request_id' => $this->leaveRequest->id,
            'user_id' => $this->leaveRequest->user_id,
            'user_name' => $this->leaveRequest->user->name ?? 'N/A',
            'leave_type' => $this->leaveRequest->leave_type,
            'start_date' => $this->leaveRequest->start_date,
            'end_date' => $this->leaveRequest->end_date,
        ];
    }
}
