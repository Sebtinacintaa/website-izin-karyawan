<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue; // <<< TAMBAH INI
use Illuminate\Notifications\Messages\MailMessage; // <<< TAMBAH INI

class CutiNotification extends Notification implements ShouldQueue // <<< TAMBAH implements ShouldQueue
{
    use Queueable;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array // Ubah $notifiable ke object $notifiable
    {
        return ['database']; // <<< PERBAIKAN DI SINI: HANYA 'database', HAPUS 'mail'
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage // Ubah $notifiable ke object $notifiable
    {
        return (new MailMessage)
                    ->subject($this->data['title'] ?? 'Notifikasi Cuti') // Menggunakan title dari data
                    ->greeting('Halo,')
                    ->line($this->data['message'] ?? 'Notifikasi terkait pengajuan cuti Anda.') // Menggunakan message dari data
                    ->action('Lihat Riwayat Cuti', route('riwayat.pengajuan')) // Contoh link ke riwayat cuti
                    ->line('Terima kasih.');
    }

    public function toDatabase(object $notifiable): array // Ubah $notifiable ke object $notifiable
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'url' => $this->data['url'] ?? route('riwayat.pengajuan'), // Menggunakan url dari data, atau default
            'created_at' => now(), // Menambahkan waktu pembuatan
        ];
    }
}
