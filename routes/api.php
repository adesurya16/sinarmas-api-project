<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

// Profile for Registered User / Unregistered User
Route::get('profile/{profile_id}', 'ProfilesController@detail');
// Route::get('getUserIdUsername/{username}','Admin\\UserController@getUserID');

// Posting for Registered User / Unregistered User
Route::get('posting', 'PostingActivitiesController@all');
Route::get('posting/{posting_id}', 'PostingActivitiesController@detail');
Route::get('getcomments/{id_activity}', 'CommentController@get_comment');

// Verified User Submissions (Admin Side)
Route::put('set-verified/{id}', 'VerifiedSubmissionsController@set_verified');

Route::group(['middleware' => ['jwt.auth']], function() {
	// Posting
	Route::post('posting', 'PostingActivitiesController@insert');

	// My Profile
	Route::put('myprofile', 'ProfilesController@MYupdate');
	Route::get('myprofile', 'ProfilesController@MYdetail');

	// Route::put('updateprofile','ProfilesController@updateProfile');
    Route::post('search/by-username','ProfilesController@getUserProfile');

    // CRUDS Events
	Route::get('events', 'EventsController@all');
	Route::get('events/by-date', 'EventsController@all_by_date');
    Route::get('events/{id}/details', 'EventsController@detail');
	Route::post('events', 'EventsController@insert');
	Route::post('events/{id}/donate_seed', 'EventsController@donate_seed');
	Route::put('events/{id}/approved', 'EventsController@approved_event');
	Route::put('events/{id}/attend', 'EventsController@attend_event');
	Route::put('events/{id}/not-attending', 'EventsController@not_attending');
    Route::post('search/by-eventname','EventsController@getEvent');
    Route::post('events/by-date', 'EventsController@filter_by_date_event');
    Route::get('event/no-sponsor', 'EventsController@no_sponsor_event');

	// Trees
	Route::get('trees', 'TreesController@all');

	// Verified User Submissions (Client Side)
	Route::get('submission_verified', 'VerifiedSubmissionsController@all');
	Route::post('submission_verified', 'VerifiedSubmissionsController@insert');
	Route::put('re-submission', 'VerifiedSubmissionsController@re_submission');
	Route::put('submission_verified/upload-id-card', 'VerifiedSubmissionsController@upload_image_id_card');

	// Sponsor
	Route::get('sponsor', 'SponsorController@all');

	// Komentar
    Route::post('comment', 'CommentController@insert');


    // Upload Image
    Route::post('upload_image', 'FileController@uploadImage');

});
