<?php

namespace App;

use App\Order;
use App\Product;
use App\Shipping;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * @var array
     */
    protected $appends = [
        'value',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'coupon_code',
        'is_active',
        'is_activable',
        'discount_type',
        'discount_amount',
        'discount_method',
        'expiration_date',
        'activation_date',
        'collects_email',
        'name',
        'usage_limit',
        'description',
        'shipping_id',
        'trigger_amount',
        'is_triggerable',
        'newsletter_signup',
    ];

    /**
     * Generate a unique coupon code
     *
     * $param  int $length
     * @return string $couponCode
     */
    public static function generateCouponCode(int $length = 40)
    {
        $couponCode = Str::random($length);
        if (self::where('coupon_code', $couponCode)->exists()) {
            return self::generateCouponCode($length);
        }
        return $couponCode;
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function order()
    {
        return $this->hasMany(Order::class, 'discount_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscriber()
    {
        return $this->hasOne(Subscriber::class);
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    /**
     * Get discount target on cart.
     *
     * @return string
     */
    public function getDiscountTypeAttribute()
    {
        return isset($this->attributes['discount_type']) && trim($this->attributes['discount_type']) !== ''
            ? $this->attributes['discount_type']
            : 'subtotal';
    }

    public function getAmountAttribute()
    {
        if($this->discount_method === 'percentage') {
            return floatval($this->discount_amount) . '%';
        } elseif($this->discount_method === 'dollars') {
            return config('cart.currency_symbol') . number_format($this->discount_amount, 2);
        } else {
            return 'free items';
        }
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        if (! $this->attributes['newsletter_signup']) {
            return ((int) $this->attributes['is_active'] === 1) && $this->order()->count() <= (int) $this->attributes['usage_limit'];
        }

        try {
            $order = Order::findOrFail(session('order')[0]);

            //check is active
            if (! $this->attributes['is_active']) {
                return false;
            }

            //check is subscriber (by order email)
            $subscriber = Subscriber::firstWhere('email', $order->email);

            if ($subscriber === null) {
                return false;
            }

            //check is subscriber discount already used
            if ($subscriber->discount_id !== null) {
                return false;
            }

            //check is subscriber signup prior to promo expiration date
            $promo_exp = $subscriber->created_at->addDays(config('mailchimp.newsletter.promo_expiration'));

            if (now()->greaterThan($promo_exp)) {
                return false;
            }
            //check if any orders with a newsletter_signup discount
            //with the same email exist
            return ! Order::where('email', $subscriber->email)
                ->where('confirmed', true)
                ->whereHas('appliedDiscount', fn($query) => $query->where('newsletter_signup', true))
                ->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get discount value from amount set, if discount method is percentage then
     * return the valid percentage value between 0 and 100% else return
     * the amount set for this discount direcly on the table.
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if($this->attributes['discount_method'] === 'percentage') {
            $discountNum = floatval($this->attributes['discount_amount']) >= 1
                ? floatval($this->attributes['discount_amount'])
                : floatval($this->attributes['discount_amount']) * 100;
            return $discountNum . '%';
        }

        return $this->attributes['discount_amount'];
    }

    /**
     * @return void
     */
    public function scopeSiteDiscount($query)
    {
        $query->whereIn('discount_type', ['shipping', 'subtotal', 'total']);
    }
}
