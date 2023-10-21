<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, Notifiable;

    /**
     * Always append those attributes
     *
     * @var
     */
    protected $appends = [
        'order_source', 'first_name', 'last_name'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'confirmed', 'confirmed_at', 'deleted_at', 'mailed_at' , 'mailed', 'transaction_id'
    ];

    /**
     * The attributes that are casted to carbon dates
     *
     * @var array
     */
    protected $dates = [
        'deleted_at', 'confirmed_at', 'mailed_at', 'refunded_at', 'push_tracking_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'transaction_response',
        'cc_expiration_month',
        'cc_expiration_year',
        'payment_method',
        'transaction_id',
        'cc_expiration',
        'card_type',
        'cc_number',
        'cc_name',
        'cc_cvv',
    ];

    /**
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', trim($value));
    }

    /**
     * Get order source attribute
     *
     * @return string
     */
    public function getOrderSourceAttribute() : string
    {
        return trim($this->gclid) == '' ? 'Other' : 'Adwords';
    }

    /**
     * @return void
     */
    public function getPhoneAttribute($value)
    {
        return preg_replace('/[^0-9]/', '', trim($value));
    }

    /**
     * @return string
     */
    public function getLastCCDigitsAttribute()
    {
        if (! isset($this->cc_number)) {
            return 'XXXX';
        }

        if (trim($this->cc_number) === '') {
            return 'XXXX';
        }

        try {
            $ccNumber = decrypt($this->cc_number);
            return strlen($ccNumber) > 4 ? substr($ccNumber, -4) : $ccNumber;
        } catch (\Exception $e) {
            return substr($this->cc_number, -4);
        }
    }

    /**
     * Get shipping address
     *
     * @return Address|null
     */
    public function getShippingAddressAttribute()
    {
        $foundShipping = $this->addresses->where('type', 'shipping')->first();

        return $foundShipping ?? $this->getBillingAddressAttribute();
    }

    /**
     * Get tax attribute
     *
     * @param  float $value
     * @return float
     */
    public function getTaxAttribute($value)
    {
        return round($value, 2);
    }

    /**
     * Get subtotal attribute
     *
     * @param  float $value
     * @return float
     */
    public function getSubtotalAttribute($value)
    {
        return round($value, 2);
    }

    /**
     * Get shipping cost attribute
     *
     * @param  float $value
     * @return float
     */
    public function getShippingCostAttribute($value)
    {
        return round($value, 2);
    }

    /**
     * Get shipping cost estimate attribute
     *
     * @return float
     */
    public function getShippingCostEstimateAttribute()
    {
        if ($this->quote && $this->quote->shipping_cost !== null) {
            return round($this->quote->shipping_cost, 2);
        } elseif($this->shipping_cost > 0) {
            return round($this->shipping_cost, 2);
        }

        return round($this->products->reduce(function($carry, $product) {
            if ($product->pivot->free_shipping) {
                $shippingCost = 0;
            } else {
                $shippingCost = $product->shippingCost * $product->pivot->quantity;
            }
            return $carry + $shippingCost;
        }), 2);
    }

    /**
     * Get total attribute
     *
     * @param  float $value
     * @return float
     */
    public function getTotalAttribute($value)
    {
        return round($value, 2);
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return ucwords(strtolower($this->attributes['name']));
    }

    /**
     * Get First name attribute
     *
     * @param  float $value
     * @return float
     */
    public function getFirstNameAttribute()
    {
        $name = explode(' ', trim($this->attributes['name']));
        return $name[0] ?? '';
    }

    /**
     * @return string
     */
    public function getShippingNameAttribute()
    {
        if ($this->shippings->isEmpty()) {
            return 'N/A';
        }

        return $this->shippings->pluck('name')->map(function($e) {

            if (strtolower($e) === 'regular items shipping') {
                return 'Regular';
            }

            if (strpos(strtolower($e), 'frozen') !== false) {
                return 'Frozen';
            }

            if (strpos(strtolower($e), 'cold') !== false) {
                return 'Frozen';
            }

            return 'N/A';

        })
        ->implode(' & ');
    }

    /**
     * Get First name attribute
     *
     * @param  float $value
     * @return float
     */
    public function getLastNameAttribute()
    {
        $name = explode(' ', trim($this->attributes['name']));

        $firstName = array_shift($name);
        $lastName = implode(' ', $name);

        return trim($lastName) === '' ? $firstName : $lastName;
    }

    /**
     * @return Carbon
     */
    public function getEstimatedDeliveryDateAttribute()
    {
        $increment = $this->products->where('frozen', true)->count() === $this->products->count()
            ? 3
            : 5;

        return now()->addDays($increment);
    }

    /**
     * @return boolean
     */
    public function getConfirmedAttribute()
    {
        return (int) $this->attributes['confirmed'] === 1;
    }

    /**
     * @return boolean
     */
    public function getCardTypeAttribute()
    {
        if (isset($this->attributes['card_type'])) {
            return trim($this->attributes['card_type']) === ''
                ? trim($this->attributes['card_type'])
                : 'Card';
        }

        $card = $this->attributes['card_type'] ?? '';

        if (trim($card) === '') {
            return 'Card';
        }

        return $card;
    }

    /**
     * Get billing address
     *
     * @return Address|null
     */
    public function getBillingAddressAttribute()
    {
        return $this->addresses->where('type', 'billing')->first();
    }

    /**
     * Mark order as canceled
     *
     * @return Order
     */
    public function markAsCanceled()
    {
        $this->order_status_id = 4;
        $this->save();
        return $this;
    }

    /**
     * Mark order as shipped
     *
     * @return Order
     */
    public function markAsShipped()
    {
        $this->order_status_id = 2;
        $this->save();
        return $this;
    }

    /**
     * Mark order as new
     *
     * @return Order
     */
    public function markAsNew()
    {
        $this->order_status_id = 1;
        $this->save();
        return $this;
    }

    /**
     * Mark order as mailed
     *
     * @return Order
     */
    public function markAsMailed()
    {
        $this->mailed = true;
        $this->mailed_at = date('Y-m-d H:i:s');
        $this->save();
        return $this;
    }

    /**
     * Mark order as refunded
     *
     * @return self
     */
    public function markAsRefunded()
    {
        $this->refunded = true;
        $this->refunded_at = date('Y-m-d H:i:s');
        $this->order_status_id = 4;
        $this->save();
        return $this;
    }

    /**
     * Mark this order as it needs review
     *
     * @return self
     */
    public function markAsReviewed()
    {
        $this->should_review = 0;
        $this->reviewed = true;
        $this->reviewed_at = date('Y-m-d H:i:s');

        if ($this->order_status_id == 0) {
            $this->order_status_id = 1;
        }

        $this->save();

        return $this;
    }

    /**
     * Mark this order as it needs review
     *
     * @return self
     */
    public function markAsNeedsReview()
    {
        $this->should_review = 1;
        $this->reviewed = false;
        $this->order_status_id = 0;
        $this->save();

        return $this;
    }

    /**
     * Push tracking to amazon
     *
     * @return self
     */
    public function markAsNeedsPushTracking()
    {
        $this->push_tracking = true;
        $this->save();

        return $this;
    }

    /**
     * @return self
     */
    public function markAsTrackingPushed()
    {
        $this->push_tracking = false;
        $this->push_tracking_at = now();
        $this->save();

        return $this;
    }

    /**
     * Get ordered products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withTimestamps()
                    ->withoutGlobalScopes()
                    ->withPivot(['quantity', 'price', 'discount', 'discount_id', 'options', 'was_outputted', 'was_outputted_at', 'free_shipping']);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class)
            ->with('product');
    }

    /**
     * Get shipping option
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippings()
    {
        return $this->belongsToMany(Shipping::class)
            ->withPivot(['cost', 'is_frozen']);
    }

    /**
     * Get applied discount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appliedDiscount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }

    public function applyAbandonedCartDiscount()
    {
        if (config('mailchimp.abandoned_cart.discount') !== null) {
            $discount = Discount::create([
            'discount_amount' => $this->subtotal * (config('mailchimp.abandoned_cart.discount') / 100),
            'discount_type'   => 'Abandoned Cart Discount',
            'discount_method' => 'Percentage off products',
            'coupon_code'     => '',
            ]);

            $this->appliedDiscount()->associate($discount);
            $this->save();
        }
    }

    public function applyDiscount()
    {
        $order_products = $this->orderProducts;
        $discount_amount = 0;
        foreach ($order_products as $order_product) {
            $discount_amount += $order_product->discount * $order_product->quantity;
        }

        $discount = Discount::create([
            'discount_amount' => $discount_amount,
            'discount_type' => 'Discount on order',
            'discount_method' => 'Percentage off products',
            'coupon_code' => '',
        ]);

        $this->update([
            'discount_id' => $discount->id,
        ]);
    }

    public function applyQuoteDiscount(){
        $discount_amount = 0;
        foreach($this->orderProducts as $product){
            $discount_amount += $product->discount_amount;
        }
        $discount = Discount::create([
           'discount_amount' => $discount_amount,
           'discount_type' => 'Bulk Order Discount',
           'discount_method' => 'Bulk Order',
           'coupon_code' => '',
        ]);
        $this->update([
           'discount_id' => $discount->id
        ]);
    }

    /**
     * Get customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get all order addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get all tracking numbers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trackingNumbers()
    {
        return $this->hasMany(TrackingNumber::class);
    }

    /**
     * Get order status
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     * Get order origin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function origin()
    {
        return $this->belongsTo(OrderOrigin::class, 'origin_id');
    }

    /**
     * Get api responses
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apiResponses()
    {
        return $this->hasMany(OrderApiResponse::class);
    }

    /**
     * Get api responses
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function amazonApiResponses()
    {
        return $this->hasMany(OrderApiResponse::class)
            ->where('caller', 'AmazonFacade::createFulfillmentOrder')
            ->orderBy('id', 'desc');
    }
    
    /**
     * @return AmazonFeedRequest
     */
    public function amazonFeedRequests()
    {
        return $this->belongsToMany(AmazonFeedRequest::class);
    }

    /**
     * @return boolean
     */
    public function hasStatus()
    {
        return $this->orderStatus instanceof \App\Shop\OrderStatuses\OrderStatus;
    }

    /**
     * Confirmed orders scope
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', true)
            ->where('refunded', false)
            ->where('order_status_id', '!=', 4);
    }

    /**
     * Site orders
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeSite($query)
    {
        return $query->where('type', 'direct');
    }

    /**
     * Site orders
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeGoogle($query)
    {
        return $query->where('type', 'google');
    }

    /**
     * Amazon orders
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeAmazon($query)
    {
        return $query->where('type', 'amazon');
    }

    /**
     * Amazon orders (MFN)
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeMfn($query)
    {
        return $query->where('amazon_fulfillment_channel', 'mfn');
    }
    
    /**
     * Amazon orders (AFN)
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeAfn($query)
    {
        return $query->where('amazon_fulfillment_channel', 'afn');
    }

    /**
     * @param  Builder $query
     * @return Builder
     */
    public function scopeManualTracking($query)
    {
        return $query->where('type', 'amazon')->where('push_tracking', true);
    }

    /**
     * Today scope
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeToday($query)
    {
        return $query->where('confirmed', true)->whereDate('created_at', now());
    }

    /**
     * Confirmed orders scope
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeNotConfirmed($query)
    {
        return $query->where('confirmed', false);
    }

    /**
     * Pending orders
     *
     * @param  Builder $query
     * @return void
     */
    public function scopePending($query)
    {
        return $query
            ->where('confirmed', true)
            ->where('refunded', false)
            ->where('order_status_id', '!=', 4)
            ->whereDate('created_at', '<=', now()->subDays(2))
            ->whereDoesntHave('trackingNumbers');
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('services.slack.order_notification_webhook');
    }
}
