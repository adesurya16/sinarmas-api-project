<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profiles;
use App\User;
use App\VerifiedSubmission;


class UserController extends Controller
{
    //filing_status 0:pending 1:verified 2:reject
    public function changeVerifiedUser(Request $request) {
      $filing_status = $request->filing_status;
      $id = $request->id_profile;
      if($filing_status == 1) {
        $this->verifyUser($id);
      }

      $verified_submission = VerifiedSubmission::where('profile_id', $id)->first();
      $verified_submission->filing_status = $filing_status;
      $verified_submission->save();

      return redirect()->back();
    }

    private function verifyUser($id) {
      $profile = Profiles::find($id);
      $profile->is_verified = true;
      $profile->save();
    }

    private function getProfile($filing_status) {
      $verified_submissions_init_today = VerifiedSubmission::where('filing_status', $filing_status)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->get();
      $verified_submissions_init_previous_day = VerifiedSubmission::where('filing_status', $filing_status)->where('created_at', '<', date('Y-m-d').' 00:00:00')->get();

      $verified_submissions_today = [];
      $verified_submissions_previous_day = [];

      foreach ($verified_submissions_init_today as $verified_submission) {
        $verified_submission->profile = Profiles::find($verified_submission->profile_id);
        $verified_submission->user = User::find($verified_submission->profile->user_id);
        array_push($verified_submissions_today, $verified_submission);
      }

      foreach ($verified_submissions_init_previous_day as $verified_submission) {
        $verified_submission->profile = Profiles::find($verified_submission->profile_id);
        $verified_submission->user = User::find($verified_submission->profile->user_id);
        array_push($verified_submissions_previous_day, $verified_submission);
      }

      // echo json_encode($verified_submissions_today);
      // echo json_encode($verified_submissions_previous_day);
      // die();
      return view('admin.user', ['users_today' => $verified_submissions_today, 'users_previous_day' => $verified_submissions_previous_day, 'filing_status' => $filing_status]);
    }

    public function getVerifiedUser() {
      return $this->getProfile(1);
    }

    public function getPendingUser() {
      return $this->getProfile(0);
    }

    public function getUserID(Request $request,$username){
      $profile = Profiles::find($username);
      return $profile->id;
    }

}
