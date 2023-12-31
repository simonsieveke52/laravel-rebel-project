<?php

namespace App;

use App\Scopes\EnabledScope;
use App\Scopes\CachableScope;
use App\{Model, City, Country, Zipcode};

class State extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'abv', 'status', 'country_id'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new EnabledScope);
        static::addGlobalScope(new CachableScope);
    }
    
    /**
     * @return string
     */
    public function getStateAttribute()
    {
        return ucwords(strtolower($this->attributes['name']));
    }

    /**
     * @return string
     */
    public function getAbvAttribute()
    {
        return strtoupper($this->attributes['abv']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zipcodes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Zipcode::class);
    }
}
