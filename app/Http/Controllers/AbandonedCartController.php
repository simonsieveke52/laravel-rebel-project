<?php

namespace App\Http\Controllers;

use App\Order;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Jobs\ReportOrderFacebookJob;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\MailchimpRepository;
use App\Repositories\Contracts\CartRepositoryContract;

class AbandonedCartController extends Controller
{

    public function __construct(OrderRepository $orderRepository, CartRepositoryContract $cartRepository) {
        $this->cartRepository  = $cartRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Create new order for a lead starting the checkout process
     *
     * @param  Request $request
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|max:191',
            'last_name'  => 'required|max:191',
            'email'      => 'required|email:rfc|max:191',
            'origin_id'  => 'required|exists:order_origins,id',
        ]);

        $cartItems = $this->cartRepository->getCartItemsTransformed();

        if ($cartItems->isEmpty()) {
            return redirect()->route('guest.checkout.index');
        }

        $order = Order::where('id', session('order'))->first();

        if ($order instanceof Order) {
            $data['id'] = $order->id;
        }

        $order = $this->orderRepository->createOrder($data);

        if(($discount = $this->cartRepository->getDiscount()) instanceof Discount) {
            $order->discount_id = $discount->id;
            $order->save();
        }

        $this->orderRepository->buildOrderDetails($order, $cartItems);

        $time = config('mailchimp.abandoned_cart.url_time');

        $link = URL::temporarySignedRoute('abandoned-cart.convertOrder', $time, [
            'order' => $order->id
        ]);

        $link .= config('mailchimp.abandoned_cart.tracking_params');

        $order->update(['checkout_url' => $link]);

        try {
            $mailchimp = new MailchimpRepository();
            $mailchimp->syncAbandonedCart($order);
        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        session(['order' => Arr::wrap($order->id)]);

        try {
            ReportOrderFacebookJob::dispatch($order, 'initiateCheckout')->onQueue('high');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        return response()->json(['order_id' => $order->id]);
    }

    /**
     * Method for creating an abandoned cart on-demand
     *
     * Takes an order id and pushes that order to the session
     *
     * redirects the customer to checkout or to the homepage if the order is no longer valid
     *
     * @param $order
     * @return \Illuminate\Http\Response
     */
    public function convertOrder($order)
    {
        try {

            $order = Order::where('id', $order)->firstOrFail();

            if (! Str::contains($order->checkout_url, request()->input('signature'))) {
                return redirect()->route('home')
                    ->with('message', 'We\'re sorry, but we can\'t proceed with that order. If you believe an error has been made, please contact support.');
            }

            if ($order->confirmed) {
                return redirect()->route('home')
                    ->with('message', 'Your order has already been completed. If you believe an error has been made, please contact support.');
            }

            $this->cartRepository->clear();

            session(['order' => Arr::wrap($order->id)]);

            // Add order items to cart
            $order->products->each(function($prod) {
                $options = isset($prod->pivot) ? $prod->pivot->toArray() : [];
                $this->cartRepository->addToCart($prod, $prod->pivot->quantity, $options);
            });

            if ($order->appliedDiscount != null) {
                $this->cartRepository->setDiscount($order->appliedDiscount->id);
            }

            if ((int) $order->has_free_shipping === 1) {
                $this->cartRepository->setFreeShipping();
            }

            $order->update(['mc_cid' => request()->input('mc_cid', null)]);

            return redirect()->route('guest.checkout.index');

        } catch (Throwable $e) {
            return redirect()->route('home')
                             ->with('message', 'We\'re sorry, but we can\'t proceed with that order. If you believe an error has been made, please contact support.');
        }
    }

}
