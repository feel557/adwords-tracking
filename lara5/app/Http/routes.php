<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get("/", "IndexController@action_index");
Route::get("/login", "IndexController@action_signin");
Route::get("/contact", "IndexController@contactus");
Route::get("/trial", "IndexController@trial");
Route::get("/faq", "IndexController@faq");
Route::get("/about", "IndexController@about");

//===========================================================

//AUTH

Route::controller('auth', "Auth\AuthController");
Route::controller('password', 'Auth\PasswordController');

//ADMIN
/*
Route::get('admin/main', [
'middleware' => 'auth',
'uses' => 'Admin\AdminController@getMain'
]);
*/

Route::controller('pay', "Payments\PaymentsController");

Route::group(['middleware' => 'auth'], function(){

Route::controller('admin', "Admin\AdminController");
Route::controller('test', "Adwords\InternalAdwordsController");

});


Route::group(['middleware' => 'App\Http\Middleware\CustomerMiddleware'], function(){

Route::controller('user', "Customer\CustomerController");
//Route::controller('billing', "Payments\PaymentsController");

});


Route::controller('tracker', "Tracker\TrackerController");
Route::controller('mq', "Tracker\TaskQueueManagerController");

















