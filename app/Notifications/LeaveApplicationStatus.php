<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\LeaveApplication;

class LeaveApplicationStatus extends Notification
{
    use Queueable;

    protected $leaveApplication;
    protected $messageTitle;
    protected $messageBody;

    public function __construct(LeaveApplication $leaveApplication, $title, $message)
    {
        $this->leaveApplication = $leaveApplication;
        $this->messageTitle = $title;
        $this->messageBody = $message;
    }

    // Kanal notifikasi, kita pakai database saja
    public function via($notifiable)
    {
        return ['database'];
    }

    // Format data yang disimpan ke database
    public function toDatabase($notifiable)
    {
        return [
            'leave_application_id' => $this->leaveApplication->id,
            'title' => $this->messageTitle,
            'message' => $this->messageBody,
        ];
    }
}
