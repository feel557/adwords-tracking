<?php namespace App\Http\Controllers\Tracker;
use App\Http\Controllers\BaseController;
use View;
use Input;
use Redirect;
use DB;
use Auth;
use App\Http\Controllers\Tracker\TaskQueueManagerController;
use Mail;


class TrackerController extends BaseController {

// Main functions

function getAdRedirect(){

//tracking URL http://ppcsurge.com/?tracker_id=109874677
// https://www.xxxxx.com/track.php?id=1234567890&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}&url={lpurl}
$tracker_id = Input::get('tracker_id');


$inputData = (object) [];


if( !empty(Input::get('url')) ){
$urlInput = Input::get('url');
$inputData->final_url = $urlInput;
}else{
$inputData->final_url = "http://account.clickmonitor.co.uk/";
}


if( !empty(Input::get('kw')) ){
$keywordInput = Input::get('kw');
$inputData->keyword = $keywordInput;
}

if( !empty(Input::get('cmp')) ){
$campaignInput = Input::get('cmp');
$inputData->campaign = $campaignInput;
}

if(!empty(Input::get('pl'))){

$placementInput = Input::get('pl');
$inputData->placement = $placementInput;

}




if(isset($_COOKIE["trackerCookie"])){

	$checkingCookie = $_COOKIE["trackerCookie"];

}else{
		$newCookie = md5(time().rand()."jk-".rand());
		setcookie ("trackerCookie", $newCookie, time()+60*60*24*365);
		$checkingCookie = $newCookie;
}

$inputData->cookie = $checkingCookie;

$serverArray = $_SERVER;

// 1. Getting IP address
$ipAddress = '';

        // Check for X-Forwarded-For headers and use those if found
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ('' !== trim($_SERVER['HTTP_X_FORWARDED_FOR']))) {
            $ipAddress = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            if (isset($_SERVER['REMOTE_ADDR']) && ('' !== trim($_SERVER['REMOTE_ADDR']))) {
                $ipAddress = trim($_SERVER['REMOTE_ADDR']);
            }
        }


// 2. Get tracker data for input IP address
$arrayTrackerDataCheckingIP = DB::table('_trackers_data')
->where("tracker_id", "=", $tracker_id)
->where("user_ip", "=", $ipAddress)
->get();

// 3. Get tracker settings
$arrayTracker = DB::table('_trackers')
->where("id", "=", $tracker_id)
->take(1)
->get();

$array2 = DB::table('_trackers_rules')
->leftJoin('_trackers_rules_users', '_trackers_rules.rule_id', '=', '_trackers_rules_users.id')
->where('_trackers_rules.tracker_id', '=', $tracker_id)
->where('_trackers_rules_users.alert_level', '=', 1)
->select(DB::raw(' _trackers_rules_users.id, _trackers_rules_users.act, _trackers_rules_users.number_of_clicks, _trackers_rules_users.time_amount, _trackers_rules_users.alert_message, _trackers_rules_users.send_alert, _trackers_rules_users.block_ip, _trackers_rules_users.show_message '))
->get();

$array3 = DB::table('_trackers_rules')
->leftJoin('_trackers_rules_users', '_trackers_rules.rule_id', '=', '_trackers_rules_users.id')
->where('_trackers_rules.tracker_id', '=', $tracker_id)
->where('_trackers_rules_users.alert_level', '=', 2)
->select(DB::raw(' _trackers_rules_users.id, _trackers_rules_users.act, _trackers_rules_users.number_of_clicks, _trackers_rules_users.time_amount, _trackers_rules_users.alert_message, _trackers_rules_users.send_alert, _trackers_rules_users.block_ip, _trackers_rules_users.show_message '))
->get();

$arrayTracker[0]->rules_array_1 = $array2;
$arrayTracker[0]->rules_array_2 = $array3;
$arrayTracker[0]->input_data = $inputData;

$arrayIpWhitelist = DB::table('_trackers_ip_whitelists')
->where('user_id', '=', $arrayTracker[0]->user)
->where('ip', '=', $ipAddress)
->get();

if( count($arrayIpWhitelist) > 0 ){

// IP in whitelist - just redirect to final url
if( $_SERVER['HTTP_USER_AGENT'] != 'Google-Adwords-Instant (+http://www.google.com/adsbot.html)' ){
				$this->saveAllUserData($ipAddress,$serverArray,$arrayTracker);
				}
//$this->saveAllUserData($ipAddress,$serverArray,$arrayTracker);
// redirect to landing
return redirect($arrayTracker[0]->input_data->final_url);

}else{


// Main Logic Function

if( count($arrayTrackerDataCheckingIP) > 0 ){
/*
- if ip exist:
					- by the parameter $_REQUEST["user_id"] get ad's owner settings for this tracker
					- by the settings:
							- either redirect ad's user to final url (landing page) 
							- or show alert:
									- in the case with displaing alert we add add ip for block in adwords account *
					- all user's data put into database * 
*/



$currentTimestamp = time();//$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));

$beginSearchPeriodTime1 = time()-$arrayTracker[0]->rules_array_1[0]->time_amount;// in seconds

// !!!ALWAYS checking firstly LEVEL 1
// 2. Get tracker data for input IP address
$arrayTrackerDataLevel1 = DB::table('_trackers_data')
->where("tracker_id", "=", $tracker_id)
->where("user_ip", "=", $ipAddress)
->where("click_timestamp", "<=", $currentTimestamp)
->where("click_timestamp", ">=", $beginSearchPeriodTime1)
->get();


$beginSearchPeriodTime2 = time()-$arrayTracker[0]->rules_array_2[0]->time_amount;// in seconds

$arrayTrackerDataLevel2 = DB::table('_trackers_data')
->where("tracker_id", "=", $tracker_id)
->where("user_ip", "=", $ipAddress)
->where("click_timestamp", "<=", $currentTimestamp)
->where("click_timestamp", ">=", $beginSearchPeriodTime2)
->get();


	if( count($arrayTrackerDataLevel1)+1 >= $arrayTracker[0]->rules_array_1[0]->number_of_clicks ){

		if( $arrayTracker[0]->rules_array_2[0]->act == 1 && count($arrayTrackerDataLevel2)+1 >= $arrayTracker[0]->rules_array_2[0]->number_of_clicks ){

	// Checking Level #2
		$level = 2;
		
			if( $_SERVER['HTTP_USER_AGENT'] != 'Google-Adwords-Instant (+http://www.google.com/adsbot.html)' ){
				$this->applyRules($ipAddress, $arrayTracker, $level);
				$this->saveAllUserData($ipAddress,$serverArray,$arrayTracker);
				}

			}else{
	// Checking Level #1
		$level = 1;
		
		if( $_SERVER['HTTP_USER_AGENT'] != 'Google-Adwords-Instant (+http://www.google.com/adsbot.html)' ){
				$this->applyRules($ipAddress, $arrayTracker, $level);
				$this->saveAllUserData($ipAddress,$serverArray,$arrayTracker);
				}

			}

	}else{

		// add all users data to db async
		if( $_SERVER['HTTP_USER_AGENT'] != 'Google-Adwords-Instant (+http://www.google.com/adsbot.html)' ){
				$this->saveAllUserData($ipAddress,$serverArray,$arrayTracker);
				}
		// redirect to landing
//var_dump($arrayTracker[0]->input_data);
		return redirect($arrayTracker[0]->input_data->final_url);

	}

}else{
/*
- if ip doesn't exist:
					- just redirect ad's user to final url (landing page) 
					- all user's data put into database * 
*/


// add all users data to db async
			if( $_SERVER['HTTP_USER_AGENT'] != 'Google-Adwords-Instant (+http://www.google.com/adsbot.html)' ){
				$this->saveAllUserData($ipAddress,$serverArray,$arrayTracker);
				}

// redirect to landing
return redirect($arrayTracker[0]->input_data->final_url);
//header("Location: ".$urlInput);

}

}




}



public function saveAllUserData($ipAddress,$serverArray,$arrayTracker){

// Referer page
if(isset($serverArray['HTTP_REFERER'])){$refererPage = $serverArray['HTTP_REFERER'];}else{$refererPage = "";}

// User Agent
$useragent = $serverArray['HTTP_USER_AGENT'];
/*
//First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
*/

// Hostname
$hostName = gethostname(); // may output e.g,: sandie
// Or, an option that also works before PHP 5.3
//echo php_uname('n'); 

// Detecting Mobile Device
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
$isMobile = 1;
}else{
$isMobile = 0;
}

// Keyword
//$keyword = $arrayTracker[0]->input_data->keyword;
//$placement = $arrayTracker[0]->input_data->placement;
//$campaignId = $arrayTracker[0]->input_data->campaign;
$adwordsInputData = json_encode($arrayTracker[0]->input_data);

// ipLocation
$ipLocation = "";
//set task to get ip location


//$tracker_id = Input::get('tracker_id');
$tracker_id = $arrayTracker[0]->id;

$timestamp = time();

// Save to db
$tracker_data_id = DB::table('_trackers_data')->insertGetId(array(

'click_timestamp' => $timestamp,
'tracker_id' => $tracker_id,
'user_ip' => $ipAddress, 
'user_browser' => $useragent, 
'user_referer_page' => $refererPage,
'device_is_mobile' => $isMobile,
'device_hostname' => $hostName,
'adwords_input_data' => $adwordsInputData,
'user_cookies' => $arrayTracker[0]->input_data->cookie


)
);


// Save to db
$task_id = DB::table('_trackers_tasks_current')->insertGetId(array(
'tracker_id' => $tracker_id,
'task_type' => 2, //from table trackers_tasks_types
'value' => json_encode(array("ip" => $ipAddress, "tracker_id" => $tracker_id, "tracker_data_id" => $tracker_data_id))
)
);
//
$taskQueueManager = new TaskQueueManagerController();
//$taskQueueManager->rabbitMQSend( "iplocation", json_encode(array("task_id" => $task_id,"ip" => $ipAddress, "tracker_data_id" => $tracker_data_id)) );

//test
$taskQueueManager->ipLocationGet(json_encode(array("task_id" => $task_id,"ip" => $ipAddress, "tracker_data_id" => $tracker_data_id)));







}


public function applyRules($ipAddress, $arrayTracker, $level){

$taskQueueManager = new TaskQueueManagerController();

$arrayStatistic = DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $arrayTracker[0]->id)
->get();

$arrayIpWhitelist = DB::table('_trackers_ip_whitelists')
->where('user_id', '=', $arrayTracker[0]->user)
->where('ip', '=', $ipAddress)
->get();

if( count($arrayIpWhitelist) < 1 ){

	if($level == 1){

	// other settings
	$trackerBlockIp = $arrayTracker[0]->rules_array_1[0]->block_ip;
	$trackerShowMessage = $arrayTracker[0]->rules_array_1[0]->show_message;
	$trackerAlertMessage = $arrayTracker[0]->rules_array_1[0]->alert_message;
	$trackerSendAlert = $arrayTracker[0]->rules_array_1[0]->send_alert;

$alert_1_views = $arrayStatistic[0]->alert_1_views + 1;
$task_id = DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $arrayTracker[0]->id)
->update(array(
'alert_1_views' => $alert_1_views
)
);

	}elseif($level == 2){

	// other settings
	$trackerBlockIp = $arrayTracker[0]->rules_array_2[0]->block_ip;
	$trackerShowMessage = $arrayTracker[0]->rules_array_2[0]->show_message;
	$trackerAlertMessage = $arrayTracker[0]->rules_array_2[0]->alert_message;
	$trackerSendAlert = $arrayTracker[0]->rules_array_2[0]->send_alert;


$alert_2_views = $arrayStatistic[0]->alert_2_views + 1;
$task_id = DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $arrayTracker[0]->id)
->update(array(
'alert_2_views' => $alert_2_views
)
);


	}


// 1. Show alert message 
if($trackerShowMessage == 1){
// Save to db
// shown_warnings
$shown_warnings = $arrayStatistic[0]->shown_warnings + 1;

$task_id = DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $arrayTracker[0]->id)
->update(array(
'shown_warnings' => $shown_warnings
)
);


echo "<body style='background:#bbb;'><div style='margin:100px auto;width:400px;'><img src='http://account.clickmonitor.co.uk/css/img/logo.png'><div style='padding:20px;background:#fff;border:2px solid #aaa;font-family:Arial;border-radius:6px;'>".$trackerAlertMessage."<br><br><a href=".$arrayTracker[0]->input_data->final_url." style='color:#428bca;'>Click Here to Continue to the Website</a></div></div></body>";

}

// 2. Block IP set task to tasksManager

$arrayBlockedIps = DB::table('_trackers_data_blocked_ip')
->where('tracker_id', '=', $arrayTracker[0]->id)
->where('ip', '=', $ipAddress)
->get();

if($trackerBlockIp == 1){

//echo "<div style='font-weight:bold;adding:10px 0;'>We are blocked your ip</div>";




//if(count($arrayBlockedIps)<1){

$tracker_id = $arrayTracker[0]->id;


// Save to db
$task_id = DB::table('_trackers_tasks_current')->insertGetId(array(
'tracker_id' => $arrayTracker[0]->id,
'task_type' => 1, //from table trackers_tasks_types
'value' => json_encode(array("ip" => $ipAddress, "tracker_id" => $arrayTracker[0]->id)),
'created' => time()
)
);

$jsonOutputData = json_encode(array(
"task_id" => $task_id, 
"ip" => $ipAddress, 
"tracker_id" => $arrayTracker[0]->id)
);

//var_dump($jsonOutputData);

//$taskQueueManager->rabbitMQSend( "blockip", json_encode(array()) );


					//NEW     $taskQueueManager->rabbitMQSend("blockip", $jsonOutputData);

/*
$taskArray = DB::table('_trackers_tasks_current')
->where("act", "=", 0)
->where("task_type", "=", 1)
->get();

foreach($taskArray as $taskItem){

	$jsonDecodeArray = json_decode($taskItem->value);
	$resultJson = json_encode(array(
		"task_id" => $taskItem->id, 
		"ip" => $jsonDecodeArray->ip, 
		"tracker_id" => $jsonDecodeArray->tracker_id)
			);
	$taskQueueManager->rabbitMQSend("blockip", $resultJson);

}
*/
//$taskQueueManager->rabbitMQSend( "blockip", json_encode(array()) );
//test
$taskQueueManager->blockIPAdwords( json_encode(array("task_id" => $task_id,"ip" => $ipAddress, "tracker_id" => $arrayTracker[0]->id)) );

//}
}

// 3. Send email to trackerUser
if( count($arrayIpWhitelist) < 1 ){

if($trackerSendAlert == 1){

$email_sent_count = $arrayStatistic[0]->email_sent_count + 1;

$task_id = DB::table('_trackers_data_statistic')
->where('tracker_id', '=', $arrayTracker[0]->id)
->update(array(
'email_sent_count' => $email_sent_count
)
);

$arrayClicksByIp = DB::table('_trackers_data')
->where('tracker_id', '=', $arrayTracker[0]->id)
->where('user_ip', '=', $ipAddress)
->get();

if(isset($arrayTracker[0]->email_1_notification) && $arrayTracker[0]->email_1_notification!=''){

$data["user_to"] = $arrayTracker[0]->email_1_notification;
$data["subject"] = "Potential Click Fraud Detected";

$textBlockIp = "";
if($trackerBlockIp == 1){$textBlockIp = "<p>This IP address has been blocked in Google Adwords.</p>";}
$data["text"] = "
<p>We have detected multiple clicks by Monitor ".$arrayTracker[0]->name."</p>
<p>IP address: ".$ipAddress."</p>
<p>Total clicks: ".count($arrayClicksByIp)."</p>
".$textBlockIp."
<p>Thank you for allowing us to assist you.</p>
<p>ClickMonitor.co.uk</p>
";


			Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
					$message->to($data["user_to"])->subject($data["subject"]);
				});


}

if(isset($arrayTracker[0]->email_2_notification) && $arrayTracker[0]->email_2_notification!=''){

$data["user_to"] = $arrayTracker[0]->email_2_notification;
$data["subject"] = "Potential Click Fraud Detected";

$textBlockIp = "";
if($trackerBlockIp == 1){$textBlockIp = "<p>This IP address has been blocked in Google Adwords.</p>";}
$data["text"] = "
<p>We have detected multiple clicks by Monitor ".$arrayTracker[0]->name."</p>
<p>IP address: ".$ipAddress."</p>
<p>Total clicks: ".count($arrayClicksByIp)."</p>
".$textBlockIp."
<p>Thank you for allowing us to assist you.</p>
<p>ClickMonitor.co.uk</p>
";


			Mail::send('emails/plain_message', array('data' => $data), function ($message) use ($data) {
					$message->to($data["user_to"])->subject($data["subject"]);
				});


}
//echo "<div style='font-weight:bold;adding:10px 0;'>Sending email to ad's owner</div>";

}
}


}
}




}