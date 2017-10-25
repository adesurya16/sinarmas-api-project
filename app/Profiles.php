<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Profiles extends Model
{
    use SoftDeletes;

    public function rules (){
        return [
            'fullname' => 'required|max:25'
        ];
    }

    public function validationInsertRules (){
        return [
            'fullname' => 'required|max:25'
        ];
    }

    public function posting(){
        return $this->hasMany('App\PostingActivities');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function submission(){
        return $this->hasOne(VerifiedSubmission::class, 'profile_id', 'id')
                    ->selectRaw("*, CONCAT('".url('/')."', self_image) as self_image, CONCAT('".url('/')."', image_id_card) as image_id_card");
    }
}
