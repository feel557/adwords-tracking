<?php

require_once('oauthFunctions.php');

$client_id = 'xxx.apps.googleusercontent.com';
$client_secret = 'xxx';
$redirect_uri = 'http://xxx/phpExternalClasses/getToken.php';

$scopeAll = urlencode("https://spreadsheets.google.com/feeds https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/adwords");
//$scopeSpreadsheets = urlencode("https://spreadsheets.google.com/feeds");
//$scopeDrive = urlencode("https://www.googleapis.com/auth/drive");
$scopeAdwords = urlencode("https://www.googleapis.com/auth/adwords");

$getAccountAccessUrl = "https://accounts.google.com/o/oauth2/auth?scope=".$scopeAdwords."&response_type=code&access_type=offline&redirect_uri=".urlencode($redirect_uri)."&client_id=".$client_id;


//set scopes
$scopes = array($scopeSpreadsheets, $scopeDrive, $scopeAdwords);
$client = setClientScopes($scopes,$client_id,$client_secret,$redirect_uri);



if (isset($_GET['code'])) {

$client->authenticate($_GET['code']);
//$_SESSION['access_token'] = $client->getAccessToken();

//=== get refresh token
$TokenArray = $client->getAccessToken();
$TokenArray = json_decode($TokenArray,true);
//===========================================================================================
//2. getting and save client refresh token/access

$access_token = $TokenArray['access_token'];
$refresh_token = $TokenArray['refresh_token'];

var_dump($TokenArray);

}else{

echo "<a href='".$getAccountAccessUrl."'>Connect me!</a>";

}

