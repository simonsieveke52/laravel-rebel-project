<?php

namespace App\Http\Middleware;

use Closure;
use App\Order;
use Illuminate\Support\Arr;

class InjectOrderData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! session()->has('order')) {
            return $next($request);
        }

        try {
            $orders = session()->get('order');
            $order = Order::findOrFail(array_shift(Arr::wrap($orders)));
        } catch (\Exception $e) {
            return $next($request);            
        }

        session($order->toArray());

        session([
            'billing_address_1' => $order->billing_address->address_1,
            'billing_address_2' => $order->billing_address->address_2,
            'billing_address_zipcode' => $order->billing_address->zipcode,
            'billing_address_state_id' => $order->billing_address->state_id,
            'billing_address_city' => $order->billing_address->city,
        ]);

        if (! $order->addresses->where('type', 'shipping')->isEmpty()) {
            session([
                'shipping_address_different' => true,
                'shipping_address_1' => $order->shipping_address->address_1,
                'shipping_address_2' => $order->shipping_address->address_2,
                'shipping_address_zipcode' => $order->shipping_address->zipcode,
                'shipping_address_state_id' => $order->shipping_address->state_id,
                'shipping_address_city' => $order->shipping_address->city,
            ]);
        }

        return $next($request);
    }
}
