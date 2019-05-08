<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title')</title>
		<link rel="shortcut icon" type="image/x-icon" href="favicon.png" />



		<script src="/js/jquery-1.11.2.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
<style>

body{
font-family:Arial;
font-size:16px;
color:#111;
}


.trial-button {
    height: 40px;
	border:0px solid #ddd;
    border-radius: 10px;
    text-align: center;
    display: inline-block;
    padding: 0 15px;
    background: rgb(0,173,238);
    background: -moz-linear-gradient(left, rgb(0,173,238) 0%, rgb(139,197,63) 100%);
    background: -webkit-linear-gradient(left, rgb(0,173,238) 0%, rgb(139,197,63) 100%);
    background: linear-gradient(to right, rgb(0,173,238) 0%, rgb(139,197,63) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00adee', endColorstr='#8bc53f', GradientType=1 );
    line-height: 40px;
    color: #FFF;
    font-family: Arial, 'open_sansbold';
    font-size: 14px;
	cursor:pointer;
}

input[type=text], input[type=password]{
	border:1px solid #ddd;
	border-radius:3px;
	padding:5px 5px;


}

a{
color:#222;
}

</style>
	</head>
	<body class="animated-css">
		<div class="sp-body">
@yield('loader')


@yield('header')


				

			

@yield('content')


		</div>
		
	
	</body>
</html>