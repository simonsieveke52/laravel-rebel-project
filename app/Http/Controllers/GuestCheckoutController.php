<?php

namespace App\Http\Controllers;

use App\Mail\OrderMailable;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\OrderCreateEvent;
use App\Jobs\ReportOrderFacebookJob;
use Illuminate\Support\Facades\Mail;
use App\Repositories\CheckoutRepository;
use App\Repositories\MailchimpRepository;
use App\Http\Requests\GuestCheckoutRequest;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\{ Address, Discount, Order, OrderOrigin, Shipping };

class GuestCheckoutController extends CheckoutBaseController
{
    /**
     * show checkout page
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $order = Order::where('id', session('order'))->first();

        if (!$order || !$order->quote) {
            $this->cartRepository->checkItemsStock();
        }

        if ($this->cartRepository->getCartItems()->isEmpty()) {
            return redirect()->route('home')->with('message', 'Your cart is empty.');
        }

        if ($order !== null) {
            session([
                'first_name' => $order->first_name,
                'last_name'  => $order->last_name,
                'email'      => $order->email,
            ]);
        }

        return view('front.guest.checkout', [
            'orderOrigins'  => OrderOrigin::all(),
            'free_shipping' => $order && ((int) $order->has_free_shipping === 1),
            'session'       => [
                'first_name'                 => session('first_name', ''),
                'last_name'                  => session('last_name', ''),
                'phone'                      => session('phone', ''),
                'email'                      => session('email', ''),
                'origin_id'                  => session('origin_id', ''),
                'billing_address_1'          => session('billing_address_1', ''),
                'billing_address_2'          => session('billing_address_2', ''),
                'billing_address_zipcode'    => session('billing_address_zipcode', ''),
                'billing_address_state_id'   => session('billing_address_state_id', ''),
                'billing_address_city'       => session('billing_address_city', ''),
                'shipping_address_1'         => session('shipping_address_1', ''),
                'shipping_address_2'         => session('shipping_address_2', ''),
                'shipping_address_zipcode'   => session('shipping_address_zipcode', ''),
                'shipping_address_state_id'  => session('shipping_address_state_id', ''),
                'shipping_address_city'      => session('shipping_address_city', ''),
                'shipping_address_different' => session('shipping_address_different', false),
                'subscriber'                 => session('subscriber', null),
            ]
        ]);
    }

    /**
     * Create new order then redirect user to checkout.execute
     * Route where payment are processed and confirmed
     *
     * @param  GuestCheckoutRequest $request
     * @return RedirectResponse
     */
    public function store(GuestCheckoutRequest $request)
    {
        // get all request attributes
        $data = $request->except('_token');

        $order = Order::where('id', session('order'))->first();

        if ($order instanceof Order) {
            $data['id'] = $order->id;
        }

        if (! $order || ! $order->quote) {
            $this->cartRepository->checkItemsStock();
        }

        $cartItems = $this->cartRepository->getCartItemsTransformed();

        if ($cartItems->isEmpty()) {
            return redirect()->route('guest.checkout.index');
        }

        // Create or updated previous order.
        $order = $this->orderRepository->createOrder($data);

        $this->orderRepository->buildOrderDetails($order, $cartItems);

        // create new billing address
        $address = $this->addressRepository->createBillingAddress($data);

        // associate address to current order
        $order->addresses()->save($address);

        // Customer has shipping address diffrent than billing address
        // need to create shipping address and create order relation
        if ($request->has('shipping_address_different') && $request->shipping_address_different == 'true') {
            $address = $this->addressRepository->createShippingAddress($data);
            $order->addresses()->save($address);
        }

        if(($discount = $this->cartRepository->getDiscount()) instanceof Discount) {
            if ($discount->newsletter_signup) {
                //dont execute checkout if the promo is invalid
                if (!$discount->isValid()) {
                    $this->cartRepository->removeDiscount();
                    return redirect()->route('guest.checkout.index')
                        ->with('error', 'Invalid promo code, order total has been adjusted')
                        ->withInput();
                }
                //and verify no orders with a newsletter_signup promo
                //have been shipped to the order->shippingAddress
                $discountAlreadyApplied = Order::where('id', '!=', $order->id)
                    ->whereHas('appliedDiscount', function ($query) {
                        return $query->where('newsletter_signup', true);
                    })->whereHas('addresses', function ($query) use ($order) {
                        return $query->where('address_1', $order->shippingAddress->address_1)
                            ->where('address_2', $order->shippingAddress->address_2)
                            ->where('city', $order->shippingAddress->city)
                            ->where('state_id', $order->shippingAddress->state_id)
                            ->where('zipcode', $order->shippingAddress->zipcode);
                    })->exists();

                if ($discountAlreadyApplied) {
                    $this->cartRepository->removeDiscount();
                    return redirect()->route('guest.checkout.index')
                        ->with('error', 'Invalid promo code, order total has been adjusted')
                        ->withInput();
                }
            }
            $order->discount_id = $discount->id;
            $order->save();
        }

        session(['order' => Arr::wrap($order->id)]);

        try {

            $order = $order->refresh();
            $order->loadMissing(['addresses', 'products']);

            $this->authorizeNetRepository->process($order);
            $this->authorizeNetRepository->confirm($order);

            session()->forget('order');
            $this->cartRepository->clear();
            
            event(new OrderCreateEvent($order));

            if (
                isset($discount) && $discount instanceof Discount 
                && !$discount->newsletter_signup
                && $discount->is_activable == 1
            ) {
                $discount->is_active = false;
                $discount->save();
            }

            return view('front.checkout.success', [
                'order' => $order
            ]);

        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        return redirect()->route('guest.checkout.index')->with(
            'error', "This transaction has been declined by your bank. Please check your account balance, billing address, and billing zipcode."
        );
    }
}
