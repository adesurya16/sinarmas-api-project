<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    use SoftDeletes;

    public function validationInsertRules (){
        return [
            'image' => 'required|mimes:jpg,jpeg,png',
        ];
    }

    public function messages()
	{
	    return [
	        'image.required' => 'Silahkan masukan gambar',
	    ];
	}
}
