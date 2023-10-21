<?php

namespace App\Notifications;

use App\Order;
use App\Mail\OrderMailable;
use Illuminate\Bus\Queueable;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class OrderNotification extends Notification
{
    use Queueable;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (
            new OrderMailable($this->order)
        )
        ->to($this->order->email)
        ->subject("Your order from Rebelsmuggling.com - Order #{$this->order->id}");
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $total = '$' . number_format($this->order->total, 2);

        return (new SlackMessage)
            ->to('#rebel-client-launch')
            ->content("Order Completed: #{$this->order->id} by {$this->order->name} for {$total} - {$this->order->order_source}");
    }
}
