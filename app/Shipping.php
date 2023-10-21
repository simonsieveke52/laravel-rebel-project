<?php

namespace App;

use App\Custom\ShoppingCart;
use App\Repositories\CartRepository;
use Illuminate\Support\Arr;

class Shipping extends Model
{
    /**
     * @var array
     */
    protected $appends = [
       'cost',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name', 'description', 'base_cost', 'status', 'is_frozen',
    ];


    /**
     * Get shipping cost
     *
     * @return float|null
     */
    public function getCostAttribute(): ?float
    {
        $totalCost = 0;
        $order = Order::where('id', session('order'))->first();

        if (isset($order) && (int) $order->has_free_shipping === 1) {
            return $totalCost;
        }

        $cartItems = app(CartRepository::class)->getCartItemsTransformed($order)->values();

        //The order has been quoted with flat rate shipping
        if ($order && $order->quote && $order->quote->hasFlatRateShipping) {
            //Filter items in the quote to compare to items in cart
            $quoteProducts = $order->quote->products->filter(function ($product) use ($cartItems) {
                return $cartItems->where('id', $product->id)
                                 ->where('quantity', $product->pivot->quantity)
                                 ->isNotEmpty();
            })->values();

            //When there are no changes to the cart, use the flat rate shipping cost from quote
            if ($quoteProducts->count() === $cartItems->count()) {
                return $order->quote->shipping_cost;
            }

            //Check if quoted items have been removed from the cart
            $noItemsRemoved = $order->quote->products->reject(function ($product) use ($cartItems) {
                return $cartItems->where('id', $product->id)
                                 ->where('quantity', '<', $product->pivot->quantity)
                                 ->isEmpty();
            })->isEmpty();

            //no items from quote were removed && new items added (more items in cart than quoted)
            if ($noItemsRemoved && ($cartItems->sum('quantity') > $order->quote->products->sum('pivot.quantity'))) {
                //loop over cart items to set $cartItems to add additional shippping costs for any items not quoted
                $temp = collect();
                foreach ($cartItems as $cartItem) {
                    $product = $order->quote->products->firstWhere('id', $cartItem->id);
                    if (is_null($product)) {
                        //the item was not quoted, will incur full shipping costs
                        $temp->push($cartItem);
                    } elseif ($cartItem->quantity > $product->pivot->quantity) {
                        //The item's quantity was incremented, it will incur shipping costs for the additional items
                        $cartItem->attributes->adjusted_quantity = $cartItem->quantity - $product->pivot->quantity;
                        $temp->push($cartItem);
                    }
                }
                $cartItems = $temp;
            }

            //When customer removes quoted items from cart they get full shipping cost
        }

        $isFrozen  = (int) ($this->attributes['is_frozen'] ?? $this->is_frozen ?? 0);
        $config    = config('default-variables.' . ($isFrozen ? 'frozen' : 'regular'));
        $cartItems->reject(function ($cartItem) use ($isFrozen) {
               return $cartItem->product->free_shipping || $cartItem->product->frozen === !$isFrozen;
           })
           ->each(function ($cartItem) use (&$totalCost) {
               if (!isset($cartItem->orderProduct) || (isset($cartItem->orderProduct) && ($cartItem->orderProduct->free_shipping === 0 || $cartItem->orderProduct->free_shipping === false))) {
                   $quantity = $cartItem->attributes->adjusted_quantity ?? $cartItem->quantity;
                   $totalCost += $cartItem->product->weight * $quantity;
               }
           });
        $total = ($config['per_pound'] * $totalCost);
        if ($order && $order->quote && $order->quote->hasFlatRateShipping) {
            $total += $order->quote->shipping_cost;
        }
        if ($total == 0) {
            return $total;
        }
        return $total <= $config['min'] ? $config['min'] : $total;
    }

    /**
     * @return float
     */
    public function getBaseCostAttribute()
    {
        return floatval($this->attributes['base_cost'] ?? $this->base_cost ?? 0);
    }

    /**
     * @param  Builder  $query
     *
     * @return void
     */
    public function scopeAvailable($query)
    {
        $frozen = (new CartRepository)->getCartItemsTransformed()
           ->map(function ($e) {
               return $e->product;
           })
           ->values()
           ->pluck('frozen')
           ->unique()
           ->values()
           ->toArray();

        return $query->whereIn('is_frozen', Arr::wrap($frozen));
    }
}
