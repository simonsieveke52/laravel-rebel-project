<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AmazonOrderMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, \Exception $exception)
    {
        $this->order = $order;
        $this->exception = $exception;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notifications.failed-report', [
            'order' => $this->order,
            'exception' => $this->exception
        ]);
    }
}
