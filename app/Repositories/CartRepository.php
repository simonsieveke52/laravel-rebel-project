<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Darryldecode\Cart\CartCondition;
use App\Facades\FrozenCartFacade as FrozenCart;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Repositories\Contracts\CartRepositoryContract;
use App\{Order, OrderProduct, Product, Shipping, Customer, Discount};

class CartRepository implements CartRepositoryContract
{
    /**
     * @param Product $product
     * @param int $quantity
     * @param $price
     * @param array $options
     *
     * @return Cart
     */
    public function addToCart(Product $product, int $quantity, $options = [])
    {
        $options['main_image'] = $product->main_image;
        $options['parent_id'] = $product->parent_id;

        if (! isset($options['free_shipping'])) {
            $options['free_shipping'] = (int) $product->free_shipping;
        }

        return Cart::add($product->id, $product->name, $product->price, $quantity, $options);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCartItems() : Collection
    {
        return Cart::getContent()->reject(function($item) {
            return ! (is_object($item) && (int) $item->id !== 0);
        });
    }

    /**
     * @param int $rowId
     */
    public function remove(int $rowId)
    {
        Cart::removeConditionsByType('discount');
        return Cart::remove($rowId);
    }

    /**
     * Remove the applied discount from the cart
     *
     * @return ?
     */
    public function removeDiscount()
    {
        return Cart::removeConditionsByType('discount');
    }

    /**
     * Clear the Cart
     *
     * @return void;
     */
    public function clear()
    {
        Cart::removeConditionsByType('discount');
        Cart::removeConditionsByType('shipping');
        Cart::removeConditionsByType('tax');
        Cart::clear();
    }

    /**
     * Count the items in the cart
     *
     * @return int
     */
    public function countItems() : int
    {
        return Cart::getContent()->count();
    }

    /**
     * Get the sub total of all the items in the cart
     *
     * @param int $decimals
     * @return float
     */
    public function getSubTotal()
    {
        return Cart::getSubTotal();
    }

    /**
     * Get the final total of all the items in the cart minus tax
     *
     * @param int $decimals
     * @param float $shipping
     * @return float
     */
    public function getTotal()
    {
        return Cart::getTotal();
    }

    /**
     * Get shipping price
     *
     * @param  mixed $cartInstance
     * @return float
     */
    public function getShipping()
    {
        $shipping = Cart::getConditionsByType('shipping');

        try {
            $order = Order::findOrFail(session('order'));   
            $order = $order instanceof Order ? $order : $order->first();
            if ((int) $order->has_free_shipping === 1) {
                return 0;
            }
        } catch (Exception $e) {
        }

        if ($shipping->isEmpty()) {
            return 0;
        }

        if (is_null($shipping->first()->parsedRawValue)) {
            return $shipping->first()->getCalculatedValue(0);
        }

        return floatval($shipping->first()->parsedRawValue);
    }

    /**
     * Get shipping price
     *
     * @param  mixed $cartInstance
     * @return float
     */
    public function getDiscountValue()
    {
        try {

            $discountObject = $this->getDiscount();

            if (($discountObject instanceof Discount) && ! $discountObject->isValid()) {
                Cart::removeConditionsByType('discount');
                return 0;
            }
        } catch (\Exception $e) {

        }

        try {

            $discount = Cart::getConditionsByType('discount');

            if ($discount->isEmpty()) {
                return 0;
            }

            if (is_null($discount->first()->parsedRawValue)) {
                return round(floatval($discount->first()->getCalculatedValue(0)), 2);
            }

            return round(floatval($discount->first()->parsedRawValue), 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Set shipping
     *
     * @param Shipping|array $shipping
     */
    public function setShipping($shipping)
    {
        if ($shipping instanceof Shipping) {
            $name = $shipping->name;
            $cost = $shipping->cost;
        } else if ($shipping instanceof Collection) {
            $cost = $shipping->sum('cost');
            $name = Str::slug(implode('-', $shipping->pluck('name')->toArray()));
        }

        Cart::removeConditionsByType('shipping');

        $condition = new CartCondition([
            'name' => $name ?? '',
            'type' => 'shipping',
            'target' => 'total',
            'value' => '+' . floatval($cost ?? 0),
            'order' => 2
        ]);

        return Cart::condition($condition);
    }

    public function setFreeShipping()
    {
        Cart::removeConditionsByType('shipping');

        $condition = new CartCondition([
            'name' => 'Free Shipping',
            'type' => 'shipping',
            'target' => 'total',
            'value' => '+' . floatval(0),
            'order' => 2,
        ]);
        return Cart::condition($condition);
    }

    /**
     * Set tax
     *
     * @param float $taxRate
     */
    public function setTax(float $taxRate)
    {
        Cart::removeConditionsByType('tax');

        $condition = new CartCondition([
            'name' => 'tax',
            'type' => 'tax',
            'target' => 'total',
            'value' => '+' . $taxRate . '%',
            'order' => 1
        ]);

        return Cart::condition($condition);
    }

     /**
     * Set Discount
     * @param mixed $discount
     *
     * @return
     */
    public function setDiscount($discount)
    {
        Cart::removeConditionsByType('discount');

        $discount = ! ($discount instanceof Discount)
            ? Discount::where('id', $discount)->firstOrFail()
            : $discount;

        if ($discount->newsletter_signup) {
            $discountValue = $discount->amount;
        }

        if (! $discount->newsletter_signup) {
            $discountValue = $discount->value;
        }

        if (($discount->products()->count() > 0) && (! $discount->newsletter_signup)) {
            return $this->getProductsDiscountValue($discount);
        }

        $condition = new CartCondition([
            'name' => trim($discount->coupon_code) === '' ? 'Bulk Discount' : $discount->coupon_code,
            'type' => 'discount',
            'target' => $discount->discount_type,
            'value' => '-' . $discountValue,
            'order' => 3,
        ]);

        return Cart::condition($condition);
    }

    /**
     * @param  Discount $discount
     * @return void
     */
    public function getProductsDiscountValue(Discount $discount)
    {
        $cartItems = $this->getMappedCartItems();

        $cartItems->map(function ($item) use ($discount, $cartItems) {

            $id = is_array($item) ? $item['id'] : $item->id;
            $parentId = is_array($item) ? $item['attributes']['parent_id'] : $item->attributes->parent_id;
            $ids = array_values(array_filter([$id, $parentId]));

            if ($discount->products()->whereIn('id', $ids)->orWhereIn('parent_id', $ids)->count() === 0) {
                return $item;
            }

            // is parent/child product
            if (
                Product::whereIn('parent_id', $ids)
                            ->remember(60 * 60)
                            ->count() > 0
                &&
                Product::whereIn('parent_id', $ids)
                            ->orWhereIn('id', $ids)
                            ->remember(60 * 60)
                            ->get()
                            ->pluck('id')
                            ->unique()
                            ->intersect($cartItems->pluck('id')->unique())
                            ->count() <= 1
            ) {
                return $item;
            }

            Cart::removeItemCondition($id, 'discount');

            $item['attributes']['discount_id'] = $discount->id;

            Cart::addItemCondition($id, new CartCondition([
                'name' => 'discount',
                'type' => 'discount',
                'value' => '-' . $discount->value,
                'order' => 3,
            ]));

            return $item;
        });
    }

    /**
     * Get tax
     *
     * @return float
     */
    public function getTax()
    {
        $tax = Cart::getConditionsByType('tax');

        if ($tax->isEmpty()) {
            return 0;
        }

        return round($tax->first()->parsedRawValue, 2);
    }

    /**
     * Get tax rate
     *
     * @return float
     */
    public function getTaxRate()
    {
        $tax = Cart::getConditionsByType('tax');

        if ($tax->isEmpty()) {
            return 0;
        }

        $value = str_replace(['+', '%'], '', $tax->first()->getValue());

        return round($value, 2);
    }

    /**
     * Get discount
     *
     * @return mixed
     */
    public function getDiscount()
    {
        $discountCondition = Cart::getConditionsByType('discount')->first();

        if ($discountCondition === null) {
            return null;
        }

        try {
            $discount = Discount::where('coupon_code', $discountCondition->getName())->firstOrFail();
            return $discount;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param int $rowId
     * @param int $quantity
     * @return CartItem
     */
    public function updateQuantityInCart(int $rowId, int $quantity)
    {
        if(Cart::get($rowId)->quantity > $quantity){
            Cart::removeConditionsByType('discount');
        }
        return Cart::update($rowId, [
            'quantity' => [
                'value' => $quantity,
                'relative' => false
            ]
        ]);
    }

    /**
     * Return the specific item in the cart
     *
     * @param int $rowId
     * @return \Gloudemans\Shoppingcart\CartItem
     */
    public function findItem(int $rowId)
    {
        return Cart::get($rowId);
    }

    /**
     * Get Mapped cart items
     *
     * @return collection
     */
    public function getMappedCartItems() : Collection
    {
        return $this->getCartItems()->map(function($item) {
                $array = is_array($item) ? $item : $item->all();
                $array['deleted'] = false;
                $array['bulkPrice'] = round($item->getPriceWithConditions(), 2);
                $array['conditions'] = array_values($array['conditions']);
                return $array;
            })
            ->values();
    }

    /**
     * @return void
     */
    public function checkItemsStock()
    {
        $this->getCartItems()->map(function($item) {
            try {
                $id = is_object($item) ? $item->id : $item['id'];
                $product = Product::findOrFail($id);
                if ((int) $product->quantity <= 0 || (int) $product->status === 0) {
                    Cart::remove($id);
                }
            } catch (\Exception $e) {
            }
        });
    }

    /**
     * Get Cart Items After Transformation
     *
     * @param  Order|null  $order
     *
     * @return collection
     */
    public function getCartItemsTransformed(Order $order = null) : Collection
    {
        if($order){
            return $this->getCartItems()->transform(function($item) use($order) {
                try {
                    $id = is_object($item) ? $item->id : $item['id'];
                    $item->product = Product::where('id', $id)->remember(60 * 60)->first();
                    $item->orderProduct = OrderProduct::where('product_id',$id)->where('order_id',$order->id)->first();
                    $item->attributes->adjusted_quantity = null;
                } catch (\Exception $e) {

                }
                return $item;
            });
        }

        return $this->getCartItems()->transform(function($item) {
            try {
                $id = is_object($item) ? $item->id : $item['id'];
                $item->product = Product::where('id', $id)->remember(60 * 60)->first();
                $item->attributes->adjusted_quantity = null;
            } catch (\Exception $e) {

            }
            return $item;
        });
    }

    /**
     * Set Bulk Discount
     * 
     * @param mixed $discount
     *
     * @return
     */
    public function setBulkDiscount($cartItem)
    {
        Cart::removeItemCondition($cartItem->id, 'Bulk Discount');

        $qty = $cartItem->quantity;

        if($qty < 2) {
            $value = '0%';
        } elseif($qty < 4) {
            $value = '4%';
        } elseif($qty < 7) {
            $value = '5%';
        } else {// 7 or more default to 6%;
            $value = '6%';
        }

        $condition = new CartCondition([
            'name' => 'Bulk Discount',
            'type' => 'discount',
            'value' => '-' . $value,
            'order' => 2,
        ]);

        return Cart::addItemCondition($cartItem->id, $condition);
    }

}
