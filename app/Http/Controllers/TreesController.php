<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use File;

// List model yang digunakan
use App\TreeSpecies;

class TreesController extends Controller
{
    /**
        All Trees
        Desc Function : Menampilkan semua data pohon
    */
    public function all(Request $request){
    	$model = new TreeSpecies;
    	$data = $model::select();

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
}
