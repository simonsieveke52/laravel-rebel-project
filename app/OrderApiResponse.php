<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderApiResponse extends Model
{
    /**
	 * @var array
	 */
    protected $fillable = [
    	'order_id', 'caller', 'content', 'status'
    ];

    /**
     * @return array
     */
    public function getContentAttribute()
    {
    	return json_decode($this->attributes['content'] ?? $this->content ?? '');
    }
}
