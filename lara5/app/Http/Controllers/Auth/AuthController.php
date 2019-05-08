<?php namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Payments\PaymentsController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Auth\User;
use Password;
use Input;
use Hash;
use Redirect;
use Auth;
use Log;
use View;
use Validator;
use DB;


class AuthController extends BaseController {


//вывод страницы добавления
function getSignup(){
//return "hello!";

$arrayCountry = DB::table('country_t')->get();

return View::make( 'public_pages/signup', array("country" => $arrayCountry) );

}

//Add new user
function postAdd(){

// Проверка входных данных
if(Input::get('password') != Input::get('password_2')){
return Redirect::to('/auth/signup')->withErrors("Password and conform password should be the same")->withInput();
}
/*
if( Input::get('agree') != true ){
return Redirect::to('/auth/signup')->withErrors("Please read the Terms of Service and click the checkbox to indicate your agreement to them.")->withInput();
}
*/

$rules = User::$validation;
$validation = Validator::make(Input::all(), $rules);
if ($validation->fails()) {
// В случае провала, редиректим обратно с ошибками и самими введенными данными
return Redirect::to('/auth/signup')->withErrors($validation)->withInput();
}

	// Сама регистрация с уже проверенными данными
	$user = new User();
	$user->fill(Input::all());
	$id = $user->register();

	//DB::table('users')->where('id', '=', $id)->update(array('is_active' => 1));
	

$user = User::find($id);
Auth::login($user);
return Redirect::to("/user/welcome/");


}


//===========
// activation

public function getActivate($userId, $activationCode) {
// Получаем указанного пользователя
$user = User::find($userId);
if (!$user) {
return $this->getMessage("Link Error");
}

// Пытаемся его активировать с указанным кодом
if ($user->activate($activationCode)) {
// В случае успеха авторизовываем его
Auth::login($user);
// И выводим сообщение об успехе
return Redirect::to("/user/main/");
}

// В противном случае сообщаем об ошибке
return $this->getMessage("Link Error or account already was activated");
}



public function getActivateEmail(){
	
	$array = DB::table('users')
	->where('id', '=', Input::get('userId'))
	->where('activation_code', '=', Input::get('activationCode'))
	->get();

	if(count($array) > 0){

		DB::table('users')->where('id', '=', $array[0]->id)
						  ->update(array('email' => $array[0]->email_new, 
										 'email_new' => '', 
										 'activation_code' => ''
											)
										);

	}

	return Redirect::to("/user/main/");

}


public function getLogin() {
//return View::make('auth/login');
return View::make('public_pages/login');
}

// Login
public function postLogin() {


// Формируем базовый набор данных для авторизации
// (isActive => 1 нужно для того, чтобы авторизоваться могли только
// активированные пользователи)
$creds = array(
'email' => Input::get('email'),
'password' => Input::get('password'),
'is_active'  => 1,
);

$username = Input::get('email');

//var_dump($creds);

// Пытаемся авторизовать пользователя
if (Auth::attempt($creds, Input::has('remember'))) {
Log::info("User [{$username}] successfully logged in.");
if(Auth::user()->user_type == 21){
return Redirect::to("/admin/main/");
}else{
return Redirect::to("/user/main/");
}

} else {
Log::info("User [{$username}] failed to login.");
}

$alert = "Invalid email / password or account not activated yet";

// Возвращаем пользователя назад на форму входа с временной сессионной
// переменной alert (withAlert)
return Redirect::back()->withAlert($alert);

}


public function getLogout() {
Auth::logout();
return Redirect::to('/');
}








}