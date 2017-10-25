<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rules (){
        return [
            'email' => 'required|max:25',
            'username' => 'required|max:25',
            'password' => 'required'
        ];
    }

    public function validationInsertRules (){
        return [
            'email' => 'required|max:25',
            'username' => 'required|max:25',
            'password' => 'required'
        ];
    }

    public function validationUpdateRules(){
        return [
            'email' => 'required|string|email|max:25|unique:users',
            'username' => 'required|string|username|max:25|unique:users'
        ];
    }

    public function validationLogin(){
        return [
            'login' => 'required',
            'password' => 'required',
        ];
    }

    public function profile(){
        return $this->hasOne('App\Profiles');
    }
}
