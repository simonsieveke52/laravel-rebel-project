<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TrackingNumber extends Model
{
    use Notifiable;

	/**
	 * @var array
	 */
    protected $fillable = [
    	'user_file_id',
        'lot_number',
    	'quantity',
    	'order_id',
    	'number',
    	'name',
    ];

    /**
     * @return BelongsTo
     */
    public function order()
    {
    	return $this->belongsTo(Order::class);
    }
  
    /**
     * @return BelongsTo
     */
    public function userFile()
    {
        return $this->belongsTo(UserFile::class);
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        $name = isset($this->attributes['name']) ? $this->attributes['name'] : '';

        if (trim($name) === '') {
            return 'FedEx';
        }

        return $name;
    }

    /**
     * @return string
     */
    public function getCarrierCodeAttribute()
    {
        $name = isset($this->attributes['name']) ? $this->attributes['name'] : '';

        if (trim($name) === '') {
            return 'FedEx';
        }

        $arrayCarrierCodes = array_map('strtolower', config('amazon-mws.carrierCodes'));
        $diff = array_diff(explode(' ', $name), config('amazon-mws.carrierCodes'));

        foreach ($diff as $carrierCode) {
            if (($index = array_search(strtolower($carrierCode), $arrayCarrierCodes)) !== false) {
                return config('amazon-mws.carrierCodes')[$index];
            }
        }

        return $name;
    }
}
