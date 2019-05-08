<?php

/*

Google Oauth Functions

Developer: Dmitry Kuznetsov
Version: 0.99
Date: June 2015

use google oauth lib

*/


require_once('googleOauth/Google/autoload.php');

//========================================= ADDITIONAL FUNCTIONS

function getUserIdEmail(){
$TokenArray = json_decode($_SESSION['access_token'],true);
$accessToken = $TokenArray['access_token'];
$url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$accessToken;
$xmlresponse = curl_file_get_contents($url);
$userArray = json_decode($xmlresponse,true);
return $userArray['email'];
}

function base64_url_encode($input) {
return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input) {
return base64_decode(strtr($input, '-_,', '+/='));
}

function base64url_encode($mime) {
return rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
}

function curl_file_get_contents($url){

$curl = curl_init();
$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.
curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.
$contents = curl_exec($curl);
curl_close($curl);
return $contents;

}

//============================================ BASIC CONFIGURATION FOR API



function setClientScopes($scopes,$client_id,$client_secret,$redirect_uri){

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);

foreach($scopes as $sc_item){
$client->addScope($sc_item);
}

$client->setAccessType('offline');

return $client;

}


/* EXAMPLE
//   $client->addScope("https://mail.google.com/");
$client->addScope("https://www.googleapis.com/auth/contacts.readonly");
$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
$client->addScope("https://www.googleapis.com/auth/userinfo.email");
$client->addScope("https://www.googleapis.com/auth/plus.me");
// $client->addScope("https://www.googleapis.com/auth/plus.login");
*/


function checkScopes(){
$TokenArray = json_decode($_SESSION['access_token'],true);
$accessToken = $TokenArray['access_token'];
$url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=".$accessToken;
//echo "== $url ==";
$xmlresponse = curl_file_get_contents($url);
$scopesArray = json_decode($xmlresponse,true);
//var_dump($scopesArray);
if($scopesArray['error'] == "invalid_token"){
$scopeType = 99;
}else{
if($scopesArray['scope'] == "https://www.googleapis.com/auth/contacts.readonly https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me https://mail.google.com/"){
$scopeType = 2;
}else{
$scopeType = 1;
}

}

return $scopeType;

}



//========= GET USER INFO /*

function getUserInfo($userEmail){
$TokenArray = json_decode($_SESSION['access_token'],true);
$accessToken = $TokenArray['access_token'];
$url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$accessToken;
$xmlresponse = curl_file_get_contents($url);
$userFullInfoInput = json_decode($xmlresponse,true);

$query = mysql_query("UPDATE `users` SET 

`given_name` = '$userFullInfoInput[given_name]',
`family_name` = '$userFullInfoInput[family_name]',
`link` = '$userFullInfoInput[link]',
`picture` = '$userFullInfoInput[picture]',
`gender` = '$userFullInfoInput[gender]',
`locale` = '$userFullInfoInput[locale]'

 WHERE `email` = '$userEmail' ");
}

//========= GET USER INFO */

function refreshTokenFunc($client, $refreshTokenExist){

$client->refreshToken($refreshTokenExist);//"1/cUOGLMcAKz3rFiNDO-QlgsyLMFpJP1Bk7dYAFF4_Dco"
$newtoken = $client->getAccessToken();
$_SESSION['access_token'] = $client->getAccessToken();
$client->setAccessToken($_SESSION['access_token']);

$TokenArray = json_decode($newtoken,true);
$accessToken = $TokenArray['access_token'];
$tokenExpired = $TokenArray['expires_in']+$TokenArray['created'];//----------

return $accessToken;

}

//========= .INI


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

