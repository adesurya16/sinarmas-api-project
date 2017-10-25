<?php
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/login', function () {
  return view('admin.login');
})->middleware('admin.redirect');

Route::post('/login', 'Admin\\AdminController@doLogin');
Route::get('/logout', 'Admin\\AdminController@logout');

Route::middleware(['admin.login'])->group(function () {
  Route::get('/home', function () {
      return view('admin.home');
  });

  // user
  Route::get('/user/verify', 'Admin\\UserController@getVerifiedUser');
  Route::get('/user/pending', 'Admin\\UserController@getPendingUser');
  Route::post('/user', 'Admin\\UserController@changeVerifiedUser');


  Route::get('/event/verify', 'Admin\\EventController@getVerifiedEvent');
  Route::get('/event/pending', 'Admin\\EventController@getPendingEvent');
  Route::get('/event/on_going', 'Admin\\EventController@getOnGoingEvent');
  Route::post('/event', 'Admin\\EventController@changeVerifiedEvent');
});
