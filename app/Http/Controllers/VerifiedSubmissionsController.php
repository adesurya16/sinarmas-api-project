<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use File;
use Image;

// List model yang digunakan
use App\VerifiedSubmission;
use App\Profiles;

class VerifiedSubmissionsController extends Controller
{
    /**
    	created by : Faisal Triadi
    	created at : 5 September 2017
    	Desc Module : Module ini untuk mengelola pengajuan permintaan untuk menjadi user yang terverifikasi
    */

    /**
    	All Submissions User
    	Desc Function : Function ini untuk mengambil semua data permohonan verifikasi akun
    */
    public function all(Request $request){
    	$model = new VerifiedSubmission;
    	$data = $model::selectRaw("*, CONCAT('".url('/')."', self_image) as self_image, CONCAT('".url('/')."', image_id_card) as image_id_card")
                        ->with('profile');

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
            } else if ($trashedStatus == FALSE){ // Only Trashed
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
                    );

            return response()->json($response,404);
        }
    }

    /**
    	Insert Submissions User
    	Desc Function : Function ini untuk menyimpan data permintaan untuk menjadi user yang terverifikasi
    */
    public function insert(Request $request){
    	DB::beginTransaction();
        try {
	    	$current_user = $this->currentUser();
	        $current_user_id = $current_user->id;
	        $current_user_username = $current_user->username;

	        $current_user_profiles_id = $current_user->profile->id;

	        // Pengecekan jika user sudah pernah mengajukan
	        $submission = VerifiedSubmission::where('profile_id',$current_user_profiles_id)
	        								->first();

	        if($submission){
                if($request->has('name')){
                    $submission->fullname = $request->get('name');
                }

                if($request->has('birthday')){
                    $submission->birthday = $request->get('birthday');
                }

	        	if($request->hasFile('self_image')){
	        		// Untuk menyimpan gambar diri sendiri
				        $file = $request->file('self_image');
				        $dataImg = $file;
			            $t = microtime(true);
			            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
			            $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);

			            $ext_file = $dataImg->getClientOriginalExtension();
			            $mime_type = $dataImg->getMimeType();
			            $size_file = $dataImg->getSize();
			            $name_file = $timestamp.'_img_item.'.$ext_file;
			            $path_file = public_path().'/media/'.$current_user_username.'/images/submission/';

			            if (!file_exists($path_file)) {
			                File::makeDirectory( $path_file, 0777, true);
			            }

			            if($dataImg->move($path_file,$name_file)){
                            $images = '/public/media/'.$current_user_username.'/images/submission/'.$name_file;
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

			            	$submission->self_image = '/media/'.$current_user_username.'/images/submission/'.$timestamp.'_img_compress.'.$ext_file;
			            	$self_image = url('/').'/media/'.$current_user_username.'/images/submission/'.$timestamp.'_img_compress.'.$ext_file;
			            } else {
			            	// For rollback data if one data is error
			            	DB::rollBack();

			                $data = array(
			                    'status' => 0,
			                    'message' => 'Upload image diri sendiri gagal',
			                    'exception_error' => $file->getErrorMessage(),
			                );

			                return response()->json($data, 500);
			            }
	        	}

	        	if($request->hasFile('image_id_card')){
	        		// Untuk menyimpan data identitas diri
			            $file = $request->file('image_id_card');
				        $dataImg = $file;
			            $t = microtime(true);
			            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
			            $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);

			            $ext_file = $dataImg->getClientOriginalExtension();
			            $mime_type = $dataImg->getMimeType();
			            $size_file = $dataImg->getSize();
			            $name_file = $timestamp.'_img_item.'.$ext_file;
			            $path_file = public_path().'/media/'.$current_user_username.'/images/submission/';

			            if (!file_exists($path_file)) {
			                File::makeDirectory( $path_file, 0777, true);
			            }

			            if($dataImg->move($path_file,$name_file)){
                            $images = '/media/'.$current_user_username.'/images/submission/'.$name_file;
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

			            	$submission->image_id_card = '/media/'.$current_user_username.'/images/submission/'.$timestamp.'_img_compress.'.$ext_file;
			            	$image_id_card = url('/').'/media/'.$current_user_username.'/images/submission/'.$timestamp.'_img_compress.'.$ext_file;
			            } else {
			            	// For rollback data if one data is error
			            	DB::rollBack();

			                $data = array(
			                    'status' => 0,
			                    'message' => 'Upload image diri sendiri gagal',
			                    'exception_error' => $file->getErrorMessage(),
			                );

			                return response()->json($data, 500);
			            }
	        	}
                
	        	$submission->updated_by = $current_user_username;

	        	$submission->save();
	        } else {
		        $submission = new VerifiedSubmission;

		        $validationRules = [];
		        if(method_exists($submission,'validationInsertRules')){
		            $validationRules = $submission->validationInsertRules();
		        }

		        $validator = Validator::make($request->all(), $validationRules, $submission->messages());

                if($request->has('name')){
                    $submission->fullname = $request->get('name');
                }

                if($request->has('birthday')){
                    $submission->birthday = $request->get('birthday');
                }

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

		        $submission->profile_id = $current_user_profiles_id;
		        // Untuk menyimpan gambar diri sendiri
                    if($request->hasFile('self_image')){
    			        $file = $request->file('self_image');
    			        $dataImg = $file;
    		            $t = microtime(true);
    		            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
    		            $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);

    		            $ext_file = $dataImg->getClientOriginalExtension();
    		            $mime_type = $dataImg->getMimeType();
    		            $size_file = $dataImg->getSize();
    		            $name_file = $timestamp.'_img_item.'.$ext_file;
    		            $path_file = public_path().'/media/'.$current_user_username.'/images/submission/';

    		            if (!file_exists($path_file)) {
    		                File::makeDirectory( $path_file, 0777, true);
    		            }

    		            if($dataImg->move($path_file,$name_file)){
    		            	$submission->self_image = '/media/'.$current_user_username.'/images/submission/'.$name_file;
    		            	$self_image = url('/').'/media/'.$current_user_username.'/images/submission/'.$name_file;
    		            } else {
    		            	// For rollback data if one data is error
    		            	DB::rollBack();

    		                $data = array(
    		                    'status' => 0,
    		                    'message' => 'Upload image diri sendiri gagal',
    		                    'exception_error' => $file->getErrorMessage(),
    		                );

    		                return response()->json($data, 500);
    		            }
                    }

		        // Untuk menyimpan data identitas diri
                    if($request->hasFile('image_id_card')){
    		            $file = $request->file('image_id_card');
    			        $dataImg = $file;
    		            $t = microtime(true);
    		            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
    		            $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);

    		            $ext_file = $dataImg->getClientOriginalExtension();
    		            $mime_type = $dataImg->getMimeType();
    		            $size_file = $dataImg->getSize();
    		            $name_file = $timestamp.'_img_item.'.$ext_file;
    		            $path_file = public_path().'/media/'.$current_user_username.'/images/submission/';

    		            if (!file_exists($path_file)) {
    		                File::makeDirectory( $path_file, 0777, true);
    		            }

    		            if($dataImg->move($path_file,$name_file)){
    		            	$submission->image_id_card = '/media/'.$current_user_username.'/images/submission/'.$name_file;
    		            	$image_id_card = url('/').'/media/'.$current_user_username.'/images/submission/'.$name_file;
    		            } else {
    		            	// For rollback data if one data is error
    		            	DB::rollBack();

    		                $data = array(
    		                    'status' => 0,
    		                    'message' => 'Upload image diri sendiri gagal',
    		                    'exception_error' => $file->getErrorMessage(),
    		                );

    		                return response()->json($data, 500);
    		            }
                    }

		        $submission->created_by = $current_user_username;
		        $submission->updated_by = $current_user_username;

		        $submission->save();
		    }

	        db::commit();

	        $submission->self_image = $self_image;

	        $response = array(
                        'status' => 1,
                        'message' => 'Sukses menyimpan data',
                        'data' => $submission,
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
    	Set Verified Submissions User
    	Desc Function : Function ini untuk mengubah pengajuan user yang terverifikasi diterima atau ditolak
    */
    public function set_verified(Request $request, $id){
    	DB::beginTransaction();
        try {
        	$model = new VerifiedSubmission;

        	$validationRules = [];
	        if(method_exists($model,'validationInsertRules')){
	            $validationRules = $model->validationSetVerifiedRules();
	        }

	        $validator = Validator::make($request->all(), $validationRules, $model->messages());

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

        	$submission = $model::find($id);

        	if($request->get('filing_status') == 1){ // Jika diterima
        		// Update VerifiedSubmission
        		$submission->filing_status = 1;
        		$submission->updated_by = 'Admin';
        		$submission->save();

        		// Update profile menjadi verified
        		$profile = Profiles::find($submission->profile_id);
        		$profile->is_verified = TRUE;
        		$profile->updated_by = 'Admin';
        		$profile->save();
        	} else { // Jika ditolak
        		// Update VerifiedSubmission
        		$submission->filing_status = 2;
        		$submission->updated_by = 'Admin';
        		$submission->save();
        	}

	        $response = array(
                        'status' => 1,
                        'message' => 'Sukses menyimpan data',
                        'data' => $submission,
                    );

        	db::commit();
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
    	Re-submission Submissions User
    	Desc Function : Function ini untuk mengubah pengajuan user yang direject kembali ke 0 agar bisa mengajukan kembali
    */
    public function re_submission(Request $request){
    	DB::beginTransaction();
        try {
	    	$current_user = $this->currentUser();
	        $current_user_id = $current_user->id;
	        $current_user_username = $current_user->username;

	        $current_user_profiles_id = $current_user->profile->id;

	        $model = new VerifiedSubmission;

        	$submission = $model::where('profile_id', $current_user_profiles_id)
        							->delete();

    		$response = array(
                        'status' => 1,
                        'message' => 'Sukses menyimpan data',
                        'data' => $submission,
                    );

        	db::commit();
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
        Upload image id card
        Desc Function : Function ini untuk mengupload image id card
    */
    public function upload_image_id_card(Request $request){
        DB::beginTransaction();
        try {
            $current_user = $this->currentUser();
            $current_user_id = $current_user->id;
            $current_user_username = $current_user->username;

            $current_user_profiles_id = $current_user->profile->id;

            // Pengecekan jika user sudah pernah mengajukan
            $submission = VerifiedSubmission::where('profile_id',$current_user_profiles_id)
                                            ->first();

            if($submission){
                if($request->hasFile('image_id_card')){
                    // Untuk menyimpan data identitas diri
                        $file = $request->file('image_id_card');
                        $dataImg = $file;
                        $t = microtime(true);
                        $micro = sprintf("%06d",($t - floor($t)) * 1000000);
                        $timestamp = date('YmdHis'.$micro, $t)."_".rand(0, 1000);

                        $ext_file = $dataImg->getClientOriginalExtension();
                        $mime_type = $dataImg->getMimeType();
                        $size_file = $dataImg->getSize();
                        $name_file = $timestamp.'_img_item.'.$ext_file;
                        $path_file = public_path().'/media/'.$current_user_username.'/images/submission/';

                        if (!file_exists($path_file)) {
                            File::makeDirectory( $path_file, 0777, true);
                        }

                        if($dataImg->move($path_file,$name_file)){
                            $images = '/media/'.$current_user_username.'/images/submission/'.$name_file;
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

                            $submission->image_id_card = '/media/'.$current_user_username.'/images/submission/'.$timestamp.'_img_compress.'.$ext_file;
                            $image_id_card = url('/').'/media/'.$current_user_username.'/images/submission/'.$timestamp.'_img_compress.'.$ext_file;
                        } else {
                            // For rollback data if one data is error
                            DB::rollBack();

                            $data = array(
                                'status' => 0,
                                'message' => 'Upload image diri sendiri gagal',
                                'exception_error' => $file->getErrorMessage(),
                            );

                            return response()->json($data, 500);
                        }
                }

                $submission->updated_by = $current_user_username;

                $submission->save();
            }

            db::commit();
            
            $submission->image_id_card = $image_id_card;

            $response = array(
                        'status' => 1,
                        'message' => 'Sukses menyimpan data',
                        'data' => $submission,
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
}
