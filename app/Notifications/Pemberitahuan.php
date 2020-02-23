<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Pemberitahuan extends Notification
{
    use Queueable;
    public $message;
    public $type;
    public $penelitian_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $type, $penelitian_id)
    {
        $this->message = $message;
        $this->type = $type;
        $this->penelitian_id = $penelitian_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }


    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'penelitian_id' => $this->penelitian_id,
        ];
    }
}
