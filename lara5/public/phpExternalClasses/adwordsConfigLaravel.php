<?php


//getting settings
//include_once("bd.php");

// ---Boot Laravel to get user id /-
$rootPath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'); 

require $rootPath.'/bootstrap/autoload.php';
$app = require_once $rootPath.'/bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$response = $kernel->handle(
  $request = Illuminate\Http\Request::capture()
);

$id = $app['encrypter']->decrypt($_COOKIE[$app['config']['session.cookie']]);
$app['session']->driver()->setId($id);
$app['session']->driver()->start();

$currentUserId = Auth::user()->id;

// ---Boot Laravel to get user id -/
//use AdWordsUser;
//use Selector;


$queryAdwordsSettings = DB::table('_adwords_settings')
->take(1)
->get();

$arrayAdwordsSettings = (array)$queryAdwordsSettings[0];

$client_id = $arrayAdwordsSettings['client_id'];
$client_secret = $arrayAdwordsSettings['client_secret'];
$redirect_uri = $arrayAdwordsSettings['redirect_uri'];
$developerToken = $arrayAdwordsSettings['developerToken'];
$userAgent = $arrayAdwordsSettings['userAgent'];
$managerRefreshToken = $arrayAdwordsSettings['managerRefreshToken'];
$managerClientCustomerId = $arrayAdwordsSettings['managerClientCustomerId'];


$adwordsScopes = "https://www.googleapis.com/auth/adwords";
$getAccountAccessUrl = "https://accounts.google.com/o/oauth2/auth?scope=".urldecode($adwordsScopes)."&response_type=code&access_type=offline&redirect_uri=".urlencode($redirect_uri)."&client_id=".$client_id;


