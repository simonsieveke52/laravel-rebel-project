<?php

namespace App;

use App\Scopes\CachableScope;
use App\{Model, City, State};

class Zipcode extends Model
{
    /**
     * All fillable attributes
     *
     * @var array
     */
	protected $guarded = [];

    /**
     * @var array
     */
    protected $appends = [
        'transit_time_list'
    ];

	/**
	 * Router will use this column to find the zipcode
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
	    return 'name';
	}

    /**
     * @return collect
     */
    public function getTransitTimeListAttribute()
    {
        return collect([
            [
                'warehouse' => 'illinois',
                'time' => $this->attributes['illinois'] ?? 0
            ],
            [
                'warehouse' => 'maryland',
                'time' => $this->attributes['maryland'] ?? 0
            ],

            [
                'warehouse' => 'modesto',
                'time' => $this->attributes['modesto'] ?? 0
            ],
            [
                'warehouse' => 'oklahoma',
                'time' => $this->attributes['oklahoma'] ?? 0
            ],
            [
                'warehouse' => 'burley',
                'time' => $this->attributes['burley'] ?? 0
            ],
            [
                'warehouse' => 'arizona',
                'time' => $this->attributes['arizona'] ?? 0
            ],
        ]);
    }

    /**
     * Get tax rate
     * 
     * @param  string $value
     * @return float       
     */
    public function getTaxRateAttribute($value)
    {
        return config('default-variables.tax_status') ? floatval($value) : 0;
    }

	/**
	 * Zipcode state
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
