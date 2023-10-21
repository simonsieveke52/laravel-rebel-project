<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
use App\Mail\AmazonOrderMailable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AmazonOrderFailedNotification extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @param Order      $order    
     * @param \Exception $exception
     * @return void
     */
    public function __construct(Order $order, \Exception $exception)
    {
        $this->order = $order;
        $this->exception = $exception;
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
        $emails = config('mail.bcc');

        return (new AmazonOrderMailable($this->order, $this->exception))
            ->to(array_shift($emails))
            ->bcc($emails)
            ->subject("Amazon MCF Failed On ". config('app.name') ." - Order #{$this->order->id}");
    }
}
