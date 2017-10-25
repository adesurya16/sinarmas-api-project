<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponsor extends Model
{
    use SoftDeletes;

    public function rules (){
        return [
            'sponsors_name' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ];
    }

    public function validationInsertRules (){
        return [
            'sponsors_name' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ];
    }

    protected $hidden = [
        'remember_token',
    ];

    public function messages()
	{
	    return [
	        'sponsors_name.required' => 'Anda tidak menyertakan nama sponsor',
	        'desc.required' => 'Anda tidak menyertakan keterangan sponsor',
	        'image.required' => 'Anda tidak menyertakan gambar',
	    ];
    }
    
}
