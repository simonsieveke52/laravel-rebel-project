<?php

namespace App\Mail;

use App\Order;
use App\Quote;
use App\QuoteEmail;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BulkOrderMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;
    protected $quote;

    /**
     * Create a new message instance.
     *
     * @param  Order  $order
     * @param  Quote  $quote
     */
    public function __construct(Order $order, Quote $quote)
    {
        $this->order = $order;
        $this->quote = $quote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;

        $time = now()->addDays(14);
        
        $link = URL::temporarySignedRoute('quoterequest.convertOrder', $time, [
            'order' => $order->id
        ]);

        QuoteEmail::create([
           'quote_id' => $this->quote->id,
           'url' => $link,
           'expires_at' => $time,
           'sent_at' => now(),
           'sent_to' => $this->order->email
        ]);
        
        return $this->subject('Great news! Your quote from Rebel Smuggling is here!')
            ->view('emails.customer.bulkOrder', compact('link', 'order'));
    }
}
