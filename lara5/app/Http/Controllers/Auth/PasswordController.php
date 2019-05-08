<?php namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use View;
use App\Http\Controllers\BaseController;
use DB;
//use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends BaseController {


//use ResetsPasswords;
public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        $this->auth = $auth;
        $this->passwords = $passwords;

        // With this, when logged says: "You're logged!" and not send the email token
        //$this->middleware('guest');
    }

/**
* The Guard implementation.
*
* @var Guard
*/
protected $auth;

/**
* The password broker implementation.
*
* @var PasswordBroker
*/
protected $passwords;

/**
* Display the form to request a password reset link.
*
* @return Response
*/
public function getEmail(){
	return View::make('public_pages/recover_password');
}

/**
* Send a reset link to the given user.
*
* @param  Request  $request
* @return Response
*/
public function postRemind(Request $request){

$response = $this->passwords->sendResetLink($request->only('email'), function($m)
		{
			$m->subject($this->getEmailSubject());
		});



		switch ($response)
		{
			case PasswordBroker::RESET_LINK_SENT:
				return redirect()->back()->withErrors(['status' => "The new password reset instructions been sent to your registered email address."]);

			case PasswordBroker::INVALID_USER:
				return redirect()->back()->withErrors(['email' => "Sorry, we do not have an account associated with email address."]);
		}

/*
$rules = array('email' => 'required|email');
$validation = Validator::make(Input::all(), $rules);
if ($validation->fails()) {
// В случае провала, редиректим обратно с ошибками и самими введенными данными
return Redirect::back()->withErrors($rules)->withInput();
}

$email = Input::post('email');
$product_array = DB::table('users')->where('email', '=', $email)->get();
$product_name = $product_array[0]->name;
$product_text = $product_array[0]->text;
*/


}

/**
* Get the e-mail subject line to be used for the reset link email.
*
* @return string
*/
protected function getEmailSubject()
{
return isset($this->subject) ? $this->subject : 'Your Password Reset Link';
}

/**
* Display the password reset view for the given token.
*
* @param  string  $token
* @return Response
*/
public function getReset($token = null)
{
if (is_null($token))
{
throw new NotFoundHttpException;
}

return view('public_pages/reset_password')->with('token', $token);
}

/**
* Reset the given user's password.
*
* @param  Request  $request
* @return Response
*/
public function postReset(Request $request)
{
$this->validate($request, [
'token' => 'required',
'email' => 'required|email',
'password' => 'required|confirmed',
]);

$credentials = $request->only(
'email', 'password', 'password_confirmation', 'token'
);

$response = $this->passwords->reset($credentials, function($user, $password)
{
$user->password = bcrypt($password);

$user->save();

$this->auth->login($user);
});

switch ($response)
{
case PasswordBroker::PASSWORD_RESET:
return redirect($this->redirectPath());

default:
return redirect()->back()
->withInput($request->only('email'))
->withErrors(['email' => trans($response)]);
}
}

/**
* Get the post register / login redirect path.
*
* @return string
*/
public function redirectPath()
{
if (property_exists($this, 'redirectPath'))
{
return $this->redirectPath;
}

return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
}

}
