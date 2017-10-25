<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use JWTAuth;
use App\Profiles;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function currentUser() {
    	try {
    		$user = JWTAuth::parseToken()->authenticate();
	    	$user->profile = Profiles::where('user_id', $user->id)->first();
	        return $user;
    	} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    		if($e->getMessage() == 'Token has expired'){
                abort(403, $e->getMessage());
            } else {
                return 'unregistered';
            }
    	}
    }
}
