<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\{Order, Product, Shipping};
use Darryldecode\Cart\ItemCollection;
use App\Repositories\Contracts\CartRepositoryContract;

class OrderRepository extends BaseRepository
{
    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * Order repository contruct
     */
    public function __construct(CartRepositoryContract $cartRepository, $order = null)
    {
        parent::__construct($order);
        $this->cartRepository = $cartRepository;
    }

    /**
     * Create order from the given data
     *
     * @param  Array $data
     * @return Collection
     */
    public function createOrder(array $data)
    {
        if (isset($data['payment_method']) && $data['payment_method'] == 'credit_card') {

            // get credit card type and remove spaces from the card number
            $data['cc_number'] = str_replace(' ', '', $data['cc_number']);
            $data['card_type'] = getCreditCardType($data['cc_number']);
            $data['cc_number'] = encrypt($data['cc_number']);

            // extract expiration month and year from the given string
            $data['cc_expiration_month'] = trim($data['cc_expiration_month']);
            $data['cc_expiration_year'] = trim($data['cc_expiration_year']);
            $data['cc_expiration'] = $data['cc_expiration_month'] . '/' . $data['cc_expiration_year'];
        }

        $orderDetails = [
            'name'                => $data['first_name'] . ' ' . $data['last_name'],
            'first_name'          => $data['first_name'],
            'last_name'           => $data['last_name'],
            'email'               => $data['email'],
            'phone'               => preg_replace('/[^0-9]/', '', isset($data['phone']) ? trim($data['phone']) : null),
            'cc_number'           => $data['cc_number'] ?? null,
            'cc_name'             => $data['cc_name'] ?? null,
            'cc_expiration'       => $data['cc_expiration'] ?? null,
            'cc_expiration_month' => $data['cc_expiration_month'] ?? null,
            'cc_expiration_year'  => $data['cc_expiration_year'] ?? null,
            'card_type'           => $data['card_type'] ?? null,
            'cc_cvv'              => $data['cc_cvv'] ?? null,
            'gclid'               => session('gclid'),
            'payment_method'      => $data['payment_method'] ?? null,
            'tax_rate'            => $this->cartRepository->getTaxRate(),
            'customer_id'         => $data['customer_id'] ?? null,
            'order_status_id'     => 1,
            'origin_id'           => $data['origin_id'] ?? null,
            'user_agent'          => request()->header('User-Agent'),
            'ip_address'          => request()->ip(),
        ];

        // find order by id and update it or create new order
        $order = Order::updateOrCreate(
            Arr::wrap([
                'id' => isset($data['id']) && (int) $data['id'] > 0 ? $data['id'] : null, 
                'confirmed' => false,
            ]), 
            $orderDetails
        );

        $order->shippings()->detach();

        $order->shipping_cost = 0;

        if ((int) $order->has_free_shipping === 0) {

            $shippings = Shipping::available()->get();
            
            foreach ($shippings as $shipping) {
                $order->shippings()->attach($shipping, [
                    'cost'    => $shipping->cost,
                    'is_frozen'  => $shipping->is_frozen
                ]);
            };
            
            $order->shipping_cost = $shippings->sum('cost');
        }

        $order->save();

        return $order->refresh();
    }

    /**
     * @param Order $order
     * @param Collection $collection
     */
    public function buildOrderDetails(Order $order, Collection $collection)
    {
        $order->products()->detach();

        $order->subtotal = 0;
        $order->discount_amount = $this->cartRepository->getDiscountValue();

        $collection->each(function ($item) use ($order) {

            $product = Product::findOrFail($item->id);

            $options = $item->attributes->toArray();
            $options['final_price'] = round($item->getPriceWithConditions(), 2);

            $order->products()->attach($product, [
                'quantity' => $item->quantity,
                'price'    => $item->price,
                'discount' => $item->price - round($item->getPriceWithConditions(), 2),
                'discount_id' => $options['discount_id'] ?? null,
                'free_shipping' => (int) ($options['free_shipping'] ?? 0),
                'options'  => json_encode($options)
            ]);

            $order->subtotal += (round($item->getPriceWithConditions(), 2) * $item->quantity);
        });

        $order->tax = ($order->tax_rate / 100) * ($order->subtotal + $order->shipping_cost);
        $order->total = $order->subtotal + $order->shipping_cost + $order->tax - $order->discount_amount;
        $order->save();

        return $collection;
    }

    /**
     * For each product we reduce quantity and increase sales counter
     *
     * @return mixed
     */
    public function confirmOrder(Order $order)
    {
        return $order->products->each(function($product){
            // $product->decrement('quantity', (int) $product->pivot->quantity);
            // $product->increment('sales_count');
        });
    }
}
