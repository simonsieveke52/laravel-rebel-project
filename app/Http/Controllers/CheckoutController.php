<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Repositories\CheckoutRepository;
use App\{Order, Shipping, Discount, Address};
use App\Http\Requests\CustomerCheckoutRequest ;

class CheckoutController extends CheckoutBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($this->cartRepository->getCartItems()->isEmpty()) {
            return redirect()->route('home')->with('message', 'Your cart is empty.');
        }

        $customer = $this->loggedUser();

        if ($customer->addresses->isEmpty()) {
            return redirect()->route('customer.address.create', $customer)
                ->with('message', 'Create your billing address first.');
        }

        $customer->addresses->loadMissing('state');

        return view('front.customers.checkout', [
            'billingAddresses' => $customer->addresses->where('type', 'billing'),
            'shippingAddresses' => $customer->addresses->where('type', 'shipping'),
        ]);
    }

    /**
     * Create and store order
     *
     * @param CartCheckoutRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @codeCoverageIgnore
     */
    public function store(CustomerCheckoutRequest $request)
    {
        $data = $request->except('_token');
        
        $customer = $this->loggedUser();

        // push required attributes to data array
        $data['name'] = $customer->name;
        $data['email'] = $customer->email;
        $data['phone'] = $customer->phone;
        $data['customer_id'] = $customer->id;

        // Create customer order with required attributes
        $order = $this->orderRepository->createOrder($data);

        $cartItems = $this->cartRepository->getCartItemsTransformed();
        $this->orderRepository->buildOrderDetails($order, $cartItems);

        // find billing address
        $address = Address::findOrFail($request->billing_address);

        // associate address to current order
        $address->order()->associate($order)->save();

        // Customer has shipping address diffrent than billing address
        // Find shipping address and create relation between order.
        if ($request->has('shipping_address') && (int) $request->shipping_address !== 0) {
            $address = Address::findOrFail($request->shipping_address);
            $address->order()->associate($order)->save();
        }

        if($discount = $this->cartRepository->getDiscount()) {
            $order->discount()->associate($discount);
        }

        session(['order' => Arr::wrap($order->id)]);

        return redirect()->route('checkout.execute');
    }
}
