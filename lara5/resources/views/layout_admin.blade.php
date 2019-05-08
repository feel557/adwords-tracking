<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<title>@yield('title')</title>
<meta name="keywords" content="Dashboard">
<meta name="description" content="Dashboard">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/admin_style.css') }}">
<!-- jQuery & jQuery UI -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="{{ URL::asset('img/favicon.ico') }}">
@yield('headExtra')

<script>

$(document).ready(function(){

$(".profile-active").mouseover(function(){
$(".profile-type-group-wrapper").show();
})
$(".profile-active").mouseout(function(){
$(".profile-type-group-wrapper").hide();
})
$(".profile-type-group-wrapper").mouseover(function(){
$(".profile-type-group-wrapper").show();
})
$(".profile-type-group-wrapper").mouseout(function(){
$(".profile-type-group-wrapper").hide();
})







})

</script>

</head>
<body>


@yield('content')
<div id="loader-preview"></div>
<div id="cover-background-unlayer"></div>
</body>
</html>