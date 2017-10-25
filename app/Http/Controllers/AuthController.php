<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use JWTAuth;
use Illuminate\Support\Facades\Validator;

// List model yang digunakan
use App\User;
use App\Profiles;

class AuthController extends Controller
{
    /**
        Register
        Desc Function : Function untuk register user
    */
    public function register(Request $request){
    	DB::beginTransaction();
    	try {
    		$model = new User;

    		$validationRules = [];
            if(method_exists($model,'validationInsertRules')){
                $validationRules = $model->validationInsertRules();
            }

            $validator = Validator::make($request->all(), $validationRules);

            if($validator->fails()) {
            	$data = array(
					'status' => 0,
					'message' => $validator->errors()->all(),
				);

                return response()->json($data,400);
            }

            $model->name = $request->get('fullname');
            $model->email = $request->get('email');
            $model->username = $request->get('username');
            $model->password = Hash::make($request->get('password'));
            $model->created_by = $request->get('username');
            $model->updated_by = $request->get('username');

            $model->save();

            $profile = new Profiles;

            $validationRules = [];
            if(method_exists($profile,'validationInsertRules')){
                $validationRules = $profile->validationInsertRules();
            }

            $validator = Validator::make($request->all(), $validationRules);

            if($validator->fails()) {
                $data = array(
                    'status' => 0,
                    'message' => $validator->errors()->all(),
                );

                return response()->json($data,400);
            }

            $profile->user_id = $model->id;
            $profile->fullname = $request->get('fullname');
            $profile->created_by = $request->get('username');
            $profile->updated_by = $request->get('username');

            $profile->save();

            DB::commit();

            $auto_login = TRUE;
            if($auto_login){
                $req_login = array(
                        'login' => $model->username,
                        'password' => $request->get('password')
                    );
                $request_login = Request::create( 'request_login', 'POST', $req_login);
                $status_login = $this->login($request_login);

                return $status_login;
            } else {
                $data = array(
    				'status' => 1,
    				'message' => 'Data berhasil disimpan',
    				'data' => $model
    			);

    			return response()->json($data,200);
            }

    	} catch (Exception $ex) {
    		// For rollback data if one data is error
			DB::rollBack();

			$data = array(
				'status' => 0,
				'message' => 'Terjadi kesalahan',
                'exception_error' => $ex->getMessage(),
			);

			return response()->json($data, 500);
    	} catch (\Illuminate\Database\QueryException $ex) {
			// For rollback data if one data is error
			DB::rollBack();

            if($ex->getCode() == 23000){
                $message = 'Username / Email telah digunakan';
            } else {
                $message = 'Terjadi kesalahan';
            }

			$data = array(
				'status' => 0,
                'message' => $message,
				'exception_error' => $ex->getMessage(),
			);

			return response()->json($data, 500);
		}
    }

    /**
        * Login
        * Desc Function : Function untuk user login jika sudah register
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
    */
    public function login (Request $request){
        $model = new User;

        $validationRules = [];
        if(method_exists($model,'validationLogin')){
            $validationRules = $model->validationLogin();
        }

        $validator = Validator::make($request->only('login', 'password'), $validationRules);
        // $validator = Validator::make($request->all(), $validationRules);

        if($validator->fails()) {
            $data = array(
                'status' => 0,
                'message' => $validator->errors()->all(),
                'http_code' => 500
            );

            return response()->json($data,200);
        }

        $user = $model::where('username', $request->get('login'))
                        ->orwhere('email', $request->get('login'))
                        ->with('profile')
                        ->first();
        
        if($user){
            if (Hash::check($request->get('password'), $user->password)) {
                $token = JWTAuth::fromUser($user);

                $data = array(
                            'status' => 1,
                            'message' => 'Login sukses',
                            'token' => $token,
                            'data' => $user,
                        );

                return response()->json($data,200);
            } else {
                $data = array(
                            'status' => 0,
                            'message' => 'Login gagal, password tidak cocok dengan username / login',
                            'http_code' => 401
                        );

                return response()->json($data,200);
            }
        } else {
            $data = array(
                        'status' => 0,
                        'message' => 'Username / Email tidak terdaftar',
                        'http_code' => 404
                    );

            return response()->json($data,200);
        }
        return $data;
    }
}
