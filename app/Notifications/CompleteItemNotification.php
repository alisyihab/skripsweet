<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CompleteItemNotification extends Notification
{
    use Queueable;

    protected $transaction;
    protected $user;


    public function __construct($transaction, $user)
    {
        $this->transaction = $transaction;
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
            'completeItem' => $this->transaction,
            'note' => 'Laundry anda telah selesai, terimakasih telah menggunakan jasa kami. jangan lupa beri penilaian ya agar kami bisa meninggkatkan service kepuasan anda :)'
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
            'completeItem' => $this->transaction,
        ]);
    }
}
