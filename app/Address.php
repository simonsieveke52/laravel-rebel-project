<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    /**
     * Soft delete date
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'status',
        'zipcode',
        'city',
        'order_id',
        'state_id',
        'address_1',
        'address_2',
        'country_id',
        'customer_id',
    ];

    /**
     * @return string
     */
    public function getAddress1Attribute()
    {
        if (! isset($this->attributes['address_1'])) {
            return '';
        }

        return ucwords(strtolower($this->attributes['address_1']));
    }

    /**
     * @return string
     */
    public function getAddress2Attribute()
    {
        if (! isset($this->attributes['address_2'])) {
            return '';
        }

        return ucwords(strtolower($this->attributes['address_2']));
    }

    /**
     * @return string
     */
    public function getCityAttribute()
    {
        if (! isset($this->attributes['city'])) {
            return '';
        }

        return ucwords(strtolower($this->attributes['city']));
    }

    /**
     * Get full address string
     *
     * @return string
     */
    public function toString()
    {
        $string  = $this->address_1 . ' ';
        $string .= empty($this->address_2) ? '' : $this->address_2 . ' ';
        $string .= $this->city . ', ';
        $string .= $this->state->abv . ' ';
        $string .= $this->zipcode;
        return $string;
    }

    /**
     * Country associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    /**
     * State associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class)->withDefault();
    }

    /**
     * Order associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
