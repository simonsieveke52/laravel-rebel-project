<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuoteEmail extends Model
{
    protected $guarded = [];
    public function quote(){
        return $this->belongsTo(Quote::class);
    }

    public function getSentAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }
    public function getExpiresAtAttribute($value){
        return Carbon::parse($value)->diffForHumans();
    }
}
