<?php

/*

This script authorize client Adwords and linking this client account to MCC account

Developer: Dmitry Kuznetsov
Version: 0.99
Date: June 2015

use adwords php lib
use google oauth lib

1. authorize client by app
2. getting and save client refresh token/access
3. using our adwords mcc developer token get our adwords manager id
4. using this credentials set auth.ini and get client adwords id
5. set auth.ini back to manager and send to client link for linking client account to manager account
6. set auth.ini as client and set this link is active
7. print success message


*/


ini_set('error_reporting','0');

/*
register_shutdown_function('shutDownFunction');

function shutDownFunction() {
$error = error_get_last();
if ($error['type'] == 1) {

$pos = strripos($error['message'], "ALREADY_MANAGED_BY_THIS_MANAGER");

if ($pos === false) {
echo "Error. Text: $error[message] ";
} else {
echo "ALREADY_MANAGED_BY_THIS_MANAGER <a href='index.php'>Go to Home page</a>";
}



}
}
*/

//session_start();

require_once('oauthFunctions.php');

// CONFIGURATION

require_once('adwordsConfigLaravel.php');


//============================================ BASIC CONFIGURATION FOR API

//set scopes
$scopes = array($adwordsScopes);
$client = setClientScopes($scopes,$client_id,$client_secret,$redirect_uri);



if (isset($_GET['code'])) {

$client->authenticate($_GET['code']);
//$_SESSION['access_token'] = $client->getAccessToken();

//=== get refresh token
$TokenArray = $client->getAccessToken();
$TokenArray = json_decode($TokenArray,true);
//===========================================================================================
//2. getting and save client refresh token/access

//if refresh token does not exist we should revoke current access token and get new access/refresh tokens

if(!isset($TokenArray['refresh_token']) OR $TokenArray['refresh_token'] == ""){

$revokeUrl = "https://accounts.google.com/o/oauth2/revoke?token=".$TokenArray['access_token'];
curl_file_get_contents($revokeUrl);
header('Location: ' . $getAccountAccessUrl);
exit();

}




$access_token = $TokenArray['access_token'];
$refresh_token = $TokenArray['refresh_token'];

$internalAdwordsController = new App\Http\Controllers\Adwords\InternalAdwordsController();

$clientCustomerId = $internalAdwordsController->getAdwordsClientId($refresh_token);

//var_dump($clientCustomerId);


$internalAdwordsController->setLinkBetweenAccounts($clientCustomerId);
//exit();
//redirect to success
header('Location: ' . "/user/main/?get_adwords_data=1");
exit();


}else{

echo "<a href='".$getAccountAccessUrl."'>Connect me!</a>";

}


