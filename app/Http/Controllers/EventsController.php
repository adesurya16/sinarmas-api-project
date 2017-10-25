<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use File;

// List model yang digunakan
use App\Events;
use App\EventSponsor;
use App\ParticipantsEvent;
use App\Sponsor;
use App\SeedEvent;

class EventsController extends Controller
{
    /**
        created by : Faisal Triadi
        created at : 6 September 2017
        Desc Module : Module ini untuk mengelola events baik yang disetujui ataupun tidak
    */

    /**
        All Events
        Desc Function : Function ini untuk mengambil semua / filter data events
    */
    public function all(Request $request){
    	$model = new Events;
    	$data = $model::select()
                        ->with('event_sponsor.sponsors')
                        ->with('total_participants');

        // filtering data untuk events yang sudah di approve atau belum
        if($request->has('is_approved')){
            if($request->get('is_approved') == 1){
                $data->where('filing_status', TRUE);
            } else {
                $data->where('filing_status', FALSE);
            }
        }

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
                    );

            return response()->json($response,404);
        }
    }

    /**
     *  List of no sponsor Event
     */

    public function no_sponsor_event() {
        $events = Events::where('no_sponsor', true)->get();
        $count = count($events);
        if($count > 0){
            $response = array(
                'status' => 1,
                'message' => 'Sukses',
                'total_rows' => $count,
                'data' => $events,
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
     *  List event filter by date
     */

    public function filter_by_date_event(Request $request) {
        $events = Events::where('updated_at', '>=', $request->get('start_date'))->where('updated_at', '<=', $request->get('end_date'))->get();
        $count = count($events);
        if($count > 0){
            $response = array(
                'status' => 1,
                'message' => 'Sukses',
                'total_rows' => $count,
                'data' => $events,
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
        Detail Events
        Desc Function : Function ini untuk mengambil spesifik data events
    */
    public function detail(Request $request, $id){
        $current_user = $this->currentUser();
        $current_user_id = $current_user->id;
        $current_user_username = $current_user->username;

        $current_user_profiles_id = $current_user->profile->id;
        $current_user_is_verified = $current_user->profile->is_verified;

        $model = new Events;
        $data = $model::where('id', $id)
                        ->with('event_sponsor.sponsors')
                        ->with('total_participants')
                        ->first();

        if($data){
            $status_attend = ParticipantsEvent::where('event_id', $id)
                                                ->where('profile_id', $current_user_profiles_id)
                                                ->first();

            $data->status_attend = ($status_attend) ? 1 : 0;
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
                        'data' => [],
                    );

            return response()->json($response,404);
        }
    }

    /**
        Create Events
        Desc Function : Function ini untuk membuat events
    */
    public function insert(Request $request){
        DB::beginTransaction();
        try {
            $current_user = $this->currentUser();
            $current_user_id = $current_user->id;
            $current_user_username = $current_user->username;

            $current_user_profiles_id = $current_user->profile->id;
            $current_user_is_verified = $current_user->profile->is_verified;

            if($current_user_is_verified){
                $events = new Events;

                $validationRules = [];
                if(method_exists($events,'validationInsertRules')){
                    $validationRules = $events->validationInsertRules();
                }

                $validator = Validator::make($request->all(), $validationRules, $events->messages());

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

                $events->event_name = $request->get('event_name');
                if($request->has('lat')){
                    $events->lat = $request->get('lat');
                }
                if($request->has('lng')){
                    $events->lng = $request->get('lng');
                }
                $events->event_date = $request->get('event_date');
                $events->location = $request->get('location'); // Nama lokasi
                $events->event_time = $request->get('event_time');
                if($request->has('desc')){
                    $events->desc = $request->get('desc');
                }
                if($request->has('area_range')) {
                    $events->area_range = $request->get('area_range');
                }
                if($request->has('seed_range')) {
                    $events->seed_range = $request->get('seed_range');
                }

                $events->created_by = $current_user_username;
                $events->updated_by = $current_user_username;

                
                // ambil array of id dari existing sponsor di tabel event_sponsor masukin ke sponsor
                $data_sponsors = (object) $request->get('sponsor');
                // dd($data_sponsors);
                // add is no sponsor
                // dd($data_sponsors->no_sponsor);
                $events->no_sponsor = $data_sponsors->no_sponsor;                
                $events->save();
                
                $sponsors = $data_sponsors->sponsor_ids;

                // cek is nosponsor
                if($data_sponsors->no_sponsor == FALSE){
                    $data_sponsors_manual = $data_sponsors->sponsor_manual;
                    // create sponsor baru
                    foreach ($data_sponsors_manual as $key => $sponsor_manual){
                        $sponsor = new Sponsor;
                        $sponsor->sponsors_name = $sponsor_manual['name'];
                        $sponsor->image = $sponsor_manual['logo'];
                        $sponsor->created_by = $current_user_username;
                        $sponsor->updated_by = $current_user_username;
                        $sponsor->save();
                        // ambil id nya
                        $id_sponsor_baru = $sponsor->id;
                        // taro id di tabel pivot
                        array_push($sponsors,$id_sponsor_baru);                            
                    }
                }
            
                // jika di checklist maka array of id dari sponsor akan siap diinsert ke tabel pivot
                foreach ($sponsors as $key => $sponsor) {
                    $event_sponsor = New EventSponsor;
                    $event_sponsor->event_id = $events->id;
                    $event_sponsor->sponsor_id = $sponsor;
                    $event_sponsor->created_by = $current_user_username;
                    $event_sponsor->updated_by = $current_user_username;
                    $event_sponsor->save();
                }

                $response = array(
                            'status' => 1,
                            'message' => 'Sukses menyimpan data',
                            'data' => $events,
                            'sponsors_id' => $sponsors,
                        );
                db::commit();
                return response()->json($response,200);
            } else {
                $data = array(
                    'status' => 0,
                    'message' => 'Akun anda tidak bisa membuat event dikarenakan belum terverifikasi',
                    'http_code' => 403,
                );

                return response()->json($data, 200);
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
    Donate Event
    Desc Function : Function ini memungkinkan untuk user dalam mendonasikan bibit
     */
    public function donate_seed(Request $request, $id) {
        DB::beginTransaction();
        try {
            $current_user = $this->currentUser();
            $current_user_id = $current_user->id;
            $current_user_username = $current_user->username;

            $current_user_profiles_id = $current_user->profile->id;

            $event = Events::find($id);
            $sum_seed_event = SeedEvent::where('id_event',$id)->sum('donate_seed');

            if((($event->seed_range !== -1) && (($sum_seed_event+$request->number_of_seeds) >= $event->seed_range)) || ($event->seed_range == null)) {
                db::rollBack();

                $response = array(
                    'status' => 0,
                    'message' => 'Event tidak butuh donasi lagi'
                );

                return response()->json($response,500);
            } else if ($request->number_of_seeds == 0) {
                db::rollBack();

                $response = array(
                    'status' => 0,
                    'message' => 'Donasi tidak boleh kosong'
                );

                return response()->json($response,500);
            } else{
                $seed_event = new SeedEvent;
                $seed_event->id_event = $id;
                $seed_event->id_user = $current_user_id;
                $seed_event->donate_seed = $request->number_of_seeds;
                $seed_event->save();

                $response = array(
                    'status' => 1,
                    'message' => 'Sukses mendonasikan bibit'
                );

                db::commit();
                return response()->json($response,200);
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

            $data = array(
                'status' => 0,
                'message' => 'Terjadi kesalahan',
                'exception_error' => $ex->getMessage(),
            );

            return response()->json($data, 500);
        }
    }

    /**
        Attends Events
        Desc Function : Function ini untuk user yang ingin menghadiri event
    */
    public function attend_event(Request $request, $id){
        DB::beginTransaction();
        try {
            $current_user = $this->currentUser();
            $current_user_id = $current_user->id;
            $current_user_username = $current_user->username;

            $current_user_profiles_id = $current_user->profile->id;

            $participants = new ParticipantsEvent;
            $participants->event_id = $id;
            $participants->profile_id = $current_user_profiles_id;
            $participants->created_by = $current_user_username;
            $participants->updated_by = $current_user_username;

            $participants->save();

            $response = array(
                        'status' => 1,
                        'message' => 'Sukses menyimpan data',
                        'data' => $participants,
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
        Approved Events
        Desc Function : Function ini untuk event yang diapproved oleh admin
    */
    public function approved_event(Request $request, $id){
        DB::beginTransaction();
        try {
            $model = new events;

            $event = $model::find($id);

            if($event){
                $event->filing_status = TRUE;
                $event->updated_by = 'Admin';

                $event->save();

                $response = array(
                            'status' => 1,
                            'message' => 'Sukses menyimpan data',
                            'data' => $event,
                        );

                db::commit();
                return response()->json($response,200);
            } else {
                $data = array(
                    'status' => 0,
                    'message' => 'Event tidak ditemukan',
                    'http_code' => 404,
                );

                return response()->json($data, 200);
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
        Not Attending
        Desc Function : Function ini untuk user yang tidak jadi hadir pada event
    */
    public function not_attending(Request $request, $id){
        DB::beginTransaction();
        try {
            $current_user = $this->currentUser();
            $current_user_id = $current_user->id;
            $current_user_username = $current_user->username;

            $current_user_profiles_id = $current_user->profile->id;

            $participants = ParticipantsEvent::where('event_id', $id)
                                                ->where('profile_id', $current_user_profiles_id);

            $participants->delete();

            DB::commit();

            $data = array(
                'status' => 1,
                'message' => 'Sukses',
            );

            return response()->json($data, 200);
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
        All By Date
        Desc Function : Function ini untuk mengambil semua / filter data events yang di grouping berdasarkan tanggal
    */
    public function all_by_date(Request $request){
        $model = new Events;
        $data = $model::select('event_date')
                        ->with('events')
                        ->groupBy('event_date');

        // filtering data untuk events yang sudah di approve atau belum
        if($request->has('is_approved')){
            if($request->get('is_approved') == 1){
                $data->where('filing_status', TRUE);
            } else {
                $data->where('filing_status', FALSE);
            }
        }

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
                    $data->orderBy('event_date');
                }
            }else{
                if($request->has('order_by')){
                    $data->orderBy($request->get('order_by'));
                }else{
                    $data->orderBy('event_date', 'desc');
                }
            }
        }else{
            $data->orderBy('event_date', 'desc');
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
                    );

            return response()->json($response,200);
        }
    }

    /*
      Mendapatkan event sesuai dengan event_name
    */
    public function getEvent(Request $request){
      $eventname = $request->eventname;
      $model = new Events;
      $data = $model::where('event_name', $eventname);

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

          return response()->json($response,200);
      }
    }

}
