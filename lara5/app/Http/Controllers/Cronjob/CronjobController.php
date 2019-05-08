<?php namespace App\Http\Controllers\Cronjob;
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

class CronjobController extends BaseController {


public function test(){

	$arrayUser = DB::table('users')
	->where("user_type", "!=", "21")
	->get();

	foreach($arrayUser as $user){

	if($user->exp_date_y >= 2016 && $user->exp_date_y < 2030 && $user->exp_date_m > 0 && $user->exp_date_m <= 12){





		$date = date_create_from_format('Y-m-d', $user->exp_date_y . "-" . $user->exp_date_m . "-" . "01");

		$newDate = $date->getTimestamp();
		$diff = $newDate - time();

		$myDiff = time() - $user->last_date_expiration;

		if($diff < 7*24*3600 && $myDiff >= 24*3600){

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
				->update(array('count_expiration_emails' => $count_expiration_emails, 'last_date_expiration' => time() ));

			}

			}
		}

		}
	}


}






public function test2(){

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



$date2 = time();
$date1 = time()-1*(60*60*24);



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