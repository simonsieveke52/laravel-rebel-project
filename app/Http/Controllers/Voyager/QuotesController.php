<?php

namespace App\Http\Controllers\Voyager;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Mail\{BulkOrderMailable};
use App\Mail\QuoteRequest;
use App\Order;
use App\Product;
use App\Quote;
use App\QuoteProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class QuotesController extends Controller
{
    public function show($quote_id)
    {
        return view('vendor.voyager.quotes.edit', ['quote' => $quote_id]);
    }

    public function get($quote)
    {
        $quote = Quote::where('id', $quote)->with('products.images','emails')->first();
        $quote->products->each(fn($p) => $p->append('shippingCost'));

        return response($quote, 200);
    }

    public function new()
    {
        return view('vendor.voyager.quotes.new');
    }

    public function store(Request $request)
    {
        $mailMeta =
           [
              'status'   => null,
              'messages' => [],
              'time'     => date('l, F jS, Y h:i:s A'),
           ];

        $validatedData =
           $request->validate([
              'name'      => 'required',
              'email'     => 'required',
              'phone'     => 'required',
              'address_1' => 'nullable',
              'address_2' => 'nullable',
              'city'      => 'nullable',
              'state'     => 'nullable',
              'zip'       => 'required',
              'message'   => 'nullable|max:191',
              'products'  => 'required',
           ]);
        $products =
           new Collection();
        foreach ($validatedData['products'] as $product) {

            $check =
               Product::find($product['id']);
            if ($check) {
                $products->push($product);
            }
        }

        $quote =
           Quote::create([
              'name'           => $validatedData['name'],
              'email'          => $validatedData['email'],
              'phone'          => $validatedData['phone'],
              'address_1' => $validatedData['address_1'] ?? '',
              'address_2' => $validatedData['address_2'] ?? '',
              'city'           => $validatedData['city'] ?? '',
              'state'          => $validatedData['state'] ?? '',
              'zip'            => $validatedData['zip'],
              'message'        => $validatedData['message'] ?? null,
              'status'         => 'New',
           ]);

        foreach ($products as $product) {
            QuoteProduct::create([
               'product_id'      => $product['id'],
               'quote_id'        => $quote['id'],
               'quantity'        => $product['quantity'],
               'price'           => $product['price'],
               'discount_value'  => 0,
               'discount_type'   => '%',
               'discount_amount' => 0,
            ]);
        }

        $data_for_mailer =
           $validatedData;
        $data_for_mailer['products_requested'] =
           $products;
        $data_for_mailer['quote_link'] =
           route('voyager.quotes.edit', ['id' => $quote->id]);
        if ($mailMeta['status'] != 'invalid') {
            try {
                Mail::to(config('mail.from.address'))->send(new QuoteRequest($data_for_mailer));
            } catch (\Exception $e) {
                logger('Quote Request error');
                logger($e);
                return back()->with('error', 'We\'re sorry. There was an error. Please try again later.');
            }
        }

        return redirect()->route('home')->with('message',
           'Thank you for submitting your request! A sales representative will contact you in 1-2 business days. For immediate assistance email us at ')
                         ->with('email', 'support@rebelsmuggling.com');

    }

    public function update(Request $request, $quote_id)
    {
        $validatedData =
           $request->validate([
              'data.email'         => 'required|email',
              'data.name'          => 'required|min:3',
              'data.phone'         => 'required',
              'data.address_1'     => 'nullable',
              'data.address_2'     => 'nullable',
              'data.city'          => 'nullable',
              'data.state'         => 'nullable',
              'data.zip'           => 'required|min:5',
              'data.message'       => 'nullable',
              'data.products'      => 'required',
              'data.shipping_cost' => 'nullable|numeric'
           ]);

        $data = [
            'name'           => $validatedData['data']['name'],
            'email'          => $validatedData['data']['email'],
            'phone'          => preg_replace('/[^0-9]/', '', trim($validatedData['data']['phone'])),
            'address_1' => $validatedData['data']['address_1'] ?? '',
            'address_2' => $validatedData['data']['address_2'] ?? '',
            'city'           => $validatedData['data']['city'] ?? '',
            'state'          => $validatedData['data']['state'] ?? '',
            'zip'            => preg_replace('/[^0-9\-]/', '', trim($validatedData['data']['zip'])),
            'status'         => 'Sent',
        ];

        if (isset($validatedData['data']['shipping_cost']) && $validatedData['data']['shipping_cost'] != 'null') {
            $data['shipping_cost'] = $validatedData['data']['shipping_cost'];
        }

        if ((int)$quote_id === 0) {
            $quote = Quote::create($data);
        } else {
            $quote = Quote::find($quote_id);
            if ($quote->status === 'Completed') {
                return redirect()->back()->with('message', 'This order has already been completed.');
            }
            $data['shipping_cost'] = $validatedData['data']['shipping_cost'];

            $quote->update($data);
            QuoteProduct::where('quote_id', $quote_id)->delete();
        }

        $customer =
           Customer::where('email', $validatedData['data']['email'])->first();

        $order =
            Order::create([
                'name'                => $validatedData['data']['name'],
                'email'               => $validatedData['data']['email'],
                'phone'               => preg_replace('/[^0-9]/', '', trim($validatedData['data']['phone'])),
                'cc_number'           => null,
                'cc_name'             => null,
                'cc_expiration'       => null,
                'cc_expiration_month' => null,
                'cc_expiration_year'  => null,
                'card_type'           => null,
                'cc_cvv'              => null,
                'customer_id'         => $customer !== null
                ? $customer->id
                : null,
                'quote_id'            => $quote->id,
                'order_status_id'     => 1,
                'shipping_cost'       => $quote->shipping_cost ?? 0
            ]);

        $subtotal =
           0;
        foreach ($validatedData['data']['products'] as $product) {
            $product_requested =
               Product::find($product['id']);
            if ($product_requested) {
                QuoteProduct::updateOrCreate([
                   'product_id'      => $product_requested->id,
                   'quote_id'        => $quote->id,
                   'price'           => $product_requested->price,
                   'quantity'        => $product['pivot']['quantity'],
                   'free_shipping'   => $product['pivot']['free_shipping'],
                   'discount_amount' => $product['pivot']['discount_amount'],
                   'discount_value'  => $product['pivot']['discount_value'],
                   'discount_type'   => $product['pivot']['discount_type'],
                ]);
                $order->products()->attach($product_requested, [
                   'quantity'        => $product['pivot']['quantity'],
                   'price'           => $product_requested->price,
                   'free_shipping'   => $product['pivot']['free_shipping'],
                   'discount'        => $product['pivot']['discount_amount'],
                   'discount_amount' => $product['pivot']['discount_amount'],
                   'discount_value'  => $product['pivot']['discount_value'],
                   'discount_type'   => $product['pivot']['discount_type'],
                ]);
                $subtotal += ($product_requested->price * $product['pivot']['quantity']);
            }
        }
        $order->load('products');
        $order->applyQuoteDiscount();
        $order->update([
           'subtotal' => round($subtotal, 2),
        ]);
        $order->refresh();
        Mail::to($order->email)->send(new BulkOrderMailable($order, $quote));
        return response('Quote Sent to Customer!', 200);
    }
}
