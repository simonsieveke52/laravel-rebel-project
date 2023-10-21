<?php

namespace App;

use Carbon\Carbon;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'product_id',
        'name',
        'description',
        'email_address',
        'date',
        'grade'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'formatted_date'
    ];

    /**
     * Related product
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class)
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute()
    {
        $description = strip_tags(trim(isset($this->attributes['description']) ? $this->attributes['description'] : ''));

        return filterWords($description);
    }

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        $title = strip_tags(trim(isset($this->attributes['title']) ? $this->attributes['title'] : ''));

        return filterWords($title);
    }

    /**
     * @return string
     */
    public function getEmailAttribute()
    {
        $email = strip_tags(trim(isset($this->attributes['email']) ? $this->attributes['email'] : ''));

        return filterWords($email);
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        $name = strip_tags(trim(isset($this->attributes['name']) ? $this->attributes['name'] : ''));

        return filterWords($name);
    }

    /**
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }
}