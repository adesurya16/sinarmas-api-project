<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use File;

//controller lain
// use app/Http/Controllers/Admin/UserController

// List model yang digunakan
use App\Profiles;
use App\User;
use Hash;

class ProfilesController extends Controller
{
    /**
        All Profiles
        Desc Function : Menampilkan semua profile user
    */
    public function all(Request $request){
        $model = new Profiles;
        $data = $model::select()
                        ->with(['posting' => function($query) use ($request){
                            if($request->has('limit')){
                                $query->take($request->get('limit'));

                                if($request->has('offset')){
                                    $query->skip($request->get('offset'));
                                }
                            }

                            $query->with('images')
                                  ->with('event')
                                  ->with('tree');

                            $query->orderBy('created_at', 'desc');
                        }])
                        ->with('user')
                        ->with('submission');

        // check if in parameter they have start_date for filtering
        if($request->has('start_date')){
            $data->where('updated_at', '>=', $request->get('start_date'));
        }

        // check if in parameter they have end_date for filtering
        if($request->has('end_date')){
            $data->where('updated_at', '<=', $request->get('end_date'));
        }

        // check if in parameter they have order_type and order_by for sorting
        if($request->has('order_type')){
            if($request->get('order_type') == 'asc'){
                if($request->has('order_by')){
                    $data->orderBy($request->get('order_by'));
                }else{
                    $data->orderBy('updated_at');
                }
            }else{
                if($request->has('order_by')){
                    $data->orderBy($request->get('order_by'));
                }else{
                    $data->orderBy('updated_at', 'desc');
                }
            }
        }else{
            $data->orderBy('updated_at', 'desc');
        }

        if ($request->has('trashed')){
            $trashedStatus = $request->get('trashed');
            if ($trashedStatus == TRUE){ // With Trashed
                $data->withTrashed();
            }
            else if ($trashedStatus == FALSE){ // Only Trashed
                $data->onlyTrashed();
            }
        }

        // check if in parameter they have search for search
        if($request->has('search')){
            $keyword = $request->get("search");
            $metadata = $model->getAllColumnsNames();
            $data->SearchByKeyword($keyword, $metadata);
        }

        $count = $data->count();

        if($request->has('limit')){
            $data->take($request->get('limit'));

            if($request->has('offset')){
                $data->skip($request->get('offset'));
            }
        }

        $data = $data->get();

        if($count > 0){
            $response = array(
                        'status' => 1,
                        'message' => 'Sukses',
                        'total_rows' => $count,
                        'data' => $data,
                    );

            return response()->json($response,200);
        } else {
            $response = array(
                        'status' => 0,
                        'message' => 'Tidak ada data',
                        'total_rows' => 0,
                        'data' => [],
                        'http_code' => 404
                    );

            return response()->json($response,200);
        }
    }

    /**
        My Profile
        Desc Function : Menampilkan informasi profile yang sedang login
    */
    public function MYdetail(Request $request){
        $current_user = $this->currentUser();
        $current_user_id = $current_user->id;

        $data = Profiles::where('user_id', $current_user_id)
                            ->with(['posting' => function($query) use ($request){
                                if($request->has('limit')){
                                    $query->take($request->get('limit'));

                                    if($request->has('offset')){
                                        $query->skip($request->get('offset'));
                                    }
                                }
                                $query->orderBy('created_at', 'desc');
                                $query->with('images')
                                      ->with('event')
                                      ->with('tree');
                            }])
                            ->with('user')
                            ->with('submission')
                            ->first();

        $data->avatar = url('/').$data->avatar;

        foreach ($data->posting as $key => $posting) {
            foreach ($posting->images as $key => $image) {
                $image->path = url('/').$image->path;
            }
        }

        $data->planted_trees = $data->posting->count();

        if($data){
            $response = array(
                        'status' => 1,
                        'message' => 'Sukses',
                        'total_rows' => 1,
                        'data' => $data,
                    );

            return response()->json($response,200);
        } else {
            $response = array(
                        'status' => 0,
                        'message' => 'Tidak ada data',
                        'total_rows' => 0,
                        'data' => new \stdClass(),
                        'http_code' => 404
                    );

            return response()->json($response,200);
        }
    }

    /**
        My Update
        Desc Function : Mengganti informasi profile user yang sedang login
    */
    public function MYupdate(Request $request){
        DB::beginTransaction();
        try {
            $current_user = $this->currentUser();
            $current_user_id = $current_user->id;
            $current_user_username = $current_user->username;

            $data = Profiles::where('user_id', $current_user_id)
                                ->first();
            $datauser = User::where('id',$current_user_id)
                                ->first();
            if($data){
                // validation request
                
                // $validationRules = [];
                // $model = new User;
                // if(method_exists($model,'validationUpdateRule')){
                //     $validationRules = $model->validationInsertRules();
                // }
    
                // $validator = Validator::make($request->all(), $validationRules);
    
                // if($validator->fails()) {
                //     $data = array(
                //         'status' => 0,
                //         'message' => $validator->errors()->all(),
                //     );
                //     return response()->json($data,400);
                // }

                if($request->has('fullname')){
                  $data->fullname = $request->get('fullname');
                }

                if($request->has('username')){
                  $datauser->username = $request->get('username');                  
                }

                if($request->has('password')){
                  $datauser->password = Hash::make($request->get('password'));
                }

                if($request->has('email')){
                  $datauser->email = $request->get('email');
                }

                if($request->has('birthday')){
                    $data->birthday = $request->get('birthday');
                }

                if($request->has('phone_number')){
                    $data->phone_number = $request->get('phone_number');
                }

                if($request->hasFile('avatar')){
                    $file = $request->file('avatar');

                    $dataImg = $file;
                    $t = microtime(true);
                    $micro = sprintf("%06d",($t - floor($t)) * 1000000);
                    $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);

                    $ext_file = $dataImg->getClientOriginalExtension();
                    $mime_type = $dataImg->getMimeType();
                    $size_file = $dataImg->getSize();
                    $name_file = $timestamp.'_img_item.'.$ext_file;
                    $path_file = public_path().'/media/'.$current_user_username.'/images/avatar/';

                    if (!file_exists($path_file)) {
                        File::makeDirectory( $path_file, 0777, true);
                    }

                    if($dataImg->move($path_file,$name_file)){
                        $imgPathOri = $path_file.$name_file;

                        $data->avatar = '/media/'.$current_user_username.'/images/avatar/'.$name_file;

                        $path = url('/').'/media/'.$current_user_username.'/images/avatar/'.$name_file;
                    } else {
                        $data = array(
                            'status' => 0,
                            'message' => 'Upload image gagal',
                            'exception_error' => $file->getErrorMessage(),
                            'http_code' => 500
                        );

                        return response()->json($data, 200);
                    }
                }

                if($request->has('bio')){
                    $data->bio = $request->get('bio');
                }

                $data->updated_by = $current_user_username;
                $data->save();
                $datauser->save();

                if(isset($name_file)){
                    $data->avatar = url('/').'/media/'.$current_user_username.'/images/avatar/'.$name_file;
                } else {
                    $data->avatar = url('/').$data->avatar;
                }

                db::commit();

                $response = array(
                            'status' => 1,
                            'message' => 'Sukses mengubah data',
                            'dataProfiles' => $data,
                            'dataUsers' => $datauser
                        );

                return response()->json($response,200);
            } else {
                $response = array(
                            'status' => 0,
                            'message' => 'Tidak ada data',
                            'total_rows' => 0,
                            'data' => new \stdClass(),
                            'http_code' => 404
                        );

                return response()->json($response,200);
            }
        } catch (Exception $e) {
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

            $data = array(
                'status' => 0,
                'message' => 'Terjadi kesalahan',
                'exception_error' => $ex->getMessage(),
            );

            return response()->json($data, 500);
        }
    }

    /**
        Detail Profile
        Desc Function : Menampilkan detail profile user
    */
    public function detail(Request $request, $id){

        $data = Profiles::where('user_id', $id)
                            ->with(['posting' => function($query) use ($request){
                                if($request->has('limit')){
                                    $query->take($request->get('limit'));

                                    if($request->has('offset')){
                                        $query->skip($request->get('offset'));
                                    }
                                }

                                $query->with('images')
                                      ->with('event')
                                      ->with('tree');

                                $query->orderBy('created_at', 'desc');
                            }])
                            ->with('user')
                            ->with('submission')
                            ->first();

        if(!is_null($data->avatar)){
            $data->avatar = url('/').$data->avatar;
        }

        foreach ($data->posting as $key => $posting) {
            foreach ($posting->images as $key => $image) {
                $image->path = url('/').$image->path;
            }
        }

        $data->planted_trees = $data->posting->count();

        if($data){
            $response = array(
                        'status' => 1,
                        'message' => 'Sukses',
                        'total_rows' => 1,
                        'data' => $data,
                    );

            return response()->json($response,200);
        } else {
            $response = array(
                        'status' => 0,
                        'message' => 'Tidak ada data',
                        'total_rows' => 0,
                        'data' => new \stdClass(),
                        'http_code' => 404
                    );

            return response()->json($response,200);
        }
    }

    /*
      Mendapatkan data profile user dari keyword username
    */
    public function getUserProfile(Request $request){
        $username = $request->username;
        $user = User::where('username',$username)->first();
        $data = Profiles::where('user_id', $user->id)
                            ->first();
        if($data){
          $res = array(
              "user_id" => $user->id,
              "username" => $username,
              "email" => $user->email,
              "avatar" => $data->avatar,
            );
          $response = array(
                      'status' => 1,
                      'message' => 'Sukses mendapatkan data',
                      'data' => $res,
                  );
          return response()->json($response,200);
        }else{
          $response = array(
                      'status' => 0,
                      'message' => 'Tidak ada data',
                      'total_rows' => 0,
                      'data' => new \stdClass(),
                      'http_code' => 404
                  );

          return response()->json($response,200);
        }
    }

    // public function updateProfile(Request $request){
    //     DB::beginTransaction();
    //     try{
    //       $current_user = $this->currentUser();
    //       $current_user_id = $current_user->id;
    //       $current_user_username = $current_user->username;
    //
    //       $data = Profiles::where('user_id', $current_user_id)
    //                           ->first();
    //
    //       if($data){
    //           if($request->has('username')){
    //               // $current_user->firstname = $request->get('firstname');
    //           }
    //           if($request->has('password')){
    //               $current_user->password = $request->get('password');
    //           }
    //           if($request->has('email')){
    //               $current_user->email = $request->get('email');
    //           }
    //
    //           if($request->hasFile('avatar')){
    //               $file = $request->file('avatar');
    //
    //               $dataImg = $file;
    //               $t = microtime(true);
    //               $micro = sprintf("%06d",($t - floor($t)) * 1000000);
    //               $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);
    //
    //               $ext_file = $dataImg->getClientOriginalExtension();
    //               $mime_type = $dataImg->getMimeType();
    //               $size_file = $dataImg->getSize();
    //               $name_file = $timestamp.'_img_item.'.$ext_file;
    //               $path_file = public_path().'/media/'.$current_user_username.'/images/avatar/';
    //
    //               if (!file_exists($path_file)) {
    //                   File::makeDirectory( $path_file, 0777, true);
    //               }
    //
    //               if($dataImg->move($path_file,$name_file)){
    //                   $imgPathOri = $path_file.$name_file;
    //
    //                   $data->avatar = '/media/'.$current_user_username.'/images/avatar/'.$name_file;
    //
    //                   $path = url('/').'/media/'.$current_user_username.'/images/avatar/'.$name_file;
    //               } else {
    //                   $data = array(
    //                       'status' => 0,
    //                       'message' => 'Upload image gagal',
    //                       'exception_error' => $file->getErrorMessage(),
    //                       'http_code' => 500
    //                   );
    //
    //                   /* yang di return itu data atau current user */
    //                   return response()->json($data, 200);
    //               }
    //           }
    //     }catch(Exception $e){
    //       // For rollback data if one data is error
    //       DB::rollBack();
    //
    //       $data = array(
    //           'status' => 0,
    //           'message' => 'Terjadi kesalahan',
    //           'exception_error' => $ex->getMessage(),
    //       );
    //
    //       return response()->json($data, 500);
    //     }
    // }

}
