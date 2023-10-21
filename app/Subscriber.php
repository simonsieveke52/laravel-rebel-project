<?php

namespace App;

use App\Model;
use App\Discount;
use App\Repositories\MailchimpRepository;

class Subscriber extends Model
{

    /**
     * Fillable attributes
     *
     * @var Array
     */
	protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'ip_address',
        'status',
        'subscribe',
        'member_id',
        'discount_id',
        'origin_id',
    ];

    /**
     * Mark the subscriber as subscribed to the newsletter
     *
     * @param  StdObject $member
     * @return boolean $result
     */
    public function markAsSubscribed($member)
    {
        return $this->update([
            'status'    => 'subscribed',
            'member_id' => $member->id
        ]);
    }

    /**
     * Get the one time usage discount associated with the subscriber
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Get the origination of the subscriber
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function origin()
    {
        return $this->belongsTo(OrderOrigin::class, 'origin_id');
    }
}
