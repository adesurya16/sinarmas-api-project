<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use File;
use Image;

// List model yang digunakan
use App\PostingActivities;
use App\Events;
use App\Images;
use App\Comment;

class PostingActivitiesController extends Controller
{
    /**
        Posting Activities
        Desc Function : Posting Activities dari user yang telah login
    */
    public function insert(Request $request){
    	DB::beginTransaction();
        try {
        	$current_user = $this->currentUser();
            $current_user_id = $current_user->id;
            $current_user_username = $current_user->username;

            $current_profile = $current_user->profile;
            $current_profile_id = $current_profile->id;            

            // Posting Insert
            $posting = new PostingActivities;

            $validationRules = [];
            if(method_exists($posting,'validationInsertRules')){
                $validationRules = $posting->validationInsertRules();
            }

            $validator = Validator::make($request->all(), $validationRules, $posting->messages());

            if($validator->fails()) {
            	// For rollback data if one data is error
            	DB::rollBack();

            	$data = array(
					'status' => 0,
					'message' => $validator->errors()->all(),
                    'http_code' => 500
				);

                return response()->json($data,200);
            }

            $posting->tree_id = $request->get('tree_id');
            $posting->profiles_id = $current_profile_id;
            $posting->event_id = $request->get('event_id');

            if($request->has('caption')){
                $posting->caption = $request->get('caption');
            }

            $posting->location = $request->get('location');
            $posting->lat = $request->get('lat');
            $posting->lng = $request->get('lng');
            $posting->date_plant = $request->get('date_plant');
            $posting->created_by = $current_user_username;
            $posting->updated_by = $current_user_username;

            $posting->save();

            // Image Insert
            $image = new Images;

            $validationRules = [];
            if(method_exists($image,'validationInsertRules')){
                $validationRules = $image->validationInsertRules();
            }

            $validator = Validator::make($request->all(), $validationRules, $image->messages());

            if($validator->fails()) {
            	// For rollback data if one data is error
            	DB::rollBack();
            	
            	$data = array(
					'status' => 0,
					'message' => $validator->errors()->all(),
                    'http_code' => 400
				);

                return response()->json($data,200);
            }

            $file = $request->file('image');

            $dataImg = $file;
            $t = microtime(true);
            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
            $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);

            $ext_file = $dataImg->getClientOriginalExtension();
            $mime_type = $dataImg->getMimeType();
            $size_file = $dataImg->getSize();
            $name_file = $timestamp.'_img_item.'.$ext_file;
            $path_file = public_path().'/media/'.$current_user_username.'/images/posting/';

            if (!file_exists($path_file)) {
                File::makeDirectory( $path_file, 0777, true);
            }

            if($dataImg->move($path_file,$name_file)){
                $images = '/media/'.$current_user_username.'/images/posting/'.$name_file;
                $jpg  = Image::make(asset($images));
                if($size_file > 10000000){
                    $jpg->save($path_file.$timestamp.'_img_compress.'.$ext_file, 10);
                } else if($size_file > 5000000){
                    $jpg->save($path_file.$timestamp.'_img_compress.'.$ext_file, 50);
                } else if($size_file > 3000000){
                    $jpg->save($path_file.$timestamp.'_img_compress.'.$ext_file, 70);
                } else {
                    $jpg->save($path_file.$timestamp.'_img_compress.'.$ext_file);
                }
                
                $imgPathOri = $path_file.$name_file;

                $image->image_name = $timestamp.'_img_compress.'.$ext_file;
                $image->posting_activities_id = $posting->id;
                $image->mime = $mime_type;
                $image->path = '/media/'.$current_user_username.'/images/posting/'.$timestamp.'_img_compress.'.$ext_file;
                $image->created_by = $current_user_username;
                $image->updated_by = $current_user_username;

                $image->save();
            } else {
            	// For rollback data if one data is error
            	DB::rollBack();

                $data = array(
                    'status' => 0,
                    'message' => 'Upload image gagal',
                    'exception_error' => $file->getErrorMessage(),
                );

                return response()->json($data, 500);
            }

            db::commit();

            $data = PostingActivities::where('id', $posting->id)
            							->with('event')
            							->with('tree')
            							->with('images')
            							->with('profile')
            							->first();

            foreach ($data->images as $key => $image) {
            	$image->path = url('/').$image->path;
            }

            $response = array(
                        'status' => 1,
                        'message' => 'Sukses menyimpan data',
                        'data' => $data,
                    );

            return response()->json($response,200);

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
        All Activities
        Desc Function : Menampilkan activities user 
    */
    public function all(Request $request){
    	$current_user = $this->currentUser();
    	if($current_user != 'unregistered'){
	        $current_profile = $current_user->profile;
	        $current_profile_id = $current_profile->id;
    	}

    	$model = new PostingActivities;
        $data = $model::select()
        				->with('event')
						->with('tree')
						->with('images')
						->with('profile');

		// if($current_user != 'unregistered'){
		// 	$data->where('profiles_id', $current_profile_id);
		// }

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
            $data->orderBy('created_at', 'desc');
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

        $datas = $data->get();

        foreach ($datas as $key => $data) {
	        foreach ($data->images as $key => $image) {
	        	$image->path = url('/').$image->path;
	        }
		}

		$out_data = [];
        foreach ($datas as $data) {
            $comment = Comment::where('id_activity',$data->id)->get();
            $data->comment_count = count($comment);
            array_push($out_data, $data);
        }

        if($count > 0){
            $response = array(
                        'status' => 1,
                        'message' => 'Sukses',
                        'total_rows' => $count,
                        'data' => $out_data,
                    );

            return response()->json($response,200);
        } else {
            $response = array(
                        'status' => 0,
                        'message' => 'Tidak ada data',
                        'total_rows' => 0,
                        'data' => [],
                    );

            return response()->json($response,404);
        }
    }

    /**
        Detail Activities
        Desc Function : Menampilkan detail activities user 
    */
    public function detail(Request $request, $id){
    	$current_user = $this->currentUser();

        if($current_user != 'unregistered'){
            $current_profile = $current_user->profile;
            $current_profile_id = $current_profile->id;
        }

    	$model = new PostingActivities;
        $data = $model::select()
        				->where('id', $id)
        				->with('event')
						->with('tree')
						->with('images')
						->with('profile');

        if($current_user != 'unregistered'){
            $data->where('profiles_id', $current_profile_id);
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

        $data = $data->first();
        
        foreach ($data->images as $key => $image) {
        	$image->path = url('/').$image->path;
        }

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
                        'status' => 1,
                        'message' => 'Tidak ada data',
                        'total_rows' => 0,
                        'data' => [],
                        'http_code' => 404
                    );

            return response()->json($response,200);
        }
    }
}
