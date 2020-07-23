<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DetailTransactionNotification extends Notification
{
    use Queueable;

    protected $detail;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($detail, $user)
    {
        $this->detail = $detail;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
         return ['database', 'broadcast'];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'sender_photo' => $this->user->photo,
            'detail' => $this->detail
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'detail' => $this->detail
        ]);
    }
}
