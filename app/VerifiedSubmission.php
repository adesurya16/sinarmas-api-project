<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifiedSubmission extends Model
{
    use SoftDeletes;

    public function rules (){
        return [
            'self_image' => 'required|mimes:jpg,jpeg,png',
            'image_id_card' => 'required|mimes:jpg,jpeg,png',
        ];
    }

    public function validationInsertRules (){
        return [
            'name' => 'required',
            'birthday' => 'required',
            // 'self_image' => 'required|mimes:jpg,jpeg,png',
            // 'image_id_card' => 'required|mimes:jpg,jpeg,png',
        ];
    }

    public function validationSetVerifiedRules (){
        return [
            'filing_status' => 'required',
        ];
    }

    public function messages()
	{
	    return [
            'name.required' => 'Anda tidak menyertakan nama anda',
            'birthday.required' => 'Anda tidak menyertakan tanggal lahir anda',
            'birthday.date_format' => 'Format tanggal yang anda masukan tidak sesuai',
	        // 'self_image.required' => 'Anda tidak menyertakan foto diri anda',
	        // 'image_id_card.required' => 'Anda tidak menyertakan foto KTP',
	        'filing_status.required' => 'Anda belum memilih user ini diterima atau ditolak',
	    ];
	}

    public function profile(){
        return $this->hasOne('App\Profiles', 'id', 'profile_id');
    }
}
