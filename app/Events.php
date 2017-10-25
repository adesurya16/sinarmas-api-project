<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'participants',
    ];

    public function validationInsertRules (){
        return [
            'event_name' => 'required',
            'event_date' => 'required|date_format:Y-m-d',
            'location' => 'required|max:255',
            'event_time' => 'required|date_format:H:i:s',
            'sponsor' => 'required',
        ];
    }

    public function rules (){
        return [
            'event_name' => 'required|max:255',
            'event_date' => 'required|date_format:Y-m-d',
            'location' => 'required|max:255',
            'event_time' => 'required|date_format:H:i:s',
            'sponsor' => 'required',
        ];
    }

    public function messages()
	{
	    return [
            'event_name.required' => 'Silahkan masukan nama event',
            'event_date.required' => 'Silahkan masukan tanggal event',
            'event_date.date_format' => 'Format tanggal yang anda masukan tidak sesuai',
            'location.required' => 'Silahkan masukan nama lokasi event',
            'location.max' => 'Jumlah karakter melebihi batas',
            'event_time.required' => 'Silahkan masukan Jam event',
            'event_time.date_format' => 'Format jam yang anda masukan tidak sesuai',
	        'sponsor.required' => 'Silahkan pilih sponsor',
	    ];
	}

    public function event_sponsor(){
        return $this->hasMany(EventSponsor::class, 'event_id', 'id');
    }

    public function total_participants(){
        return $this->hasOne(ParticipantsEvent::class, 'event_id', 'id')
                    ->selectRaw('event_id, COUNT(*) as total_participants')
                    ->groupBy('event_id');
    }

    public function events(){
        return $this->hasMany(Events::class, 'event_date', 'event_date')
                        ->with('event_sponsor.sponsors')
                        ->with('total_participants')
                        ->orderBy('event_time', 'desc');
    }
}
