<?php namespace App\Http\Controllers\Customer;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Payments\PaymentsController;
use View;
use Input;
use Redirect;
use DB;
use Auth;
use Excel;


use Hash;
use App\Http\Controllers\Adwords\InternalAdwordsController;
use Mail;
use App\Http\Controllers\Cronjob\CronjobController;
use Illuminate\Support\Str;
use Session;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\Campaign;
use Google\AdsApi\AdWords\v201809\cm\CampaignOperation;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\mcm\CustomerService;

use Google\AdsApi\AdWords\v201809\mcm\Customer;
use Google\AdsApi\AdWords\v201809\mcm\ManagedCustomerService;
use Google\AdsApi\AdWords\v201809\mcm\ManagedCustomerLink;
use Google\AdsApi\AdWords\v201809\mcm\LinkOperation;
use Google\AdsApi\AdWords\v201809\mcm\ServiceLink;
use Google\AdsApi\AdWords\v201809\mcm\ServiceLinkLinkStatus;
use Google\AdsApi\AdWords\v201809\mcm\ServiceLinkOperation;
use Google\AdsApi\AdWords\v201809\mcm\ServiceType;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;

use App\Http\Controllers\Tracker\TaskQueueManagerController;

class CustomerController extends BaseController {

	function getTest(){
		
		
		//$res = $this->addTrackingUrlCampaign("1752108172", 'https://tratestmymple.com/?season={_season}&promocode={_promocode}&u={lpurl}', 104);
		
		//$res = $this->addTrackingUrlAccount('https://tratestmyggg.com/?season={_season}&promocode={_promocode}&u={lpurl}', 104);
		
		$tmc = new InternalAdwordsController();
		
		$tmc->blockIPAdwordsFunction("1752108172", "53.53.67.67", 104);
		
	}

	function getPaynow(){

		return View::make('customer/payment_page');

	}



function postPaynow(){

	
	$amt = Input::get('amt');

	$PaymentsController = new PaymentsController();
	$resArray = $PaymentsController->createSubscriptionPayment($amt);

	if($resArray['response_status'] == 1){

		//var_dump($resArray['url']);
		return Redirect::to($resArray['url']);

	}else{

		exit("exit");

	}



}


function getPaymentSuccess(){


	$PayerID = Input::get('PayerID');
	$token = Input::get('token');


	// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
	if ( isset($token) && $token != "" ){

		
		$PaymentsController = new PaymentsController();
		//require_once ("paypalfunctions.php");

		/*
		'------------------------------------
		' Calls the GetExpressCheckoutDetails API call
		'
		' The GetShippingDetails function is defined in PayPalFunctions.jsp
		' included at the top of this file.
		'-------------------------------------------------
		*/
		

		$resArray = $PaymentsController->GetShippingDetails( $token );
		$ack = strtoupper($resArray["ACK"]);
		if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") 
		{
			/*
			' The information that is returned by the GetExpressCheckoutDetails call should be integrated by the partner into his Order Review 
			' page		
			*/
			$email 			= $resArray["EMAIL"]; // ' Email address of payer.
			$payerId 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
			$payerStatus		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
			//$salutation			= $resArray["SALUTATION"]; // ' Payer's salutation.
			$firstName			= $resArray["FIRSTNAME"]; // ' Payer's first name.
			//$middleName			= $resArray["MIDDLENAME"]; // ' Payer's middle name.
			$lastName			= $resArray["LASTNAME"]; // ' Payer's last name.
/*
			$suffix				= $resArray["SUFFIX"]; // ' Payer's suffix.
			$cntryCode			= $resArray["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.
			$business			= $resArray["BUSINESS"]; // ' Payer's business name.
			$shipToName			= $resArray["SHIPTONAME"]; // ' Person's name associated with this address.
			$shipToStreet		= $resArray["SHIPTOSTREET"]; // ' First street address.
			$shipToStreet2		= $resArray["SHIPTOSTREET2"]; // ' Second street address.
			$shipToCity			= $resArray["SHIPTOCITY"]; // ' Name of city.
			$shipToState		= $resArray["SHIPTOSTATE"]; // ' State or province
			$shipToCntryCode	= $resArray["SHIPTOCOUNTRYCODE"]; // ' Country code. 
			$shipToZip			= $resArray["SHIPTOZIP"]; // ' U.S. Zip code or other country-specific postal code.
			$addressStatus 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
			$invoiceNumber		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
			$phonNumber			= $resArray["PHONENUM"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 
*/
			$resArray = array("response_status" => 1, "name" => $lastName." ".$firstName, "amount" => session('Payment_Amount'));

		} 
		else  
		{
			//Display a user friendly Error on the page using any of the following error information returned by PayPal
			$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
			
			$message = "GetExpressCheckoutDetails API call failed. "
			. "Detailed Error Message: " . $ErrorLongMsg
			. "Short Error Message: " . $ErrorShortMsg
			. "Error Code: " . $ErrorCode
			. "Error Severity Code: " . $ErrorSeverityCode;

			$resArray = array("response_status" => 0, "message" => $message);

		}
	}

	return View::make('customer/payment_success', array(
		"data" => $resArray
	));


}



function postPaymentConfirm(){

	
	$PaymentsController = new PaymentsController();
	$finalPaymentAmount = session('Payment_Amount');
	

	$resArray = $PaymentsController->ConfirmPayment ( $finalPaymentAmount ); 
	$ack = strtoupper($resArray["ACK"]);
//var_dump($resArray);
	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
	{

		DB::table('users_payments')
		->insert(array(
			"user" => Auth::user()->id, 
			"payment_method" => $resArray["TRANSACTIONID"], 
			"amount" => $resArray["AMT"]
			)
		);


		DB::table('users')
		->where("id", "=", Auth::user()->id)
		->update(array("trial" => 2));


/*

		$transactionId		= $resArray["TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
		$transactionType 	= $resArray["TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
		$paymentType		= $resArray["PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
		$orderTime 			= $resArray["ORDERTIME"];  //' Time/date stamp of payment
		$amt				= $resArray["AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
		$currencyCode		= $resArray["CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
		$feeAmt				= $resArray["FEEAMT"];  //' PayPal fee amount charged for the transaction
		$settleAmt			= $resArray["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
		$taxAmt				= $resArray["TAXAMT"];  //' Tax charged on the transaction.
		$exchangeRate		= $resArray["EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer’s account.
		
		
		
		$paymentStatus	= $resArray["PAYMENTSTATUS"]; 


		$pendingReason	= $resArray["PENDINGREASON"];  

		$reasonCode		= $resArray["REASONCODE"]; 
		*/
		echo "Thank you for your payment.";



	}
	else  
	{
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		
		echo "GetExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;
	}

	



	$resArray = $PaymentsController->CreateRecurringPaymentsProfile();
	$ack = strtoupper($resArray["ACK"]);

	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" ){
				
		$array = DB::table('users')
		->where("id", "=", Auth::user()->id)
		->update(array("billing_subscription_id" => $resArray["PROFILEID"]));

		return Redirect::to("/user/settings/");

	}else{

		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		
		echo "GetExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;
	}

}



	function getWelcome(){

		return View::make('customer/welcome_page');

	}


	function getAdwordsCampaigns(){

		$internalAdwordsController = new InternalAdwordsController();
		$internalAdwordsController->getCampaignListByUser(Auth::user()->id);

	}

function getCloseAccessAdwords(){


$internalAdwordsController = new InternalAdwordsController();
//$internalAdwordsController->setLinkBetweenAccounts("2214096916");

$arrayUser = DB::table('_adwords_users')
->where('internal_user_id', '=', Auth::user()->id)
->get();


$clientCustomerId = $arrayUser[0]->adwords_user_id;
$internalAdwordsController->deleteLinkBetweenAccounts($clientCustomerId);


DB::table('_adwords_users_nonmanager')
->where('manager_adwords_id', '=', $arrayUser[0]->adwords_user_id)
->delete();

DB::table('_adwords_campaigns')
->where('manager_adwords_id', '=', $arrayUser[0]->adwords_user_id)
->delete();

DB::table('_adwords_users')
->where('adwords_user_id', '=', $arrayUser[0]->adwords_user_id)
->delete();


}


function getReportsAll(){



// Statistic, Reports, Data Visualization
$arrayAllUserTrackers = DB::table('_trackers')
->where('user', '=', Auth::user()->id)
->get();

if(count($arrayAllUserTrackers) > 0){

/*
if( !empty(Input::get('range_type')) && Input::get('range_type') == 2 && !empty(Input::get('date1')) && !empty(Input::get('date2')) ){

$date1 = Input::get('date1');
$date1 = explode("-",$date1);
$date1 = mktime( 0,0,0,$date1[0],$date1[1],$date1[2] );

$date2 = Input::get('date2');
$date2 = explode("-",$date2);
$date2 = mktime( 0,0,0,$date2[0],$date2[1]+1,$date2[2] );

}else{

$date2 = time();
$date1 = time()-7*(60*60*24);

}
*/



$allTrackersArrayData = DB::table('_trackers_data')
->leftJoin('_trackers', '_trackers_data.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', Auth::user()->id)
//->where('_trackers_data.click_timestamp', '>=', $date1)
//->where('_trackers_data.click_timestamp', '<=', $date2)
//->where('_trackers_data.user_browser', '!=', 'Google-Adwords-Instant (+http://www.google.com/adsbot.html)')
->orderBy('_trackers_data.click_timestamp', 'desc')
->Paginate(50);
//->get();


}

if(isset($allTrackersArrayData)){

return View::make('customer/report_all_clicks', array(
"data" => $allTrackersArrayData
));

}else{

return redirect("/user/main/");

}

}


function getMain(){

$cronController = new CronjobController();
$cronController->test();
$cronController->test2();


$get_adwords_data = Input::get('get_adwords_data');
if($get_adwords_data == 1){
//retrieving user's adwords campaigns data


}


$trackerId = 0;
$date1 = Input::get('date1');
$date2 = Input::get('date2');
$range_type = Input::get('range_type');

$data_main = $this->createReportsDataFull($trackerId, $date1, $date2, $range_type);


$userDataArray = DB::table('users')
->where("id", "=", Auth::user()->id)
->get();

$data = $data_main["data"];
$data["user_data"] = $userDataArray;

$array = DB::table('_adwords_users')
->where("internal_user_id", "=", Auth::user()->id)
->get();

if(isset($array[0]) && count($array[0])>0){
$data["adwords_user_exist"] = 1;
}else{
$data["adwords_user_exist"] = 0;
}


/*
if( isset($userDataArray[0]->braintree_customer_id) && $userDataArray[0]->braintree_customer_id != "" ){
// Here should be check Brain tree data is act?
$data["braintree_user"] = 1;
}else{
$data["braintree_user"] = 0;
}
*/


return View::make('customer/index', array(
"data" => $data, 
"trackers_data" => $data_main["trackers_data"]
));


}






public function getUserAwordsCampaigns(){

// add rule to check if campaign ID used in any other trackers of this user, we don't include this in the list
$array2 = DB::table('_adwords_campaigns')
->leftJoin('_adwords_users', '_adwords_campaigns.manager_adwords_id', '=', '_adwords_users.adwords_user_id')
->where('_adwords_users.internal_user_id', '=', Auth::user()->id)
->select(DB::raw(' _adwords_campaigns.name as campaign_name, _adwords_campaigns.adwords_campaign_id '))
->orderBy('_adwords_campaigns.id', 'desc')
->get();

return $array2;

}

function getAdwCampaigns(){


$array2 = DB::table('_adwords_campaigns')
->leftJoin('_adwords_users', '_adwords_campaigns.manager_adwords_id', '=', '_adwords_users.adwords_user_id')
->where('_adwords_users.internal_user_id', '=', Auth::user()->id)
->select(DB::raw(' _adwords_campaigns.name as campaign_name, _adwords_campaigns.adwords_campaign_id '))
->orderBy('_adwords_campaigns.id', 'desc')
->get();
//->Paginate(4);


return View::make('customer/adw_campaigns', array(
"adwordsArray" => $array2
));

}


function getTrackers(){


$array2 = DB::table('_trackers')
//->leftJoin('_adwords_keywords', '_adwords_keywords_status.keyword_id', '=', '_adwords_keywords.id')
//->leftJoin('_adwords_users', '_adwords_campaigns.manager_adwords_id', '=', '_adwords_users.adwords_user_id')
->where('_trackers.user', '=', Auth::user()->id)
->where('_trackers.is_deleted', '=', 0)
//->select(DB::raw(' _adwords_campaigns.name as campaign_name, _adwords_campaigns.adwords_campaign_id '))
->orderBy('_trackers.id', 'desc')
->get();
//->Paginate(4);


return View::make('customer/trackers', array(
"adwordsArray" => $array2
));

}

function getAddTracker(){

$array_level_1 = DB::table('_trackers_rules_default')
->leftJoin('_trackers_rules_users', '_trackers_rules_default.rule_id', '=', '_trackers_rules_users.id')
->where('_trackers_rules_default.level', '=', 1)
->select(DB::raw(' _trackers_rules_users.id, _trackers_rules_users.number_of_clicks, _trackers_rules_users.time_amount, _trackers_rules_users.alert_message, _trackers_rules_users.send_alert, _trackers_rules_users.block_ip, _trackers_rules_users.show_message '))
->get();

$array_level_2 = DB::table('_trackers_rules_default')
->leftJoin('_trackers_rules_users', '_trackers_rules_default.rule_id', '=', '_trackers_rules_users.id')
->where('_trackers_rules_default.level', '=', 2)
->select(DB::raw(' _trackers_rules_users.id, _trackers_rules_users.number_of_clicks, _trackers_rules_users.time_amount, _trackers_rules_users.alert_message, _trackers_rules_users.send_alert, _trackers_rules_users.block_ip, _trackers_rules_users.show_message '))
->get();

$object = (object) ['form_url' => '/user/add-tracker/', 'rules_array_1' => $array_level_1, 'rules_array_2' => $array_level_2 ];
$array[] = $object;

return View::make('customer/tracker_add', array(
"data" => $array
));

}





function getReportsPage(){

$trackerId = 0;
$date1 = Input::get('date1');
$date2 = Input::get('date2');
$range_type = Input::get('range_type');

$data_main = $this->createReportsDataFull($trackerId, $date1, $date2, $range_type);


$userDataArray = DB::table('users')
->where("id", "=", Auth::user()->id)
->get();

$data = $data_main["data"];
$data["user_data"] = $userDataArray;

$array = DB::table('_adwords_users')
->where("internal_user_id", "=", Auth::user()->id)
->get();

if(isset($array[0]) && count($array[0])>0){
$data["adwords_user_exist"] = 1;
}else{
$data["adwords_user_exist"] = 0;
}

if( isset($userDataArray[0]->braintree_customer_id) && $userDataArray[0]->braintree_customer_id != "" ){
// Here should be check Brain tree data is act?
$data["braintree_user"] = 1;
}else{
$data["braintree_user"] = 0;
}



return View::make('customer/reports_page', array(
"data" => $data, 
"trackers_data" => $data_main["trackers_data"]
));


}





























function getEditTracker(){

if(!empty(Input::get('id')) && Input::get('id')!= 0){
$tracker_id = Input::get('id');
/*
$array2 = DB::table('_trackers')
->leftJoin('_trackers_rules', '_trackers.id', '=', '_trackers_rules.tracker_id')
->leftJoin('_trackers_rules_users', '_trackers_rules.rule_id', '=', '_trackers_rules_users.id')
->where('_trackers.user', '=', Auth::user()->id)
->where('_trackers.id', '=', $tracker_id)
->orderBy('_trackers.id', 'desc')
->get();
*/


$array1 = DB::table('_trackers')
->where('_trackers.user', '=', Auth::user()->id)
->where('_trackers.id', '=', $tracker_id)
->orderBy('_trackers.id', 'desc')
->get();


$array2 = DB::table('_trackers_rules')
->leftJoin('_trackers_rules_users', '_trackers_rules.rule_id', '=', '_trackers_rules_users.id')
->where('_trackers_rules.tracker_id', '=', $tracker_id)
->where('_trackers_rules_users.alert_level', '=', 1)
->select(DB::raw(' _trackers_rules_users.id, _trackers_rules_users.number_of_clicks, _trackers_rules_users.time_amount, _trackers_rules_users.alert_message, _trackers_rules_users.send_alert, _trackers_rules_users.block_ip, _trackers_rules_users.show_message '))
->get();

$array3 = DB::table('_trackers_rules')
->leftJoin('_trackers_rules_users', '_trackers_rules.rule_id', '=', '_trackers_rules_users.id')
->where('_trackers_rules.tracker_id', '=', $tracker_id)
->where('_trackers_rules_users.alert_level', '=', 2)
->select(DB::raw(' _trackers_rules_users.id, _trackers_rules_users.number_of_clicks, _trackers_rules_users.time_amount, _trackers_rules_users.alert_message, _trackers_rules_users.send_alert, _trackers_rules_users.block_ip, _trackers_rules_users.show_message, _trackers_rules_users.act '))
->get();

$selectedCampaignName = "";
if($array1[0]->tracking_item != "" && $array1[0]->tracking_item != 0 && $array1[0]->tracking_level == 2){
	$selectedCampaignQuery = DB::table('_adwords_campaigns')
	->where('adwords_campaign_id', '=', $array1[0]->tracking_item)
	->get();
	$selectedCampaignName = $selectedCampaignQuery[0]->name;
}

$array1[0]->rules_array_1 = $array2;
$array1[0]->rules_array_2 = $array3;
$array1[0]->form_url = "/user/edit-tracker/";
$array1[0]->selected_campaign_name = $selectedCampaignName;

return View::make('customer/tracker_add', array(
"data" => $array1
));
}else{
return "Error! Tracker doesn't exist.";
}

}


function postAddTracker(){

if(isset(Auth::user()->id) && Auth::user()->id!=0){

$tracker_name = Input::get('tracker-name');
$tracker_level = Input::get('tracker-level');


if( Input::get('tracker-level') == 1){

// Update tracker data
DB::table('_trackers')
->where('user', '=', Auth::user()->id)
->update(array(
'act' => 0
)
);

$act = 1;
$tracker_item_id = 0;

}

if( Input::get('tracker-level') == 2 ){

$accountTracker = DB::table('_trackers')
->where('user', '=', Auth::user()->id)
->where('tracking_level', '=', 1)
->where('act', '=', 1)
->take(1)
->get();
if(count($accountTracker) > 0){$act = 0;}else{$act = 1;}

$tracker_item_id = Input::get('selected-campaign');

}


$landing_page = Input::get('final_url');
$email_1 = Input::get('email-1');
$email_2 = Input::get('email-2');
//alert #1
$alert_1_clicks = Input::get('alert-1-clicks');
$alert_1_time_period = Input::get('alert-1-time-period');
$alert_1_is_send_email = Input::get('alert-1-is-send-email');
$alert_1_add_ip_block = Input::get('alert-1-add-ip-block');
$alert_1_show_warning = Input::get('alert-1-show-warning');
$alert_1_warning_text = Input::get('alert-1-warning-text');
//alert #2
$alert_2_clicks = Input::get('alert-2-clicks');
$alert_2_time_period = Input::get('alert-2-time-period');
$alert_2_is_send_email = Input::get('alert-2-is-send-email');
$alert_2_add_ip_block = Input::get('alert-2-add-ip-block');
$alert_2_show_warning = Input::get('alert-2-show-warning');
$alert_2_warning_text = Input::get('alert-2-warning-text');
$alert_2_act = Input::get('alert-2-act');

// Insert tracker data
$tracker_id = DB::table('_trackers')->insertGetId(array(

'name' => $tracker_name,
'user' => Auth::user()->id,
'tracking_level' => $tracker_level,
'tracking_item' => $tracker_item_id,
'landing_page' => $landing_page,
'email_1_notification' => $email_1,
'email_2_notification' => $email_2,
'act' => $act
)
);

// Insert alert #1 data
$alert_1_id = DB::table('_trackers_rules_users')->insertGetId(array(

'user' => Auth::user()->id,
'number_of_clicks' => $alert_1_clicks,
'time_amount' => $alert_1_time_period,
'send_alert' => $alert_1_is_send_email,
'block_ip' => $alert_1_add_ip_block,
'show_message' => $alert_1_show_warning,
'alert_message' => $alert_1_warning_text,
'alert_level' => 1

)
);

// Insert alert #2 data
$alert_2_id = DB::table('_trackers_rules_users')->insertGetId(array(

'user' => Auth::user()->id,
'number_of_clicks' => $alert_2_clicks,
'time_amount' => $alert_2_time_period,
'send_alert' => $alert_2_is_send_email,
'block_ip' => $alert_2_add_ip_block,
'show_message' => $alert_2_show_warning,
'alert_message' => $alert_2_warning_text,
'alert_level' => 2,
'act' => $alert_2_act

)
);


// #1
DB::table('_trackers_rules')->insert(array(

'tracker_id' => $tracker_id,
'rule_id' => $alert_1_id

)
);

// #2
DB::table('_trackers_rules')->insert(array(

'tracker_id' => $tracker_id,
'rule_id' => $alert_2_id

)
);

// Insert tracker data statistic

$arrayStatistic = DB::table('_trackers_data_statistic')
->where('id', '=', $tracker_id)
->get();
if(count($arrayStatistic)<1){
DB::table('_trackers_data_statistic')->insert(array(
'tracker_id' => $tracker_id
)
);
}

//add tracking url to adwords
if( Input::get('tracker-level') == 2 ){
$trackingUrl = "http://www.account.clickmonitor.co.uk/tracker/ad-redirect/?url={lpurl}&tracker_id=".$tracker_id."&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}";
$this->addTrackingUrlCampaign($tracker_item_id, $trackingUrl, Auth::user()->id);
}

//add tracking url to adwords
if( Input::get('tracker-level') == 1 ){
$trackingUrl = "http://www.account.clickmonitor.co.uk/tracker/ad-redirect/?url={lpurl}&tracker_id=".$tracker_id."&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}";
$this->addTrackingUrlAccount($trackingUrl, Auth::user()->id);
}



return redirect('user/trackers');

}else{
return Redirect::back()->withErrors(['Error, you haven\'t permissions']);
}
}


	public function addTrackingUrlCampaign($campaignId, $trackingUrl, $internalUserId) {

        $internalAdwordsController = new InternalAdwordsController();
		$arrayAdwordsSettings = $internalAdwordsController->getAdwordsSettings();
		
		$queryCampaign = DB::table('_adwords_campaigns')
			->where('adwords_campaign_id', '=', $campaignId)
			->get();


		if(isset($queryCampaign[0]) && count($queryCampaign[0]) > 0){
				
			$arrayCampaign = (array)$queryCampaign[0];

			$campaignUser = $arrayCampaign['adwords_user_id'];
			$campaignManager = $arrayCampaign['manager_adwords_id'];

			//get refresh token

			$queryAdwordsUser = DB::table('_adwords_users')
				->where('adwords_user_id', '=', $campaignManager)
				->where('internal_user_id', '=', $internalUserId)
				->get();

			if(isset($queryAdwordsUser[0]) && count($queryAdwordsUser[0]) > 0){

				$arrayAdwordsUser = (array)$queryAdwordsUser[0];

				$session = $internalAdwordsController->setSessionWithIdAndRefreshToken($campaignUser, $arrayAdwordsUser['adwords_refresh_token']);
				
				$adWordsServices = new AdWordsServices();
				$campaignService = $adWordsServices->get($session, CampaignService::class);

				 // Create campaign using an existing ID.
				$campaign = new Campaign();
				$campaign->setId($campaignId);
				$campaign->setTrackingUrlTemplate($trackingUrl);



				//  "http://www.account.clickmonitor.co.uk/?season={_season}&promocode={_promocode}&u={lpurl}";


				/*
				// http://www.account.clickmonitor.co.uk/?keyword={keyword}&placement={placement}&{loc_physical_ms}&{network}&u={lpurl}

				// {loc_physical_ms} id of location in google https://developers.google.com/adwords/api/docs/appendix/geotargeting

				// Since the tracking URL has two custom parameters, provide their
				// values too. This can be provided at campaign, ad group, ad, criterion
				// or feed item levels.
				$seasonParameter = new CustomParameter();
				$seasonParameter->key = "season";
				$seasonParameter->value = "spring";

				$promoCodeParameter = new CustomParameter();
				$promoCodeParameter->key = "promocode";
				$promoCodeParameter->value = "NYC123";

				$campaign->urlCustomParameters = new CustomParameters();
				$campaign->urlCustomParameters->parameters = array( $seasonParameter, $promoCodeParameter );
				*/
			  // Create operation.
				$operation = new CampaignOperation();
				//$operation->operand = $campaign;
				//$operation->operator = 'SET';
				$operation->setOperand($campaign);
				$operation->setOperator(Operator::SET);

				$operations[] = $operation;

				  // Make the mutate request.
				$result = $campaignService->mutate($operations);

				  // Display result.
				return 1;
				

			}
			
		}

	}


	public function addTrackingUrlAccount($trackingUrl, $internalUserId) {

        $internalAdwordsController = new InternalAdwordsController();
		$arrayAdwordsSettings = $internalAdwordsController->getAdwordsSettings();

		$queryAdwordsUser = DB::table('_adwords_users')
			->where('internal_user_id', '=', $internalUserId)
			->get();

		if(isset($queryAdwordsUser[0]) && count($queryAdwordsUser[0]) > 0){

			$arrayAdwordsUser = (array)$queryAdwordsUser[0];

			$session = $internalAdwordsController->setSessionWithIdAndRefreshToken($queryAdwordsUser[0]->adwords_user_id, $arrayAdwordsUser['adwords_refresh_token']);

			$adWordsServices = new AdWordsServices();
			$customerService = $adWordsServices->get($session, CustomerService::class);

			$customer = new Customer();
			$customer->setId($queryAdwordsUser[0]->adwords_user_id);
			$customer->setTrackingUrlTemplate($trackingUrl);

			$result = $customerService->mutate($customer);

		  // Display result.
			return 1;
			
			//var_dump($result);

		}

	}








	function postEditTracker(){

	//var_dump(Input::get('tracker-id'));

	if(!empty(Input::get('tracker-id')) && Input::get('tracker-id')!=0){
	//if(isset(Auth::user()->id) && Auth::user()->id!=0){


	$tracker_name = Input::get('tracker-name');
	$tracker_level = Input::get('tracker-level');


	if( Input::get('tracker-level') == 1){


	// Update tracker data
	DB::table('_trackers')
	->where('user', '=', Auth::user()->id)
	->update(array(
	'act' => 0
	)
	);
	$act = 1;

	}

	if( Input::get('tracker-level') == 2 ){

	$accountTracker = DB::table('_trackers')
	->where('user', '=', Auth::user()->id)
	->where('tracking_level', '=', 1)
	->where('act', '=', 1)
	->take(1)
	->get();

	if(count($accountTracker) > 0){$act = 0;}else{$act = 1;}

	}

	if(Input::get('tracker-level') == 2){$tracker_item_id = Input::get('selected-campaign');}else{$tracker_item_id = 0;}
	$landing_page = Input::get('final_url');
	$email_1 = Input::get('email-1');
	$email_2 = Input::get('email-2');
	//alert #1
	$alert_1_clicks = Input::get('alert-1-clicks');
	$alert_1_time_period = Input::get('alert-1-time-period');
	$alert_1_is_send_email = Input::get('alert-1-is-send-email');
	$alert_1_add_ip_block = Input::get('alert-1-add-ip-block');
	$alert_1_show_warning = Input::get('alert-1-show-warning');
	$alert_1_warning_text = Input::get('alert-1-warning-text');
	//alert #2
	$alert_2_act = Input::get('alert-2-act');
	$alert_2_clicks = Input::get('alert-2-clicks');
	$alert_2_time_period = Input::get('alert-2-time-period');
	$alert_2_is_send_email = Input::get('alert-2-is-send-email');
	$alert_2_add_ip_block = Input::get('alert-2-add-ip-block');
	$alert_2_show_warning = Input::get('alert-2-show-warning');
	$alert_2_warning_text = Input::get('alert-2-warning-text');


	// Update tracker data
	DB::table('_trackers')
	->where('id', '=', Input::get('tracker-id'))
	->where('user', '=', Auth::user()->id)
	->update(array(
	'name' => $tracker_name,
	'tracking_level' => $tracker_level,
	'tracking_item' => $tracker_item_id,
	'landing_page' => $landing_page,
	'email_1_notification' => $email_1,
	'email_2_notification' => $email_2,
	'act' => $act
	)
	)
	;

	//var_dump(Input::get('alert-2-time-period'));

	// Update alert #1 data
	DB::table('_trackers_rules_users')
	->where('id', '=', Input::get('alert-1-id'))
	->where('user', '=', Auth::user()->id)
	->where('alert_level', '=', 1)
	->update(
	array(
	'number_of_clicks' => $alert_1_clicks,
	'time_amount' => $alert_1_time_period,
	'send_alert' => $alert_1_is_send_email,
	'block_ip' => $alert_1_add_ip_block,
	'show_message' => $alert_1_show_warning,
	'alert_message' => $alert_1_warning_text,
	)

	);


	// Update alert #2 data
	DB::table('_trackers_rules_users')
	->where('id', '=', Input::get('alert-2-id'))
	->where('user', '=', Auth::user()->id)
	->where('alert_level', '=', 2)
	->update(
	array(
	'number_of_clicks' => $alert_2_clicks,
	'time_amount' => $alert_2_time_period,
	'send_alert' => $alert_2_is_send_email,
	'block_ip' => $alert_2_add_ip_block,
	'show_message' => $alert_2_show_warning,
	'alert_message' => $alert_2_warning_text,
	'act' => $alert_2_act
	)

	);


	//add tracking url to adwords
	if( Input::get('tracker-level') == 2 ){

	$trackingUrl = "";
	$this->addTrackingUrlAccount($trackingUrl, Auth::user()->id);

	$trackingUrl = "http://www.account.clickmonitor.co.uk/tracker/ad-redirect/?url={lpurl}&tracker_id=".Input::get('tracker-id')."&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}";
	$this->addTrackingUrlCampaign($tracker_item_id, $trackingUrl, Auth::user()->id);

	}

	//add tracking url to adwords
	if( Input::get('tracker-level') == 1 ){
	$trackingUrl = "http://www.account.clickmonitor.co.uk/tracker/ad-redirect/?url={lpurl}&tracker_id=".Input::get('tracker-id')."&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}";
	$this->addTrackingUrlAccount($trackingUrl, Auth::user()->id);
	}



	return redirect('user/trackers');
	//return Redirect::back()->withErrors(['Data was saved']);

	}else{
	return redirect('user/trackers');
	//return Redirect::back()->withErrors(['Error, you haven\'t permissions']);
	}
	}

	function getUpdateActTracker(){

	// if exist and active account tracker campaign trackers will not work
	if( Input::get('act') == 1 ){

	//get ID act



	$accountTracker = DB::table('_trackers')
	->where('tracking_level', '=', 1)
	->where('user', '=', Auth::user()->id)
	->where('act', '=', 1)
	->get();

	if(count($accountTracker) > 0){

	// Update tracker data
	DB::table('_trackers')
	->where('user', '=', Auth::user()->id)
	->update(array(
	'act' => 0
	)
	);

	}

	}





	$trackerArray = DB::table('_trackers')
	->where('id', '=', Input::get('tracker_id'))
	->where('user', '=', Auth::user()->id)
	->get();

	if( Input::get('act') == 0 ){
		$trackingUrl = "";
	}

	if( Input::get('act') == 1 ){
		//add tracking url to adwords
		if( $trackerArray[0]->tracking_level == 2 ){
			$trackingUrl = "http://www.account.clickmonitor.co.uk/tracker/ad-redirect/?url={lpurl}&tracker_id=".Input::get('tracker_id')."&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}";
		}

		//add tracking url to adwords
		if( $trackerArray[0]->tracking_level == 1 ){
			$trackingUrl = "http://www.account.clickmonitor.co.uk/tracker/ad-redirect/?url={lpurl}&tracker_id=".Input::get('tracker_id')."&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}";
		}
	}



	if( isset($trackerArray[0]->tracking_level) && $trackerArray[0]->tracking_level == 2 ){
		if( isset($trackerArray[0]->tracking_item) ){
			$this->addTrackingUrlCampaign($trackerArray[0]->tracking_item, $trackingUrl, Auth::user()->id);
		}
	}

	//add tracking url to adwords
	if( isset($trackerArray[0]->tracking_level) && $trackerArray[0]->tracking_level == 1 ){
		$this->addTrackingUrlAccount($trackingUrl, Auth::user()->id);
	}






	// Update tracker data
	DB::table('_trackers')
	->where('id', '=', Input::get('tracker_id'))
	->where('user', '=', Auth::user()->id)
	->update(array(
	'act' => Input::get('act')
	)
	);

		return Redirect::back()->withErrors(['Data was saved']);

	}


	// Delete Tracker
	function getDeleteTracker(){

	// checking account tracker !!!!!!!!!!!!

		if( !empty(Input::get('tracker_id') ) ){


		$trackerArray = DB::table('_trackers')
			->where('id', '=', Input::get('tracker_id'))
			->where('user', '=', Auth::user()->id)
			->get();


		if( isset($trackerArray[0]->tracking_level) && $trackerArray[0]->tracking_level == 2 ){
		$trackingUrl = "";
		if( isset($trackerArray[0]->tracking_item) ){
		$this->addTrackingUrlCampaign($trackerArray[0]->tracking_item, $trackingUrl, Auth::user()->id);
		}
		}

		//add tracking url to adwords
		if( isset($trackerArray[0]->tracking_level) && $trackerArray[0]->tracking_level == 1 ){
		$trackingUrl = "";
		$this->addTrackingUrlAccount($trackingUrl, Auth::user()->id);
		}



		/*
		DB::table('_trackers')
		->where('id', '=', Input::get('tracker_id'))
		->where('user', '=', Auth::user()->id)
		->delete();
		*/
		DB::table('_trackers')
			->where('id', '=', Input::get('tracker_id'))
			->where('user', '=', Auth::user()->id)
			->update(array('is_deleted' => 1));


			return Redirect::back();
			
		}

	}








public function createReportsDataFull($trackerId, $date1, $date2, $range_type){


// Statistic, Reports, Data Visualization

if(isset($trackerId) && $trackerId != 0){
	$arrayAllUserTrackers = DB::table('_trackers')
	->where('id', '=', $trackerId)
	->where('user', '=', Auth::user()->id)
	->get();
}else{
	$arrayAllUserTrackers = DB::table('_trackers')
	->where('user', '=', Auth::user()->id)
	->get();
}




if(count($arrayAllUserTrackers) > 0){

if( !empty($range_type) && $range_type == 2 && !empty($date1) && !empty($date2) ){

//$date1 = Input::get('date1');
$date1 = explode("-",$date1);
$date1 = mktime( 0,0,0,$date1[1],$date1[0],$date1[2] );

//$date2 = Input::get('date2');
$date2 = explode("-",$date2);
$date2 = mktime( 0,0,0,$date2[1],$date2[0]+1,$date2[2] );

}elseif($range_type == 3){
//today
$date1 = mktime( 0,0,0,date("n"),date("j"),date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j")+1,date("Y") );

}elseif($range_type == 4){
//yesterday
$date1 = mktime( 0,0,0,date("n"),date("j")-1,date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

}elseif($range_type == 5){
//last week
//$date1 = mktime( 0,0,0,date("n"),date("j")-7,date("Y") );
//$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

	if( date('w') == 1){
		$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );
	}else{
		$date2 = strtotime( "previous monday" );
	}

	$date1 = $date2-7*(60*60*24);

}elseif($range_type == 6){
	//this week
	$date2 = strtotime( "next monday" );

	if( date('w') == 1){
		$date1 = mktime( 0,0,0,date("n"),date("j"),date("Y") );
	}else{
		$date1 = strtotime( "previous monday" );
	}

}elseif($range_type == 7){
//last 14 days
$date1 = mktime( 0,0,0,date("n"),date("j")-14,date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

}elseif($range_type == 8){
//last 30 days
$date1 = mktime( 0,0,0,date("n"),date("j")-30,date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

}elseif($range_type == 9){
//last month
$date1 = mktime (0, 0, 0, date("m")-1, 1, date("Y"));
$date2 = mktime (0, 0, 0, date("m")-1, date('t'), date("Y"));

}elseif($range_type == 10){
//this month
$date1 = mktime (0, 0, 0, date("m"), 1, date("Y"));
$date2 = mktime (0, 0, 0, date("m"), date('t'), date("Y"));

}else{

$date2 = mktime( 0,0,0,date("n"),date("j")+1,date("Y") );
$date1 = mktime( 0,0,0,date("n"),date("j")-6,date("Y") );

}

/*
$arrayGetAllTrackersData = DB::table('_trackers_data')
->leftJoin('_trackers', '_trackers_data.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', Auth::user()->id)
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


/* --------------------------------------------- REPORTS */



// TOP clickers/locations/keywords/cookies

$array = DB::table('_trackers_data')
->leftJoin('_trackers', '_trackers_data.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', Auth::user()->id)
->where('click_timestamp', '>=', $date1)
->where('click_timestamp', '<=', $date2)
->get();



// ip Array
$ipArray = array();
// cookie Array
$cookieArray = array();
// location Array
$locationArray = array();
// keywords Array
$keywordArray = array();
//countryArray
$countryArray = array();


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
if($dataItem->ip_location != ""){
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
}

// location Array
foreach($array as $dataItem){

$countryJson = json_decode($dataItem->ip_location);
if(is_object($countryJson) && count($countryJson)>0 && isset($countryJson->location->countryCode)){
$countryString = $countryJson->location->countryCode;

	if( !in_array($countryString, $countryArray) ){
		$countryArray[] = $countryString;
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






// Location Report
$resultArray = array();
$i=0;

foreach($locationArray as $arraySortItem){

$resultArray[$i]["sortParameter"] = $arraySortItem;
$clickArray = array();

	foreach($array as $dataItem){
	if(isset($dataItem->ip_location)){
$locationJson = json_decode($dataItem->ip_location);
if( isset($locationJson->location->cityName) && isset($locationJson->location->regionName) && isset($locationJson->location->countryCode) ){
$locationString = $locationJson->location->cityName . ", " . $locationJson->location->regionName . ", " . $locationJson->location->countryCode;
		if($locationString == $arraySortItem){

			$clickArray[] = $dataItem;
			}
		}
	}
	}
$resultArray[$i]["data"] = count($clickArray);
$i++;
}

$resultLocationArray = $resultArray;

// Country Report
$resultArray = array();
$i=0;

foreach($countryArray as $arraySortItem){

$resultArray[$i]["sortParameter"] = $arraySortItem;
$clickArray = array();

	foreach($array as $dataItem){
	    if($dataItem->ip_location != ""){

		$locationJson = json_decode($dataItem->ip_location);
		if(isset($locationJson->location->countryCode)){
			$locationString = $locationJson->location->countryCode;
				if($locationString == $arraySortItem){
					$clickArray[] = $dataItem;		
				}
			}
		}
	}
$resultArray[$i]["data"] = count($clickArray);
$i++;
}

$resultCountryArray = $resultArray;


// IP Report

$resultArray = array();
$i=0;

foreach($ipArray as $ipItem){

$resultArray[$i]["sortParameter"] = $ipItem;
$clickArray = array();

	foreach($array as $dataItem){

		if($dataItem->user_ip == $ipItem){

			$clickArray[] = $dataItem;
			
		}

	}
$resultArray[$i]["data"] = count($clickArray);
$i++;
}

$resultIpArray = $resultArray;


// Keyword Report

$resultArray = array();
$i=0;

foreach($keywordArray as $arraySortItem){

$resultArray[$i]["sortParameter"] = $arraySortItem;
$clickArray = array();

	foreach($array as $dataItem){

		$decodedJson = json_decode($dataItem->adwords_input_data);
if(is_object($decodedJson) && count($decodedJson)>0 && isset($decodedJson->keyword)){
		$sortString = $decodedJson->keyword;

		if($sortString == $arraySortItem){

			$clickArray[] = $dataItem;
			
		}
}
	}
$resultArray[$i]["data"] = count($clickArray);
$i++;
}

$resultKeywordArray = $resultArray;


// Cookie Report

$resultArray = array();
$i=0;

foreach($cookieArray as $arraySortItem){

$resultArray[$i]["sortParameter"] = $arraySortItem;
$clickArray = array();

	foreach($array as $dataItem){

		$sortString = $dataItem->user_cookies;

		if($sortString == $arraySortItem){

			$clickArray[] = $dataItem;
			
		}

	}
$resultArray[$i]["data"] = count($clickArray);
$i++;
}

$resultCookieArray = $resultArray;



//---
$sortCount = array();
foreach ($resultIpArray as $key => $row)
{
    $sortCount[$key] = $row['data'];
}
array_multisort($sortCount, SORT_DESC, $resultIpArray);

$resultIpArray = array_slice($resultIpArray, 0, 10, true);
//---


//---
$sortCount = array();
foreach ($resultLocationArray as $key => $row)
{
    $sortCount[$key] = $row['data'];
}
array_multisort($sortCount, SORT_DESC, $resultLocationArray);

$resultLocationArray = array_slice($resultLocationArray, 0, 10, true);
//---


//---
$sortCount = array();
foreach ($resultCountryArray as $key => $row)
{
    $sortCount[$key] = $row['data'];
}
array_multisort($sortCount, SORT_DESC, $resultCountryArray);

$resultCountryArray = array_slice($resultCountryArray, 0, 10, true);
//---



//---
$sortCount = array();
foreach ($resultCookieArray as $key => $row)
{
    $sortCount[$key] = $row['data'];
}
array_multisort($sortCount, SORT_DESC, $resultCookieArray);

$resultCookieArray = array_slice($resultCookieArray, 0, 10, true);
//---


//---
$sortCount = array();
foreach ($resultKeywordArray as $key => $row)
{
    $sortCount[$key] = $row['data'];
}
array_multisort($sortCount, SORT_DESC, $resultKeywordArray);

$resultKeywordArray = array_slice($resultKeywordArray, 0, 10, true);
//---




$allTrackersArrayData["reports"]["ip_array"] = $resultIpArray;
$allTrackersArrayData["reports"]["location_array"] = $resultLocationArray;
$allTrackersArrayData["reports"]["country_array"] = $resultCountryArray;
$allTrackersArrayData["reports"]["cookie_array"] = $resultCookieArray;
$allTrackersArrayData["reports"]["keyword_array"] = $resultKeywordArray;



$data["user_trackers"] = count($arrayAllUserTrackers);

}else{

$data["user_trackers"] = 0;
$allTrackersArrayData = "";

}


return array(
"data" => $data, 
"trackers_data" => $allTrackersArrayData
);

}










function getReports(){

$trackerId = Input::get('tracker_id');
$date1 = Input::get('date1');
$date2 = Input::get('date2');
$range_type = Input::get('range_type');

$data_main = $this->createReportsDataFull($trackerId, $date1, $date2, $range_type);

$userDataArray = DB::table('users')
->where("id", "=", Auth::user()->id)
->get();

$data = $data_main["data"];
$data["user_data"] = $userDataArray;

$array = DB::table('_adwords_users')
->where("internal_user_id", "=", Auth::user()->id)
->get();

if(isset($array[0]) && count($array[0])>0){
$data["adwords_user_exist"] = 1;
}else{
$data["adwords_user_exist"] = 0;
}

if( isset($userDataArray[0]->braintree_customer_id) && $userDataArray[0]->braintree_customer_id != "" ){
// Here should be check Brain tree data is act?
$data["braintree_user"] = 1;
}else{
$data["braintree_user"] = 0;
}



return View::make('customer/reports_tracker', array(
"data" => $data, 
"trackers_data" => $data_main["trackers_data"]
));


}







function getReportDetail(){

$range_type = Input::get('range_type');

if( !empty($range_type) && $range_type == 2 && !empty($date1) && !empty($date2) ){

//$date1 = Input::get('date1');
$date1 = explode("-",$date1);
$date1 = mktime( 0,0,0,$date1[1],$date1[0],$date1[2] );

//$date2 = Input::get('date2');
$date2 = explode("-",$date2);
$date2 = mktime( 0,0,0,$date2[1],$date2[0]+1,$date2[2] );

}elseif($range_type == 3){
//today
$date1 = mktime( 0,0,0,date("n"),date("j"),date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j")+1,date("Y") );

}elseif($range_type == 4){
//yesterday
$date1 = mktime( 0,0,0,date("n"),date("j")-1,date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

}elseif($range_type == 5){
//last week
//$date1 = mktime( 0,0,0,date("n"),date("j")-7,date("Y") );
//$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

	if( date('w') == 1){
		$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );
	}else{
		$date2 = strtotime( "previous monday" );
	}

	$date1 = $date2-7*(60*60*24);

}elseif($range_type == 6){
	//this week
	$date2 = strtotime( "next monday" );

	if( date('w') == 1){
		$date1 = mktime( 0,0,0,date("n"),date("j"),date("Y") );
	}else{
		$date1 = strtotime( "previous monday" );
	}

}elseif($range_type == 7){
//last 14 days
$date1 = mktime( 0,0,0,date("n"),date("j")-14,date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

}elseif($range_type == 8){
//last 30 days
$date1 = mktime( 0,0,0,date("n"),date("j")-30,date("Y") );
$date2 = mktime( 0,0,0,date("n"),date("j"),date("Y") );

}elseif($range_type == 9){
//last month
$date1 = mktime (0, 0, 0, date("m")-1, 1, date("Y"));
$date2 = mktime (0, 0, 0, date("m")-1, date('t'), date("Y"));

}elseif($range_type == 10){
//this month
$date1 = mktime (0, 0, 0, date("m"), 1, date("Y"));
$date2 = mktime (0, 0, 0, date("m"), date('t'), date("Y"));

}else{

$date2 = mktime( 0,0,0,date("n"),date("j")+1,date("Y") );
$date1 = mktime( 0,0,0,date("n"),date("j")-6,date("Y") );

}


//var_dump(date("Y-m-d H:i:s",$date1));
//var_dump(date("Y-m-d H:i:s",$date2));


//check if auth user has this tracker!!

if( empty(Input::get('tracker_id')) || Input::get('tracker_id') == 0 ){

$array = DB::table('_trackers_data')
->leftJoin('_trackers', '_trackers_data.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', Auth::user()->id)
->where('_trackers_data.click_timestamp', '>=', $date1)
->where('_trackers_data.click_timestamp', '<=', $date2)
//->take(20)
->get();

}else{

$array = DB::table('_trackers_data')
->leftJoin('_trackers', '_trackers_data.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', Auth::user()->id)
->where('_trackers_data.tracker_id', '=', Input::get('tracker_id'))
->where('_trackers_data.click_timestamp', '>=', $date1)
->where('_trackers_data.click_timestamp', '<=', $date2)
//->take(20)
->get();

}


$trackerDataArray = array();
$trackerDataArray["clicks_count"] = count($array);

// ip Array
$ipArray = array();

foreach($array as $dataItem){

	if( !in_array($dataItem->user_ip, $ipArray) ){
		$ipArray[] = $dataItem->user_ip;
	}

}

// cookie Array
$cookieArray = array();

foreach($array as $dataItem){

	if( !in_array($dataItem->user_cookies, $cookieArray) ){
		$cookieArray[] = $dataItem->user_cookies;
	}

}

$trackerDataArray["cookies_count"] = count($cookieArray);
$trackerDataArray["ip_count"] = count($ipArray);

if(!empty(Input::get('report_type')) && Input::get('report_type') == 1){
/* Report by IP clickers /- */


$resultArray = array();
$i=0;

foreach($ipArray as $ipItem){

$resultArray[$i]["sortParameter"] = $ipItem;
$clickArray = array();

	foreach($array as $dataItem){

		if($dataItem->user_ip == $ipItem){

			$clickArray[] = $dataItem;
			
		}

	}
$resultArray[$i]["data"] = $clickArray;
$i++;
}

$pageTitle = "IP Report";
//var_dump($resultArray);
/* Report by IP clickers -/ */

	

}

if(Input::get('report_type') == 2){
/* Report by Location clickers /- */

$arraySort = array();

foreach($array as $dataItem){

$locationJson = json_decode($dataItem->ip_location);
$locationJson = json_decode($dataItem->ip_location);
if(
is_object($locationJson) && 
count($locationJson)>0 && 
isset($locationJson->location->cityName) && 
isset($locationJson->location->regionName) && 
isset($locationJson->location->countryCode)
){
$locationString = $locationJson->location->cityName . ", " . $locationJson->location->regionName . ", " . $locationJson->location->countryCode;

	if( !in_array($locationString, $arraySort) ){
		$arraySort[] = $locationString;
	}
}
}


$resultArray = array();
$i=0;

foreach($arraySort as $arraySortItem){

$resultArray[$i]["sortParameter"] = $arraySortItem;
$clickArray = array();

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
			if($locationString == $arraySortItem){

				$clickArray[] = $dataItem;
				
			}
		}
	}
$resultArray[$i]["data"] = $clickArray;
$i++;
}

$pageTitle = "Location Report";
//var_dump($resultArray);
/* Report by Location clickers -/ */

}


if(Input::get('report_type') == 3){
/* Report by Keyword clickers /- */

$arraySort = array();

foreach($array as $dataItem){

$decodedJson = json_decode($dataItem->adwords_input_data);
if(isset($decodedJson->keyword)){
$sortString = $decodedJson->keyword;

	if( !in_array($sortString, $arraySort) ){
		$arraySort[] = $sortString;
	}
}
}


$resultArray = array();
$i=0;

foreach($arraySort as $arraySortItem){

$resultArray[$i]["sortParameter"] = $arraySortItem;
$clickArray = array();

	foreach($array as $dataItem){
		$decodedJson = json_decode($dataItem->adwords_input_data);
		if(isset($decodedJson->keyword)){
		$sortString = $decodedJson->keyword;
		if($sortString == $arraySortItem){
			$clickArray[] = $dataItem;
			}
		}
	}

$resultArray[$i]["data"] = $clickArray;
$i++;

}

$pageTitle = "Keyword Report";
//var_dump($resultArray);
/* Report by Keyword clickers -/ */

}



if(Input::get('report_type') == 4){
/* Report by Cookie clickers /- */

$arraySort = array();

foreach($array as $dataItem){

$sortString = $dataItem->user_cookies;

	if( !in_array($sortString, $arraySort) ){
		$arraySort[] = $sortString;
	}

}


$resultArray = array();
$i=0;

foreach($arraySort as $arraySortItem){

$resultArray[$i]["sortParameter"] = $arraySortItem;
$clickArray = array();

	foreach($array as $dataItem){

		$sortString = $dataItem->user_cookies;

		if($sortString == $arraySortItem){

			$clickArray[] = $dataItem;
			
		}

	}
$resultArray[$i]["data"] = $clickArray;
$i++;
}

$pageTitle = "Cookie Report";
//var_dump($resultArray);
/* Report by Cookie clickers -/ */

}





//---
$sortCount = array();
foreach ($resultArray as $key => $row)
{
    $sortCount[$key] = $row['data'];
}
array_multisort($sortCount, SORT_DESC, $resultArray);

$resultArray = array_slice($resultArray, 0, 10, true);
//---






return View::make('customer/report_detail', array( "data" => $resultArray, "title" => $pageTitle, "tracker_summary" => $trackerDataArray ));

}





function getReportIpDetail(){

$array = array();

if( !empty(Input::get('ip')) ){

$array = DB::table('_trackers_data')
->leftJoin('_trackers', '_trackers_data.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', Auth::user()->id)
->where('_trackers_data.user_ip', '=', Input::get('ip'))
->get();


}


return View::make('customer/report_ip_detail', array( "data" => $array ));

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
return View::make('customer/messages', array("data" => $query));
}






function postExel(){

$destinationPath = '';
$filename = '';

if (Input::hasFile('file')) {

$file = Input::file('file');
$destinationPath = public_path().'/exel/';
$filename = str_random(6) . '_' . $file->getClientOriginalName();
$uploadSuccess = $file->move($destinationPath, $filename);

//=======
$rows = Excel::load( $destinationPath.$filename, function($reader) {

})->get()->first();

$rows = json_decode(json_encode($rows), true);

$count="";
$i=0;
foreach($rows as $item){

//var_dump($item);

$author = $item[0];
$text = $item[1];
if($author!='' && $text!=''){

//echo "$i $author $text<br>";

$array = DB::table('inspirations')
->where('author', '=', $author)
->where('text', '=', $text)
->get();

$count = count($array);

if($count > 0){

DB::table('inspirations')->insert(
array('author' => $author, 'text' => $text, 'status' => 1)
);

}else{
$i++;

DB::table('inspirations')->insert(
array('author' => $author, 'text' => $text, 'status' => 0)
);

//=============================================================== PUSH NOTOFICATIONS

}

/*
DB::table('users')->where('id', '=', $data)->update(array('block' => 0));

DB::table('users')->insert(
array('email' => 'john@example.com', 'votes' => 0)
);
*/

/*
$id = DB::table('users')->insertGetId(
array('email' => 'john@example.com', 'votes' => 0)
);
*/




}

}

unlink($destinationPath.$filename);
return Redirect::back()->withErrors(['Was upload '.$i.' records']);

}else{

return Redirect::back()->withErrors(['Errors while file uploading']);

}

}


/* Whitelist IPs /- */

function getIpWhitelist(){

/*
$array = DB::table('_trackers_ip_whitelists')
->leftJoin('_trackers', '_trackers_ip_whitelists.tracker_id', '=', '_trackers.id')
->where('_trackers.user', '=', Auth::user()->id)
->where('_trackers.id', '=', Input::get('tracker_id'))
->Paginate(30);
*/

$array = DB::table('_trackers_ip_whitelists')
->where('user_id', '=', Auth::user()->id)
->Paginate(30);

return View::make('customer/ip_whitelist', array( "data" => $array ));

}


function postAddWhitelistIp(){

DB::table('_trackers_ip_whitelists')
->insert(array(
'user_id' => Auth::user()->id,
'ip' => Input::get('ip')
)
);

return Redirect::back()->withErrors(['Data was saved']);

}


function getDeleteWhitelistIp(){

DB::table('_trackers_ip_whitelists')
->where('user_id', '=', Auth::user()->id)
->where('id', '=', Input::get('id'))
->delete();

return Redirect::back()->withErrors(['Data was saved']);

}


/* Whitelist IPs -/ */


function getBilling(){


$array = DB::table('users')
->where('id', '=', Auth::user()->id)
->get();

$billing_subscription = "";

if($array[0]->braintree_customer_id != ""){

	if($array[0]->billing_subscription_id != ""){
		$billing_subscription = "<a href='/billing/delete-subscription/'>Delete</a>";
	}else{
		$billing_subscription = "<a href='/billing/create-subscription/'>Create Subscription</a>";
	}

}

$paymentController = new PaymentsController;
$transactions = $paymentController->getTransactions();
return View::make('customer/billing', array(
"transactions" => $transactions,
"billing_subscription" => $billing_subscription
));

}




function getFaqs(){

return View::make('customer/faqs');

}



function getSettings(){



return View::make('customer/settings', array(
"array" => ""
));

}



function postEditSettings(){

$email = Input::get('email');
//$password = Input::get('password');
//$password_2 = Input::get('password_2');
$timezone = Input::get('timezone');
$message = "";
$daily_summary_email = Input::get('daily_summary_email');

if(isset($email) && $email != ""){

	$check_email_exist = DB::table('users')->where('email', '=', $email)->where('id', '!=', Auth::user()->id)->get();
	if(count($check_email_exist) < 1){

		$token = Str::random();
		
		//DB::table('users')->where('id', '=', Auth::user()->id)->update(array('email_new' => $email, 'activation_code' => $token));
		DB::table('users')->where('id', '=', Auth::user()->id)->update(array('email' => $email));
		$data["email"] = Auth::user()->email;
		$data["subject"] = "ClickMonitor Email Change";
		$data["text"] = "
		<p>Dear Client,
		<br>
		We just received a request to change your email address.  If this request was made by you, no further action is necessary.  If this request was not made by you, please contact us immediately at Support@account.clickmonitor.co.uk		
		</p>
		<p>Thank you,
		<br>
		The ClickMonitor Team
		</p>
		";

/*
We just received a request to change your email address.  If this request was not made by you, please contact us immediately at Support@account.clickmonitor.co.uk
		<br><br>
		If this request was made by you, click on the link to set your new email: <a href='http://www.account.clickmonitor.co.uk/auth/activate-email/?userId=".Auth::user()->id."&activationCode=".$token."'>Click here</a>
*/
		
		Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
							$message->to($data["email"])->subject($data["subject"]);
						});

		//$message = "Please check your email to set new one";

	}

}

if(isset($timezone) && $timezone != ""){

	DB::table('users')->where('id', '=', Auth::user()->id)->update(array('timezone' => $timezone));

}


if($daily_summary_email == 1){

	DB::table('users')->where('id', '=', Auth::user()->id)->update(array('daily_summary_email' => 1));

}else{

	DB::table('users')->where('id', '=', Auth::user()->id)->update(array('daily_summary_email' => 0));

}





return Redirect::back();

}



function postEditPassword(){

$old_password = Hash::make(Input::get('old_password'));
$password = Input::get('password');
$password_2 = Input::get('password_2');

if (Hash::check(Input::get('old_password'), Auth::user()->password)){

	if(isset($password) && isset($password_2) && $password != "" && $password_2 != "" && $password == $password_2){

		$password = Hash::make($password);
		DB::table('users')->where('id', '=', Auth::user()->id)->update(array('password' => $password));

	}

	$message = "success";

}else{

	$message = "Old password is invalid";
	return Redirect::back()->withErrors(array("password" => $message));

}

return Redirect::back();

}


function postSendMessage(){

	$name = Input::get('name');
	$email = Input::get('email');
	$text = Input::get('text');

	$data["email"] = "Support@account.clickmonitor.co.uk";
	$data["subject"] = "ClickMonitor - Customer Question";
	$data["text"] = "
	<p><b>Name:</b> ".$name."</p>
	<p><b>Email:</b> ".$email."</p>
	<p><b>Question:</b> ".$text."</p>
	";
	if($text!=""){
	Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
						$message->to($data["email"])->subject($data["subject"]);
					});

	}

return Redirect::back();

}











function postCheckCreditCard(){


$paymentController = new PaymentsController;

$paymentToken = Auth::user()->braintree_payment_token;
if($paymentToken != ""){
	$paymentController->deleteCreditCard($paymentToken);
}

$arrayCheck = array(
    "firstName" => Input::get('first_name'),
    "lastName" => Input::get("last_name"),
    "creditCard" => array(
        "number" => Input::get("number"),
        "expirationMonth" => Input::get("month"),
        "expirationYear" => Input::get("year"),
        "cvv" => Input::get("cvv"),
        "billingAddress" => array(
            "postalCode" => Input::get("postal_code")
        )
    )
);




$checkCardResult = $paymentController->checkCreditCardFunction($arrayCheck);

if ($checkCardResult->success) {
	
		DB::table('users')
		->where('id', '=', Auth::user()->id)
		->update(array('braintree_customer_id' => $checkCardResult->customer->id,
						'braintree_payment_token' => $checkCardResult->customer->paymentMethods[0]->token,
				'exp_date_m' => $checkCardResult->customer->paymentMethods[0]->expirationMonth,
				'exp_date_y' => $checkCardResult->customer->paymentMethods[0]->expirationYear
			));
		

//var_dump($checkCardResult->customer->paymentMethods[0]->token);

return Redirect::back();

} else {

   /*
 echo("Validation errors:<br/>");
    foreach (($checkCardResult->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
*/

return Redirect::back()->withErrors( $checkCardResult->errors->deepAll() );

}

}



// count user domains
function countUserDomainsFunction(){

	$users = DB::table('users')->get();

	foreach($users as $user){

		$this->countUserDomains($user->id, $user->email);

	}

}

function countUserDomains($userId, $userEmail){

	//$userId = Input::get('id');
	
	$click_timestamp = time() - 24*60*60;

	$userTrackers = DB::table('_trackers')
	->where('user', '=', $userId)
	->get();

	$domainsArray = array();
	foreach($userTrackers as $userTracker){
	
		$userTrackerData = DB::table('_trackers_data')
		->where('tracker_id', '=', $userTracker->id)
		->where('click_timestamp', '>', $click_timestamp)
		->get();

		if(isset($userTrackerData[0]->adwords_input_data) && $userTrackerData[0]->adwords_input_data != ""){

			$clickData = json_decode($userTrackerData[0]->adwords_input_data);
			$domain = $clickData->final_url;

			if($domain != "{lpurl}"){

				$parseUrl = parse_url($domain);
				$finalUrl = $parseUrl['host'];
				$resultUrl = str_replace("www.","", $finalUrl);

				if(!in_array($resultUrl, $domainsArray)){
					$domainsArray[] = $resultUrl;
				}

			}

		}

	}
		

	if(count($domainsArray) > 5){

		$userTrackers = DB::table('_trackers_users_domains')
		->insert(array(
			"user" => $userId,
			"date" => time(),
			"domains_count" => count($domainsArray)
		));

		// send email
			
		$data["email"] = $userEmail;
		$data["subject"] = "ClickMonitor Alert";
		$data["text"] = "<p>Thank you for using ClickMonitor. Your account is limited to five domains and we noticed that you are monitoring more than five. We limit this to five so that advertising agencies using our service don’t overwhelm our system. To get this fixed and to make sure you continue to receive monitoring please reduce the number of domains you are monitoring to five or less. If you’re not sure what we’re talking about, please email us at Support@account.clickmonitor.co.uk and we’ll help you get this taken care of.</p>
		<p>Thanks!<br>
		The ClickMonitor Team</p>
		";
		
		Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
							$message->to($data["email"])->subject($data["subject"]);
						});

		


	}


}












}