<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use View;
use Input;
use Redirect;
use DB;
use Auth;
use Excel;
use App\Http\Controllers\Payments\PaymentsController;
use Hash;
use Mail;
use App\Http\Controllers\Adwords\InternalAdwordsController;
use App\Http\Controllers\Customer\CustomerController;


class AdminController extends BaseController {



function getMain(){


$users = DB::table('users')
->where("user_type", "!=", "21")
->orderBy('id', 'desc')
->get();

$data['users'] = $users;

$users_trial = DB::table('users')
->where("billing_subscription_id", "<>", "" )
->where("billing_start_date", ">", time() )
->where("user_type", "!=", "21")
->orderBy('id', 'desc')
->get();

$data['users_trial'] = $users_trial;

$array_total_clicks = "";


// Statistic, Reports, Data Visualization
$arrayAllUserTrackers = DB::table('_trackers')
->get();




if( !empty(Input::get('range_type')) && Input::get('range_type') == 2 && !empty(Input::get('date1')) && !empty(Input::get('date2')) ){

$date1 = Input::get('date1');
$date1 = explode("-",$date1);
$date1 = mktime( 0,0,0,$date1[2],$date1[1],$date1[0] );

$date2 = Input::get('date2');
$date2 = explode("-",$date2);
$date2 = mktime( 0,0,0,$date2[2],$date2[1],$date2[0] );


$date_2 = Input::get('date2')." 00:00:00";
$date_1 = Input::get('date1')." 00:00:00";


}else{

$date2 = time();
$date1 = time()-7*(60*60*24);


$date_2 = date('Y-m-d H:i:s');
$date_1 = date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")) );


}


if(count($arrayAllUserTrackers) > 0){

$array_total_clicks = DB::table('_trackers_data')
->where('click_timestamp', '>=', $date1)
->where('click_timestamp', '<=', $date2)
->get();

}

$data['total_clicks'] = count($array_total_clicks);



//$paymentController = new PaymentsController;
//$transactions = $paymentController->getBillingTransactions($date_1, $date_2);
$month_total_revenue = 0;
/*
	foreach($transactions as $transaction) {
		$transactionItem = $paymentController->getTransactionDetailsById($transaction->id);
		$month_total_revenue = $month_total_revenue + $transactionItem->amount;
	}
*/
$data['month_total_revenue'] = $month_total_revenue;

return View::make('admin/index', array("data" => $data));

}

function getTestMy(){

$paymentController = new PaymentsController;
$paymentController->createSubscriptionPayment();

}

function getUsers(){

$value = Input::get("value");
$value_type = Input::get("value_type");
$sort = Input::get("sort");
$special_search = Input::get("special_search");

if($special_search){

if(Input::get("special_search") == 1){

	$date = time() - 30*24*60*60;

	$arrayData = DB::table('_trackers_users_domains')
	->where("_trackers_users_domains.date", ">", $date )
	->get();

	$usersArray = array();

	foreach($arrayData as $user){

		if(!in_array($user->user, $usersArray)){

			$usersArray[] = $user->user;

		}

	}


	$resultArray = array();
	$str = "";
	$i = 0;
	foreach($usersArray as $user){
		/*
	$array_2 = DB::table('users')
	->whereRaw("id", "=", $user)
	->get();
		*/
	if($i == 0){
		$str .= " `id` = '".$user."' ";
	}else{
		$str .= " OR `id` = '".$user."' ";
	}
	//$resultArray[] = $array_2[0];
	$i++;
	}

//var_dump($str);

	$array_2 = DB::table('users')
	->whereRaw($str)
	->Paginate(100);

//var_dump($array_2);


return View::make('admin/users', array( "users_array" => $array_2 ) );

}



}

if( isset($sort) ){

if($sort == 1){$query_sort = 'id';}
if($sort == 2){$query_sort = 'billing_subscription_id';}
if($sort == 3){$query_sort = 'username';}

}else{

$query_sort = 'id';

}



if( (isset($value)  && !empty($value)) || isset($value_type) ){

if($value_type == 1){

	$array = DB::table('users')
	->where("email", "=", $value)
	->where("user_type", "!=", "21")
	->orderBy($query_sort, 'asc')
	->Paginate(100);

}

if($value_type == 2){

	$array = DB::table('users')
	->where(function ($query) use ($value) {
		$query->where("first_name", "like", $value."%")
			  ->orWhere("last_name", "like", $value."%");
										}
			)
	->where("user_type", "!=", "21")
	->orderBy($query_sort, 'asc')
	->Paginate(100);

}


if($value_type == 3){

	$array = DB::table('users')
	->where("billing_subscription_id", "<>", "" )
	->where("billing_start_date", ">", time() )
	->where("user_type", "!=", "21")
	->orderBy($query_sort, 'asc')
	->Paginate(100);

}



}else{

	$array = DB::table('users')
	->where("user_type", "!=", "21")
	->orderBy($query_sort, 'asc')
	->Paginate(100);

}




return View::make('admin/users', array( "users_array" => $array ) );

}


function getUserDetail(){

$array = DB::table('users')
->where("id", "=", Input::get("id"))
->get();

$trialStatus = 0;
/*
$paymentController = new PaymentsController();
$plans = $paymentController->getBillingPlans();
$trialStatus = 0;

if($array[0]->billing_subscription_id!=""){
	$subscription = $paymentController->getSubscription($array[0]->billing_subscription_id);
	if($subscription->trialPeriod == true){

		if($subscription->trialDurationUnit == "month"){$multiplier = 30;}else{$multiplier = 1;}

		$totalTrialPeriodSeconds = $subscription->trialDuration*$multiplier*3600*24;
		if( $array[0]->billing_start_date + $totalTrialPeriodSeconds <= time() ){$trialStatus = 1;}

	}
$data["paymentMethod"] = $paymentController->getPaymentMethod($array[0]->braintree_payment_token);
}else{
	$subscription = "";
$data["paymentMethod"] = "";
}
*/

$data["user_data"] = $array;
$data["billing_plans"] = "";
$data["billing_subscription"] = "";
$data["trialStatus"] = $trialStatus;

//$data["user_transactions"] = $paymentController->getTransactions($array[0]->id);
$i=0;
$countClicks = 0;
if(isset($data["user_transactions"]->transactions)){
foreach($data["user_transactions"]->transactions as $transactionItem){

	$trDate = $transactionItem->createdAt->format('Y-m-d H:i:s');
	$searchDate = $transactionItem->createdAt->getTimestamp();

	$userTrackers = DB::table('_trackers')
	->where("user", "=", $array[0]->id)
	->get();

	

	foreach($userTrackers as $userTracker){

		$clicksArray = DB::table('_trackers_data')
		->where("tracker_id", "=", $userTracker->id)
		->where("click_timestamp", ">=", $searchDate)
		->where("click_timestamp", "<=", time())
		->get();

		$countClicks = $countClicks + count($clicksArray);

	}

		if($i==0){break;}
		$i++;

	}
}
$data["count_click_last_transaction"] = $countClicks;

return View::make('admin/user_detail', array( "data" => $data ) );

}



function postChangeBillingPlan(){

	$error = "";
$array = DB::table('users')
		->where('id', '=', Input::get("userId"))
		->get();

if($array[0]->braintree_customer_id != ''){

	$paymentController = new PaymentsController();
	$deleteResult = $paymentController->deleteSubscription(Input::get("userId"));

	if($deleteResult == 1){
		$paymentController->createSubscription(Input::get("planId"), Input::get("userId"));
	}

}else{
	$error = "This user doesn't have braintree account";
}

return Redirect::back()->withErrors([$error]);

}







function getBillingTransactions(){


if( !empty(Input::get('range_type')) && Input::get('range_type') == 2 && !empty(Input::get('date1')) && !empty(Input::get('date2')) ){

	$date_2 = Input::get('date2')." 00:00:00";
	$date_1 = Input::get('date1')." 00:00:00";

}else{

	$date_2 = date('Y-m-d H:i:s');
	$date_1 = date( 'Y-m-d H:i:s', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")) );

}

$paymentController = new PaymentsController;
$transactions = $paymentController->getBillingTransactions($date_1, $date_2);

$transactionsArray = array();

foreach($transactions as $transaction) {
    $transactionItem = $paymentController->getTransactionDetailsById($transaction->id);
	$transactionsArray[] = $transactionItem;
}

return View::make('admin/billing_transactions', array( "data" => $transactionsArray ) );

}









function getBillingPlans(){

	$paymentController = new PaymentsController;
	$plans = $paymentController->getBillingPlans();


	$defaultBillingPlan = DB::table('default_settings')
	->where('id', '=', 1)
	->get();

	return View::make('admin/billing_plans', array( "billing_plans" => $plans, "default_plan" => $defaultBillingPlan ) );

}



function getSetDefaultBillingPlan(){

	$paymentController = new PaymentsController;
	$plans = $paymentController->getBillingPlans();


	$defaultBillingPlan = DB::table('default_settings')
	->where('id', '=', 1)
	->update(array('value' => Input::get('id')));

	
return Redirect::back();

}


function getLoginAsUser(){


$user = Auth::loginUsingId(Input::get('id'));
if (!$user){
    throw new Exception('Error logging in');
}else{
	return Redirect::to("/user/main/");
}



}







function getAbout(){
return View::make('admin/about');
}


/* ADD New place*/

function getMapsSettings(){

//$ini_file = base_path() . "/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/AdWords/auth.ini";//"/phpExternalClasses/adwordsLib/src/Google/Api/Ads/AdWords/auth.ini";

//$ini_array = parse_ini_file($ini_file);
//print_r($ini_array);

$array = DB::table('_adwords_settings')->get();

return View::make('admin/maps_settings', array(
//"ini_array" => $ini_array,
"settings_array" => $array
));



}



function postMapSettingsUpdate(){

$requestType = Input::get('requestType');


if($requestType == 2){

$ini_file = base_path() . "/vendor/googleads/googleads-php-lib/src/Google/Api/Ads/AdWords/auth.ini";//"/phpExternalClasses/adwordsLib/src/Google/Api/Ads/AdWords/auth.ini";

$settings_array = DB::table('_adwords_settings')->get();

$array = array(
'developerToken' => $settings_array[0]->developerToken,
'userAgent' => $settings_array[0]->userAgent,
'clientCustomerId' => $settings_array[0]->managerClientCustomerId,
'OAUTH2' => array(
'client_id' => $settings_array[0]->client_id,
'client_secret' => $settings_array[0]->client_secret,
'refresh_token' => $settings_array[0]->managerRefreshToken
)
);



function write_php_ini($array, $file){
$res = array();
foreach($array as $key => $val)
{
if(is_array($val))
{
$res[] = "[$key]";
foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
}else{
$res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
}
}
safefilerewrite($file, implode("\r\n", $res));
}

function safefilerewrite($fileName, $dataToSave){
if ($fp = fopen($fileName, 'w')){
$startTime = microtime(TRUE);
do
{
$canWrite = flock($fp, LOCK_EX);
// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
if(!$canWrite) usleep(round(rand(0, 100)*1000));
} while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

//file was locked so now we can store information
if ($canWrite)
{
fwrite($fp, $dataToSave);
flock($fp, LOCK_UN);
}
fclose($fp);
}

}



write_php_ini($array, $ini_file);

}

if($requestType == 1){

$developerToken = Input::get('developerToken');
$userAgent = Input::get('userAgent');
$clientCustomerId = Input::get('clientCustomerId');
$client_id = Input::get('client_id');
$client_secret = Input::get('client_secret');
$refresh_token = Input::get('refresh_token');
$redirect_uri = Input::get('redirect_uri');

DB::table('_adwords_settings')
//->where('id', '=', '1')
->update(array(
'developerToken' => $developerToken,
'userAgent' => $userAgent,
'managerClientCustomerId' => $clientCustomerId,
'client_id' => $client_id,
'client_secret' => $client_secret,
'managerRefreshToken' => $refresh_token,
'redirect_uri' => $redirect_uri
));

}

return Redirect::back()->withErrors(['Data was saved']);

}








function getDeleteUser(){

$inputId = Input::get('id');

$internalAdwordsController = new InternalAdwordsController();

$arrayUser = DB::table('_adwords_users')
->where('internal_user_id', '=', $inputId)
->get();

if( count($arrayUser) > 0 ){


$json = $internalAdwordsController->getAccessToken($arrayUser[0]->adwords_refresh_token);
$jsonResponse = json_decode($json);
if(!isset($jsonResponse->error)){


	// Delete tracking URL

	$trackersArrayByUser = DB::table('_trackers')
	->where('user', '=', $inputId)
	->where('is_deleted', '!=', 1)
	->get();


	$customerController = new CustomerController;

	foreach($trackersArrayByUser as $trackerArray){

		if( isset($trackerArray->tracking_level) && $trackerArray->tracking_level == 2 ){
			$trackingUrl = "";
			if( isset($trackerArray->tracking_item) ){
			$customerController->addTrackingUrlCampaign($trackerArray->tracking_item, $trackingUrl, $inputId);
			}
		}

		//add tracking url to adwords
		if( isset($trackerArray->tracking_level) && $trackerArray->tracking_level == 1 ){
			$trackingUrl = "";
			$customerController->addTrackingUrlAccount($trackingUrl, $inputId);
		}

	}
	//-------




$clientCustomerId = $arrayUser[0]->adwords_user_id;
$deleteLinkResult = $internalAdwordsController->deleteLinkBetweenAccounts($clientCustomerId);
//var_dump($deleteLinkResult);


if( $deleteLinkResult->links[0]->linkStatus == "INACTIVE" ){


DB::table('_adwords_users_nonmanager')
->where('manager_adwords_id', '=', $arrayUser[0]->adwords_user_id)
->delete();

DB::table('_adwords_campaigns')
->where('manager_adwords_id', '=', $arrayUser[0]->adwords_user_id)
->delete();

DB::table('_adwords_users')
->where('adwords_user_id', '=', $arrayUser[0]->adwords_user_id)
->delete();

DB::table('users')->where('id', '=', $inputId)->delete();


}else{

var_dump($deleteLinkResult);
exit();

}
}

}else{

DB::table('users')->where('id', '=', $inputId)->delete();

}


return Redirect::back();

}

function getBlockUser(){

$data = Input::get('id');
DB::table('users')->where('id', '=', $data)->update(array('is_active' => 0));
return Redirect::back();

}

function getUnblockUser(){

$data = Input::get('id');
DB::table('users')->where('id', '=', $data)->update(array('is_active' => 1));
return Redirect::back();

}








/* Messages */

function getAddMessage(){


$type = Input::get('add_message_type');
$usersJson = Input::get('users_array_json');


if($type == 1){// Selected

		$usersArray = json_decode($usersJson, true);

		$resultArray = array();
		if(count($usersArray)>0){
		foreach($usersArray as $userId){

		$query = DB::table('users')
		->where("id", "=", $userId)
		->get();
		$resultArray[] = $query;

		}

		$data["type"] = 1;
		$data["usersArray"] = $resultArray;
		$data["usersJson"] = $usersJson;

		return View::make('admin/message_add', array("data" => $data));

		}else{
			return Redirect::back();
		}
}elseif($type == 2){// All

		$data["type"] = 2;

		return View::make('admin/message_add', array("data" => $data));

}elseif($type == 3){

	$user_id = Input::get('id');
	if( isset($user_id) && $user_id != "" ){

		$query = DB::table('users')
		->where("id", "=", Input::get('id'))
		->get();

		$data["type"] = 3;
		$data["userData"] = $query;

		return View::make('admin/message_add', array("data" => $data));

	}

}


	//return Redirect::back();


}


function postAddMessage(){

	$user_id = Input::get('id');
	$type = Input::get('type');
	$usersJson = Input::get('usersJson');
	$data["subject"] = Input::get('theme');
	$data["text"] = Input::get('text');

	if($type == 1){// Selected

		$usersArray = json_decode($usersJson, true);

		if(count($usersArray)>0){
		foreach($usersArray as $userId){

		$query = DB::table('users')
		->where("id", "=", $userId)
		->get();

			$data["user_to"] = $query;
			
		if(isset($data["user_to"][0]->email)){

			Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
					$message->to($data["user_to"][0]->email)->subject($data["subject"]);
				});

		}

		}
		}

	}elseif($type == 2){// All

	$query = DB::table('users')
			->where("user_type", "!=", 21)
			->get();
	foreach($query as $userItem){

				$data["user_to"] = $userItem;
				
			if(isset($data["user_to"]->email)){

				Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
						$message->to($data["user_to"]->email)->subject($data["subject"]);
					});

			}

			}

	}elseif($type == 3){

	$query = DB::table('users')
	->where("id", "=", $user_id)
	->get();

	$data["user_to"] = $query;
	
	if(isset($data["user_to"][0]->email)){

		Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
				$message->to($data["user_to"][0]->email)->subject($data["subject"]);	
			});

	}

	}

return redirect('/admin/users/')->with(array("message" => "Message was sent"));

}



function postEditUser(){


$email = Input::get('email');
//$daily_summary_email = Input::get('daily_summary_email');

$first_name = Input::get('first_name');
$last_name = Input::get('last_name');
$phone = Input::get('phone');
$state = Input::get('state');
$zipcode = Input::get('zipcode');
$address1 = Input::get('address1');
$address2 = Input::get('address2');
$timezone = Input::get('timezone');

$credit_card_number = Input::get('credit_card_number');
$exp_date_m = Input::get('exp_date_m');
$exp_date_y = Input::get('exp_date_y');
$credit_card_cvv = Input::get('credit_card_cvv');





if(isset($email) && $email != ""){

	$check_email_exist = DB::table('users')->where('email', '=', $email)->where('id', '!=', Input::get('id'))->get();
	if(count($check_email_exist)<1){
		DB::table('users')->where('id', '=', Input::get('id'))->update(array('email' => $email));
	}

}



DB::table('users')
->where('id', '=', Input::get('id'))
->update(
array(

'first_name' => $first_name,
'last_name' => $last_name,
'phone' => $phone,
'state' => $state,
'zipcode' => $zipcode,
'address1' => $address1,
'address2' => $address2,
'timezone' => $timezone,
'credit_card_number' => $credit_card_number,
'exp_date_m' => $exp_date_m,
'exp_date_y' => $exp_date_y,
'credit_card_cvv' => $credit_card_cvv

)
);



return Redirect::back();



}


















function getDeleteMessages(){


$id_arr = explode(',', Input::get('string'));
foreach($id_arr as $value){
if($value != ''){

$query_messages = DB::table('messages')
->where("id", "=", $value)
->where(function ($query) {
                $query->where("user_from", "=", Auth::user()->id)
                      ->orWhere("user_to", "=", Auth::user()->id);
            })
->get();


if(count($query_messages) > 0){

if($query_messages[0]->user_from == Auth::user()->id){
DB::table('messages')->where("id", "=", $value)->where("user_from", "=", Auth::user()->id)->update(array("user_from_del" => 1));
}

if($query_messages[0]->user_to == Auth::user()->id){
DB::table('messages')->where("id", "=", $value)->where("user_to", "=", Auth::user()->id)->update(array("user_to_del" => 1));
}


}}
}

echo 1;


}

function getMessages(){

if(!empty(Input::get('id'))){
// Get single Message

$query = DB::table('messages')
->where("id","=",Input::get('id'))
->where(function ($query) {
                $query->where("user_from","=",Auth::user()->id)
                      ->orWhere("user_to","=",Auth::user()->id);
            })
->get();


DB::table('messages')
->where("id","=",Input::get('id'))
->where(function ($query) {
                $query->where("user_from","=",Auth::user()->id)
                      ->orWhere("user_to","=",Auth::user()->id);
            })
->update(array(
"view" => 1
));

}else{
// Get all messages
if(!empty(Input::get('out'))){$get_out = Input::get('out');}else{$get_out = 0;}


//user_to_del_forever - delete forever
//user_to_del - delete to trash basket
//spam - spam for input messages


if($get_out == 0){

//$sql = "  user_to_del = '0' AND user_to = '". Auth::user()->id ."' AND user_to_del_forever = '0' ";

$query = DB::table('messages')
->where("user_to_del","=","0")
->where("user_to","=",Auth::user()->id)
->orderBy('date', 'desc')
->Paginate(30);

}
if($get_out == 1){

//$sql = " user_from_del = '0' AND user_from = '". Auth::user()->id ."' AND user_from_del_forever = '0' ";

$query = DB::table('messages')
->where("user_from_del","=","0")
->where("user_from","=",Auth::user()->id)
->orderBy('date', 'desc')
->Paginate(30);

}

/*
trash & spam folders
if($get_out == 2){$sql = " (user_to_del = '1' AND user_to = '". Auth::user()->id ."' AND spam = '0' AND user_to_del_forever = '0' ) OR (user_from_del = '1' AND user_from = '". Auth::user()->id ."' AND user_from_del_forever = '0' )";}

if($get_out == 3){$sql = " spam = '1' AND user_to = '". Auth::user()->id ."' AND user_to_del_forever = '0' ";}
*/




}
return View::make('admin/messages', array("data" => $query));
}










function getSettings(){

return View::make('admin/settings', array(
"array" => ""
));

}




function postEditSettings(){

$email = Input::get('email');
$password = Input::get('password');
$password_2 = Input::get('password_2');


if(isset($password) && isset($password_2) && $password != "" && $password_2 != "" && $password == $password_2){

	$password = Hash::make($password);
	DB::table('users')->where('id', '=', Auth::user()->id)->update(array('password' => $password));

}

if(isset($email) && $email != ""){

	$check_email_exist = DB::table('users')->where('email', '=', $email)->where('id', '!=', Auth::user()->id)->get();
	if(count($check_email_exist)<1){
		DB::table('users')->where('id', '=', Auth::user()->id)->update(array('email' => $email));
	}

}

return Redirect::back();

}


function getExportAllUsers(){

	$arrayUsers = DB::table('users')
	->get();
	$lines = "";
	foreach($arrayUsers as $userItem){

		$lines .= $userItem->first_name . "," . 
				  $userItem->last_name . "," . 
				  $userItem->email . "," .  
				  $userItem->phone . "," .  
				  $userItem->state . "," .  
				  $userItem->zipcode . "," .  
				  $userItem->address1 . "," .  
				  $userItem->address2 . "," .  
				  $userItem->timezone . "," .

					" \r\n";

		}

	header('Content-Disposition: attachment; filename="export.csv"');
	header("Cache-control: private");
	header("Content-type: application/force-download");
	header("Content-transfer-encoding: binary\n");

  echo $lines;

  exit;

}

function getTest(){

	$arrayUser = DB::table('users')
	->where("user_type", "!=", "21")
	->get();

	foreach($arrayUser as $user){

	if($user->exp_date_y >= 2016 && $user->exp_date_y < 2030 && $user->exp_date_m > 0 && $user->exp_date_m <= 12){

		$date = date_create_from_format('Y-m-d', $user->exp_date_y . "-" . $user->exp_date_m . "-" . "01");

		$newDate = $date->getTimestamp();
		$diff = $newDate - time();

		if($diff < 7*24*3600){

			//echo $user->id." send email";


			$query = DB::table('users')
			->where("id", "=", $user->id)
			->get();

			$data["user_to"] = $query;
			$data["subject"] = "Your credit card expiration date is close";
			$data["text"] = "text";

			if($user->count_expiration_emails<=7){

			if(isset($data["user_to"][0]->email)){

				Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
						$message->to($data["user_to"][0]->email)->subject($data["subject"]);	
					});

				$count_expiration_emails = $user->count_expiration_emails+1;
				DB::table('users')
				->where('id', '=', $user->id)
				->update(array('count_expiration_emails' => $count_expiration_emails));

			}

			}
		}

		}
	}


}






function getTest2(){

//Statistic, Reports, Data Visualization


	$arrayUser = DB::table('users')
	->where("user_type", "!=", "21")
	->where("daily_summary_email", "=", 1)
	->get();

foreach($arrayUser as $user){

$userInternalId = $user->id;
$myDiff = time() - $user->last_date_daily_summary;
if($myDiff >= 24*3600){



if(isset($trackerId) && $trackerId != 0){
	$arrayAllUserTrackers = DB::table('_trackers')
	->where('id', '=', $trackerId)
	->where('user', '=', $userInternalId)
	->get();
}else{
	$arrayAllUserTrackers = DB::table('_trackers')
	->where('user', '=', $userInternalId)
	->get();
}




if(count($arrayAllUserTrackers) > 0){

if( !empty($range_type) && $range_type == 2 && !empty($date1) && !empty($date2) ){

//$date1 = Input::get('date1');
$date1 = explode("-",$date1);
$date1 = mktime( 0,0,0,$date1[0],$date1[1],$date1[2] );

//$date2 = Input::get('date2');
$date2 = explode("-",$date2);
$date2 = mktime( 0,0,0,$date2[0],$date2[1]+1,$date2[2] );

}else{

$date2 = time();
$date1 = time()-7*(60*60*24);

}

/*
$arrayGetAllTrackersData = DB::table('_trackers_data')
->leftJoin('_trackers', '_trackers_data.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', $userInternalId)
//->select(DB::raw(' _adwords_campaigns.name as campaign_name, _adwords_campaigns.adwords_campaign_id '))
//->orderBy('_adwords_campaigns.id', 'desc')
->get();
*/

$allTrackersArrayData = array();
$iCounterAllTrackers = 0;

$allTrackersClicksCount = 0;
$allTrackersCookiesCount = 0;
$allTrackersIPsCount = 0;

$allTrackersAlert1Views = 0;
$allTrackersAlert2Views = 0;
$allTrackersEmailSent = 0;
$allTrackersShownWarnings = 0;
$allTrackersIPBlocked = 0;
$allTrackersRedirectedAltUrl = 0;
$allTrackersPlacementBlocked = 0;

// ip Array
$ipArray = array();
// cookie Array
$cookieArray = array();
// location Array
$locationArray = array();
// keywords Array
$keywordArray = array();


foreach($arrayAllUserTrackers as $trackerItemArray){

$trackerId = $trackerItemArray->id;

$array = DB::table('_trackers_data')
->where('tracker_id', '=', $trackerId)
->where('click_timestamp', '>=', $date1)
->where('click_timestamp', '<=', $date2)
->get();

if(count($array) > 0){


$allTrackersClicksCount = $allTrackersClicksCount+count($array);


// ip Array
foreach($array as $dataItem){

	if( !in_array($dataItem->user_ip, $ipArray) ){
		$ipArray[] = $dataItem->user_ip;
	}

}

// cookie Array
foreach($array as $dataItem){

	if( !in_array($dataItem->user_cookies, $cookieArray) ){
		$cookieArray[] = $dataItem->user_cookies;
	}

}

// location Array
foreach($array as $dataItem){

$locationJson = json_decode($dataItem->ip_location);
if(
is_object($locationJson) && 
count($locationJson)>0 && 
isset($locationJson->location->cityName) && 
isset($locationJson->location->regionName) && 
isset($locationJson->location->countryCode)
){
$locationString = $locationJson->location->cityName . ", " . $locationJson->location->regionName . ", " . $locationJson->location->countryCode;

	if( !in_array($locationString, $locationArray) ){
		$locationArray[] = $locationString;
	}
}
}

// keyword Array
foreach($array as $dataItem){

$decodedJson = json_decode($dataItem->adwords_input_data);
if(isset($decodedJson->keyword)){
$sortString = $decodedJson->keyword;

	if( !in_array($sortString, $keywordArray) ){
		$keywordArray[] = $sortString;
	}
}
}








$trackerStatistic = DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $trackerId)
->get();

if(count($trackerStatistic)>0){
$allTrackersAlert1Views = $allTrackersAlert1Views+$trackerStatistic[0]->alert_1_views;
$allTrackersAlert2Views = $allTrackersAlert2Views+$trackerStatistic[0]->alert_2_views;
$allTrackersEmailSent = $allTrackersEmailSent+$trackerStatistic[0]->email_sent_count;
$allTrackersShownWarnings = $allTrackersShownWarnings+$trackerStatistic[0]->shown_warnings;
$allTrackersIPBlocked = $allTrackersIPBlocked+$trackerStatistic[0]->ip_blocked;
$allTrackersRedirectedAltUrl = $allTrackersRedirectedAltUrl+$trackerStatistic[0]->redirected_alt_url_count;
$allTrackersPlacementBlocked = $allTrackersPlacementBlocked+$trackerStatistic[0]->placement_blocked;
}

}
}


$allTrackersArrayData["all_statistic"]["clicks_count"] = $allTrackersClicksCount;
$allTrackersArrayData["all_statistic"]["cookies_count"] = count($cookieArray);
$allTrackersArrayData["all_statistic"]["ip_count"] = count($ipArray);

$allTrackersArrayData["all_statistic"]["alert_1_views"] = $allTrackersAlert1Views;
$allTrackersArrayData["all_statistic"]["alert_2_views"] = $allTrackersAlert2Views;
$allTrackersArrayData["all_statistic"]["email_sent"] = $allTrackersEmailSent;
$allTrackersArrayData["all_statistic"]["shown_warnings"] = $allTrackersShownWarnings;
$allTrackersArrayData["all_statistic"]["ip_blocked"] = $allTrackersIPBlocked;
$allTrackersArrayData["all_statistic"]["redirected_alt_url"] = $allTrackersRedirectedAltUrl;
$allTrackersArrayData["all_statistic"]["placement_blocked"] = $allTrackersPlacementBlocked;


			
			$data["text"] = '<div style="padding:20px;">
<h2>Activity Summary</h2>
<table class="reports-activity-summary" style="width:100%;">
<tr class="table-header-tr"><td class="td-left"><b>Activity</b></td><td class="td-right"><b>Count</b></td></tr>
<tr><td class="td-left first-td">Alert Level 1 Triggered</td><td class="td-right first-td">'.$allTrackersArrayData["all_statistic"]["alert_1_views"].'</td></tr>
<tr><td class="td-left">Alert Level 2 Triggered</td><td class="td-right">'.$allTrackersArrayData["all_statistic"]["alert_2_views"].'</td></tr>
<tr><td class="td-left first-td">IP Addresses blocked in Adwords</td><td class="td-right first-td">'.$allTrackersArrayData["all_statistic"]["ip_blocked"].'</td></tr>
<tr><td class="td-left">Email Notifications sent to you</td><td class="td-right">'.$allTrackersArrayData["all_statistic"]["email_sent"].'</td></tr>
<tr><td class="td-left first-td">Warnings shown to Visitors</td><td class="td-right first-td">'.$allTrackersArrayData["all_statistic"]["shown_warnings"].'</td></tr>
</table>
</div>';

			$query = DB::table('users')
			->where("id", "=", $user->id)
			->get();
			$data["user_to"] = $query;
			$data["subject"] = "Daily Summary";
			if(isset($data["user_to"][0]->email)){

				Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
						$message->to($data["user_to"][0]->email)->subject($data["subject"]);	
					});

				DB::table('users')
				->where('id', '=', $user->id)
				->update(array('last_date_daily_summary' => time()));
			
			}
/* --------------------------------------------- REPORTS */




}
}
}

}









}