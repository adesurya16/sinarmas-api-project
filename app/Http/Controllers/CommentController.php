<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use File;

// List model yang digunakan
use App\Comment;
use App\Profiles;

class CommentController extends Controller
{
    //
    public function insert(Request $request) {
        DB::beginTransaction();
        try {
            $current_user = $this->currentUser();
            $current_user_id = $current_user->id;

            $current_user_username = $current_user->username;
            $current_user_profiles_id = $current_user->profile->id;

            $comment = new Comment;
            $comment->id_user = $current_user_id;
            $comment->id_activity = $request->id_activity;
            $comment->comment = $request->comment;

            $comment->save();

            $response = array(
                'status' => 1,
                'message' => 'Sukses menyimpan data',
                'data' => $comment,
            );

            db::commit();
            return response()->json($response,200);
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

            $data = array(
                'status' => 0,
                'message' => 'Terjadi kesalahan',
                'exception_error' => $ex->getMessage(),
            );

            return response()->json($data, 500);
        }
    }

    public function get_comment(Request $request, $id_activity){
        $model = Comment::where('id_activity',$id_activity);
        $comments = [];

        $count = $model->count();
        if($request->has('limit')){
            $model->take($request->get('limit'));

            if($request->has('offset')){
                $model->skip($request->get('offset'));
            }
        }

        $comment_init = $model->get();
        foreach ($comment_init as $comment) {
            $profile = Profiles::where('user_id', $comment->id_user)->first();
            $comment->fullname = $profile->fullname;
            $comment->avatar = url('/').$profile->avatar;
            array_push($comments, $comment);
        }


        if($count > 0){
            $response = array(
                'status' => 1,
                'message' => 'Sukses',
                'total_rows' => $count,
                'data' => $comments,
            );

            return response()->json($response,200);
        } else {
            $response = array(
                'status' => 1,
                'message' => 'Tidak ada data',
                'total_rows' => 0,
                'data' => [],
            );

            return response()->json($response,200);
        }
    }
}
