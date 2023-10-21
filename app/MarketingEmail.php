<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketingEmail extends Model
{
	/**
	 * @var array
	 */
    protected $fillable = [
    	'code', 'email', 'first_name', 'last_name', 'name', 'phone'
    ];
}
