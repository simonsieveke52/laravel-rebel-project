<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            $this->order->markAsMailed();
        } catch (\Exception $e) {
            
        }

        $data = [
            'order'            => $this->order,
            'products'         => $this->order->products,
            'customer'         => $this->order->customer,
            'billing_address'  => $this->order->billing_address,
            'shipping_address' => $this->order->shipping_address,
            'status'           => $this->order->orderStatus,
            'payment'          => $this->order->paymentMethod,
            'discount'         => $this->order->discount
        ];

        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Your Rebel Smuggling Order #' . $this->order->id)
            ->view('emails.customer.sendOrderDetailsToCustomer', $data)
            ->text('emails.customer.orderDetailsPlain', $data);
    }
}

