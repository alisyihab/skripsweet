<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentNotification extends Notification
{
    use Queueable;
    protected $payment;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param $payment
     * @param $user
     */
    public function __construct($payment, $user)
    {
        $this->payment = $payment;
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
            'payment' => $this->payment,
            'note' => 'Pembayaran telah dilakukan silahkan cek'
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
            'payment' => $this->payment
        ]);
    }
}
