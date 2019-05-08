<?php namespace App\Http\Controllers\Auth;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\Reminders\RemindableTrait;
use Log;
use Hash;
use Mail;
use Illuminate\Support\Str;
use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use DB;
//use App\Http\Controllers\Auth\UserRegController;

class User extends Eloquent implements AuthenticatableContract, CanResetPasswordContract {

use Authenticatable, CanResetPassword;

/**
* The database table used by the model.
*
* @var string
*/

protected $table = 'users';
protected $primary = 'id';//neobyz
protected $fillable = array(
'first_name',
'last_name',
'email',
'password',
//'credit_card_number',
//'exp_date_m',
//'exp_date_y',
//'credit_card_cvv',
'phone',
'website',
'company_name'

);

protected $hidden = array('password', 'remember_token');

// register data
public function register() {

$originPass = $this->password;
$this->password = Hash::make($this->password);
$this->activation_code = $this->generateCode();
$this->is_active = false;
$this->save();

Log::info("User [{$this->email}] registered. Activation code: {$this->activation_code}");

$this->sendActivationMail($originPass);

return $this->id;

}

public static $validation = array(

'email'     => 'required|email|unique:users',
'first_name'  => 'required',
//'last_name'  => 'required',
'password'  => 'required|min:6',
//'country_id'  => 'required',
//'state'  => 'required',
//'zipcode'  => 'required',

);

protected function generateCode() {
return Str::random(); // По умолчанию длина случайной строки 16 символов
}

public function sendActivationMail($originPass) {


//echo $this->email;
$message = "<p>Hello <b>".$this->first_name."</b>,<br>
Thanks for signing up to ClickMonitor, we hope you enjoy your free trial.
</p>
<p>If you have any questions at all during your trial, please do not hesitate to contact us.</p>

<p>
Control Panel URL: http://account.clickmonitor.co.uk/<br>
Username: ".$this->email."<br>
Password: ".$originPass."
</p>

<p>
Please follow the below steps to complete the setup and start using our state of art click fraud monitoring and protection tool.<br>
<br>
<span style='text-transform:uppercase;font-weight:bold;'>Step 1 – Activate Your Account</span>
<br>
One of our team member will be in touch with you shortly with further details about your free trial.  
</p>



<p>
<span style='text-transform:uppercase;font-weight:bold;'>Step 2 – Link Your AdWords Account</span>
<br>
Login into the account using the details you have used at the time of registering.
<br><br>
Now you should link your Google AdWords Account with our tracking tool. This needs to be done if you want to block the suspicious clicks / fraud clicks automatically.
</p>


<p>
<span style='text-transform:uppercase;font-weight:bold;'>Step 3 – Create a Monitor</span><br>
Create your monitor and define the settings for ALERT LEVEL 1 and ALERT LEVEL 2 SETTINGS as per your user behaviour. You can create a single monitor applicable to all the campaigns within the AdWords Account or you can have separate monitor for specific campaigns. 
</p>

<p>&nbsp;</p>
<p><img src='http://account.clickmonitor.co.uk/emailimg/image023.png' width='400'></p>
<p><img src='http://account.clickmonitor.co.uk/emailimg/image025.png' width='400'></p>
<p>&nbsp;</p>

<p>
Example - For some businesses 4-5 clicks within 24 hours from same IP address may be normal on the other hand for some businesses this may be too much if their cost per clicks ranges from £5-£50.
</p>


<p>
It would be better to warn users first rather than blocking them as they may have clicked multiple time by accident. As a thump rule don’t be too narrow or too broad in your custom settings otherwise you may end up blocking lot of genuine users or if it is too broad then it may not be worthwhile and you may not be able to track fraud clicks.
</p>



<p>
<span style='text-transform:uppercase;font-weight:bold;'>Step 4 – Setup The Tracking URL</span><br>
The next step is setup the tracking URL into your AdWords Account. Copy the tracking URL after you have created your monitor from the monitors page as shown in the below screenshot.
</p>

<p>&nbsp;</p>
<p><img src='http://account.clickmonitor.co.uk/emailimg/image027.png' width='400'></p>
<p>&nbsp;</p>

<p>
And paste the tracking URL in your campaign settings in TRACKING TEMPLATE and SAVE it as shown in the below screenshot.
</p>
 
<p>&nbsp;</p>
<p><img src='http://account.clickmonitor.co.uk/emailimg/image029.png' width='400'></p>
<p>&nbsp;</p>

<p>
<span style='text-transform:uppercase;font-weight:bold;'>Step 5 – Get Started With Your Click Fraud Detection and Protection</span><br>
Thanks, and enjoy!
</p>

<p>
ClickMonitor.co.uk<br>
E-mail - Support@ClickMonitor.co.uk<br>
Telephone - 01438 316 801
</p>
";
		$data["email"] = $this->email;
		$data["subject"] = "Welcome To ClickMonitor";
		$data["text"] = $message;
		
		Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
							$message->to($data["email"])->subject($data["subject"]);
						});

}


//---activation

public function activate($activationCode) {
// Если пользователь уже активирован, не будем делать никаких
// проверок и вернем false
if ($this->is_active) {
return false;
}

// Если коды не совпадают, то также ввернем false
if ($activationCode != $this->activation_code) {
return false;
}

// Обнулим код, изменим флаг isActive и сохраним
$this->activation_code = '';
$this->is_active = true;
$this->save();

// И запишем информацию в лог, просто, чтобы была :)
Log::info("User [{$this->email}] successfully activated");

return true;
}

}
