<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSponsor extends Model
{
    use SoftDeletes;

    public function sponsors(){
        return $this->hasOne(Sponsor::class, 'id', 'sponsor_id')
        			->selectRaw("*, CONCAT('".url('/')."', image) as image");
    }
}
