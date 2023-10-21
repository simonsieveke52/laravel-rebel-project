<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmazonCronHistory extends Model
{
	/**
	 * @var array
	 */
    protected $dates = [
    	'processed_at'
    ];

    /**
     * @return AmazonCronHistory
     */
    public function markAsProcessed(int $totalProcessed = 0)
    {
    	$this->processed_at = now();
    	$this->processed = true;
    	$this->total_processed = $totalProcessed;
    	$this->save();
    	return $this;
    }
}
