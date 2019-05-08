<?php


// ПЕРВОНАЧАЛЬНАЯ АВТОРИЗАЦИЯ ЯНДЕКСА ВОЗВРАЩАЕТ КУКИ

function firstAuth($login,$password){

//include 'simple_html_dom.php';
//$cookie_box = 'cookie.txt';

$common_yandex = curl_init('https://passport.yandex.ru/passport?mode=auth&amp;msg=money');
curl_setopt($common_yandex, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($common_yandex, CURLOPT_POST, 1);
curl_setopt($common_yandex, CURLOPT_HEADER, 1);
curl_setopt($common_yandex, CURLOPT_POSTFIELDS,"login=$login&passwd=$password");
curl_setopt($common_yandex, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($common_yandex, CURLOPT_COOKIEJAR, $cookie_box);
//curl_setopt($common_yandex, CURLOPT_FOLLOWLOCATION, 1);
curl_exec_follow($common_yandex, $maxredirect = null);
curl_setopt($common_yandex, CURLOPT_VERBOSE, 1); //curl log
$html = curl_exec($common_yandex);
$header = substr($html, 0, curl_getinfo($common_yandex, CURLINFO_HEADER_SIZE));
$body = substr($html, curl_getinfo($common_yandex, CURLINFO_HEADER_SIZE));
curl_close($common_yandex);

/*

curl_setopt($common_yandex, CURLOPT_POST, 0);
curl_setopt($common_yandex, CURLOPT_URL, "https://mail.yandex.ru/neo2/handlers/handlers3.jsx?_h=settings-color-schemes-text,folders,labels,messages,service-emails,second-level-zones");
$html = curl_exec($common_yandex);
$uid_and_login = json_decode($html);
curl_setopt($common_yandex, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($common_yandex, CURLOPT_URL, "https://mail.yandex.ru/neo2/handlers/abook-epxort.jsx?_uid=".$uid_and_login->uid."&tp=4&lang=ru");
$html = curl_exec($common_yandex);
*/

//echo $header;
//echo "<br><br>--------------------------------------<br><br>";
$cookie = get_cookies($header);
return $cookie;
//echo "<br><br>--------------------------------------<br><br>";
//echo $body;

}


// GET DATA
function sendGetCurl($url, $httpArray, $header_set, $cookie){


$curlInit = curl_init();

$options = array(
CURLOPT_URL            => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HEADER         => $header_set,
// CURLOPT_FOLLOWLOCATION => true,
CURLOPT_ENCODING       => "",
CURLOPT_AUTOREFERER    => true,
CURLOPT_CONNECTTIMEOUT => 120,
CURLOPT_TIMEOUT        => 120,
CURLOPT_MAXREDIRS      => 10,
);
curl_setopt_array( $curlInit, $options );
if(isset($httpArray) AND $httpArray!=''){
curl_setopt( $curlInit, CURLOPT_HTTPHEADER, $httpArray);
}
//curl_exec_follow( $curlInit, $maxredirect = null );
if(isset($cookie) AND $cookie!=''){
curl_setopt( $curlInit, CURLOPT_COOKIE, $cookie);
}
$response = curl_exec($curlInit);
//$httpCode = curl_getinfo($curlInit, CURLINFO_HTTP_CODE);
curl_close($curlInit);

return $response;



}


// POST DATA
function sendPostCurl($url, $httpArray, $postfields, $header_set, $cookie){


$curlInit = curl_init();

$options = array(
CURLOPT_URL            => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HEADER         => $header_set,
// CURLOPT_FOLLOWLOCATION => true,
CURLOPT_ENCODING       => "",
CURLOPT_AUTOREFERER    => true,
CURLOPT_CONNECTTIMEOUT => 120,
CURLOPT_TIMEOUT        => 120,
CURLOPT_MAXREDIRS      => 10,
);
curl_setopt_array( $curlInit, $options );
//curl_exec_follow( $curlInit, $maxredirect = null );
if(isset($httpArray) AND $httpArray!=''){
curl_setopt( $curlInit, CURLOPT_HTTPHEADER, $httpArray);
}
curl_setopt( $curlInit, CURLOPT_POST, true);
curl_setopt( $curlInit, CURLOPT_POSTFIELDS, $postfields);
if(isset($cookie) AND $cookie!=''){
curl_setopt( $curlInit, CURLOPT_COOKIE, $cookie);
}
$response = curl_exec($curlInit);
//$httpCode = curl_getinfo($curlInit, CURLINFO_HTTP_CODE);
curl_close($curlInit);

return $response;



}





/* ================================= ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ======================== */

function curl_exec_follow($ch, $maxredirect = null) {
$mr = $maxredirect === null ? 5 : intval($maxredirect);
if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
} else {
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
if ($mr > 0) {
$newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

$rch = curl_copy_handle($ch);
curl_setopt($rch, CURLOPT_HEADER, true);
curl_setopt($rch, CURLOPT_NOBODY, true);
curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
do {
curl_setopt($rch, CURLOPT_URL, $newurl);
$header = curl_exec($rch);
if (curl_errno($rch)) {
$code = 0;
} else {
$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
if ($code == 301 || $code == 302) {
preg_match('/Location:(.*?)\n/', $header, $matches);
$newurl = trim(array_pop($matches));
} else {
$code = 0;
}
}
} while ($code && --$mr);
curl_close($rch);
if (!$mr) {
if ($maxredirect === null) {
trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
} else {
$maxredirect = 0;
}
return false;
}
curl_setopt($ch, CURLOPT_URL, $newurl);
}
}
return curl_exec($ch);
}



function curl_redir_exec($ch)
{
static $curl_loops = 0;
static $curl_max_loops = 20;
if ($curl_loops   >= $curl_max_loops)
{
$curl_loops = 0;
return FALSE;
}
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
list($header, $data) = explode("\r\n\r\n", $data, 2);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code == 301 || $http_code == 302)
{
$matches = array();
preg_match('/Location:(.*?)\n/', $header, $matches);
$url = @parse_url(trim(array_pop($matches)));
if (!$url)
{
//couldn't process the url to redirect to
$curl_loops = 0;
return $data;
}
$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
if (!$url['scheme'])
$url['scheme'] = $last_url['scheme'];
if (!$url['host'])
$url['host'] = $last_url['host'];
if (!$url['path'])
$url['path'] = $last_url['path'];
$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
curl_setopt($ch, CURLOPT_URL, $new_url);
//debug('Redirecting to', $new_url);
return curl_redir_exec($ch);
} 	else {
$curl_loops=0;
return $data;
}
}



function get_cookies($header){
$cookies="";
$rows=explode("\n",$header);
for ($i=0;$i<count($rows);$i++) {
$header=$rows[$i];
if (substr($header,0,11)=='Set-Cookie:') {
$pos=strpos($header,';');
if ($pos)
$header=substr($header,12,$pos-12); else
$header=substr($header,12,strlen($header)-13);
if ($cookies!="") $cookies.=';';
$cookies.=$header;
};
};
return $cookies;
}




