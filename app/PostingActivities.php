<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostingActivities extends Model
{
    use SoftDeletes;

    public function rules (){
        return [
            'tree_id' => 'required',
            'event_id' => 'required',
            'caption' => 'required',
            'location' => 'required',
        ];
    }

    public function validationInsertRules (){
        return [
            'tree_id' => 'required',
            'event_id' => 'required',
            // 'caption' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'date_plant' => 'required',
        ];
    }

    public function messages()
	{
	    return [
	        'tree_id.required' => 'Silahkan pilih jenis pohon',
	        'event_id.required' => 'Silahkan pilih event',
	        // 'caption.required' => 'Silahkan masukan caption',
            'location.required' => 'Silahkan masukan lokasi',
            'lat.required' => 'Silahkan masukan peta lokasi',
            'lng.required' => 'Silahkan masukan peta lokasi',
	        'date_plant.required' => 'Silahkan masukan tanggal penanaman',
	    ];
	}

	public function profile(){
        return $this->belongsTo('App\Profiles', 'profiles_id', 'id');
    }

    public function event(){
        return $this->belongsTo('App\Events');
    }

    public function tree(){
        return $this->belongsTo('App\TreeSpecies');
    }

    public function images(){
        return $this->hasMany('App\Images');
    }
}
