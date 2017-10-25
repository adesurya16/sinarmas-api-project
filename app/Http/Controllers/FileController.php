<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Image;

class FileController extends Controller
{
    //
    public function uploadImage(Request $request) {
        $current_user = $this->currentUser();
        $current_user_id = $current_user->id;
        $current_user_username = $current_user->username;

        $current_profile = $current_user->profile;
        $current_profile_id = $current_profile->id;

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


            $image_path = '/media/'.$current_user_username.'/images/posting/'.$timestamp.'_img_compress.'.$ext_file;

            $data = array(
                'file_name' => $image_path
            );

            $response = array(
                'status' => 1,
                'message' => 'Sukses menyimpan data',
                'data' => $data,
            );

            return response()->json($response,200);

        } else {
            $response = array(
                'status' => 0,
                'message' => 'Gagal menyimpan image',
            );

            return response()->json($response,500);
        }
    }
}
