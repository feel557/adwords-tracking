<?php

namespace App\Libraries\AdwordsInternalClasses;
use App\Http\Controllers\BaseController;
use View;
use Input;
use Redirect;
use DB;
use Auth;
use Excel;



class AdwordsClass extends BaseController {

public function adwordsConfig(){

$array = DB::table('_adwords_settings')
//->leftJoin('_adwords_keywords', '_adwords_keywords_status.keyword_id', '=', '_adwords_keywords.id')
//->leftJoin('_adwords_campaigns', '_adwords_keywords.campaign_id', '=', '_adwords_campaigns.id')
//->select(DB::raw('posts.id as post_id, users.id as user_id, users.username, posts.date, posts.text'))
//->orderBy('_adwords_campaigns.id', 'desc')
->take(1)
->get();
//->Paginate(4);

//var_dump($array);

/*

$client_id = $arrayAdwordsSettings['client_id'];
$client_secret = $arrayAdwordsSettings['client_secret'];
$redirect_uri = $arrayAdwordsSettings['redirect_uri'];
$developerToken = $arrayAdwordsSettings['developerToken'];
$userAgent = $arrayAdwordsSettings['userAgent'];
$managerRefreshToken = $arrayAdwordsSettings['managerRefreshToken'];
$managerClientCustomerId = $arrayAdwordsSettings['managerClientCustomerId'];
$adwordsScopes = "https://www.googleapis.com/auth/adwords";
$getAccountAccessUrl = "https://accounts.google.com/o/oauth2/auth?scope=".urldecode($adwordsScopes)."&response_type=code&access_type=offline&redirect_uri=".urlencode($redirect_uri)."&client_id=".$client_id;

*/

return $array[0];

}



}

?>