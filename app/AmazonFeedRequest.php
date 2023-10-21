<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;

class AmazonFeedRequest extends Model
{
	/**
	 * @var array
	 */
    protected $fillable = [
    	'feed_submission_id',
		'feed_type',
		'feed_processing_status',
		'response_at',
		'response'
    ];

    /**
     * @var array
     */
    protected $dates = [
    	'response_at'
    ];

    /**
     * @return orders
     */
    public function orders()
    {
    	return $this->belongsToMany(Order::class);
    }

    /**
     * @param  array  $response
     * @return AmazonFeedRequest
     */
    public function markResponseStatus(array $response)
    {
    	$this->response_at = now();
        $this->response = json_encode($response);
    	$this->status = ! (isset($response['Result']) && ! empty($response['Result']));
        $this->save();
        return $this;
    }

    /**
     * @return Collection
     */
    public function getFailedOrdersAttribute()
    {
        if ((int) $this->status === 1) {
            return collect();
        }

        $response = json_decode($this->attributes['response'], true);
        $result = isset($response['Result']) ? $response['Result'] : [];

        return Order::whereIn(
            'amazon_order_id', array_column(array_column($result, 'AdditionalInfo'), 'AmazonOrderID')
        )->get();
    }

    /**
     * @return Collection
     */
    public function getProcessedOrdersAttribute()
    {
        if ((int) $this->status === 1) {
            $this->orders()->get();
        }

        if (! isset($this->attributes['response'])) {
            return collect();
        }

        $response = json_decode($this->attributes['response'], true);
        $result = isset($response['Result']) ? $response['Result'] : [];

        return $this->orders()->whereNotIn(
            'amazon_order_id', array_column(array_column($result, 'AdditionalInfo'), 'AmazonOrderID')
        )->get();
    }

    /**
     * @return 
     */
    public function getFailedAmazonOrdersAttribute()
    {
        if ((int) $this->status === 1) {
            return collect();
        }

        $response = json_decode($this->attributes['response'], true);
        $result = isset($response['Result']) ? $response['Result'] : [];

        return collect(
            array_column(array_column($result, 'AdditionalInfo'), 'AmazonOrderID')
        );
    }

    /**
     * @return array
     */
    public function getResponseAttribute()
    {
        try {
            return Arr::wrap(
                json_decode($this->attributes['response'] ?? '', true)
            );
        } catch (\Exception $e) {
            return [];            
        }
    }
}
