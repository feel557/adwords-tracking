<?php namespace App\Http\Controllers;

use View;

class IndexController extends BaseController {

/**
* отображение главной страницы
*/
function action_index(){

return View::make('public_pages/login');

}

function action_signin(){

return View::make('public_pages/login');

}


function contactus(){

return View::make('public_pages/lp_contact_us');

}


function trial(){

return View::make('public_pages/lp_trial');

}


function faq(){

return View::make('public_pages/lp_faq');

}


function about(){

return View::make('public_pages/lp_about');

}








}