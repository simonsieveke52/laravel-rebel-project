<?php

namespace App\Mail;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuoteRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->products_requested = $request['products_requested']->pluck('name')->toArray();
        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->phone = $request['phone'];
        $this->address_1 = $request['address_1'];
        $this->address_2 = $request['address_2'] ?? '';
        $this->city = $request['city'];
        $this->state = $request['state'];
        $this->zip = $request['zip'];
        $this->quote_message = $request['message'] ?? '';
        $this->link = $request['quote_link'] ?? '';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
        ->view('emails.admin.quote-request', [
            'products_requested'    => $this->products_requested,
            'name'                  => $this->name,
            'email'                 => $this->email,
            'phone'                 => $this->phone,
            'address_1'             => $this->address_1,
            'address_2'             => $this->address_2,
            'city'                  => $this->city,
            'state'                 => $this->state,
            'zip'                   => $this->zip,
            'quote_message'         => $this->quote_message,
            'link'                  => $this->link,
        ]);
    }
}
