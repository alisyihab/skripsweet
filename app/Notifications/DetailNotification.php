<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class DetailNotification extends Notification
{
    use Queueable;

    protected $detailTransaction;
    protected $user;


    public function __construct($detailTransaction, $user)
    {
        $this->detailTransaction = $detailTransaction;
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

    public function toDatabase($notifiable)
    {
        return [
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'detailTransaction' => $this->detailTransaction,
            'note' => 'Laundry anda sedang di proses, silahkan melakukan pembayaran'
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_id' => $this->user->id,
            'sender_name' => $this->user->name,
            'detailTrasaction' => $this->detailTransaction,
        ]);
    }
}
