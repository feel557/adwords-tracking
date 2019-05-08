@extends('lp_layout')

@section('title')
Log In
@stop


@section('header')

@stop

@section('content')


<?php

if(isset($signup_message)){
	echo '
	<div style="text-align:center;padding:20px;background:#95d880;border-radius:10px;margin:20px 0;color:#fff;">
	<p>'.$signup_message.'</p>
	</div>
	';
}

?>

							
@if (Session::has('alert'))
<div style="text-align:center;padding:20px;background:#95d880;border-radius:10px;margin:20px 0;color:#fff;">
<p>{{ Session::get('alert') }}
</p>
</div>
@endif

<div class="center-block" style="border:2px solid #ddd;padding:30px;border-radius:10px;width:350px;margin:0 auto;font-size:14px;margin-top:70px;">
<div style="padding-bottom:20px;">
<table style="width:100%;">
<tr>
<td style="text-align:center;">
<img src="http://www.clickmonitor.co.uk/wp-content/uploads/2016/03/logo-1.png">
</td>
</tr>
</table>
</div>


<form action="{{ action('Auth\AuthController@postLogin') }}" method="post" class="support-form form-inline">


<table style="width:100%;">
<tr>
<td>
<label class="sr-only" for="user-name">Email</label>
</td><td>
<input type="text" name="email" class="lineField robotoLight" placeholder="Email *">
</td>
</tr>

<tr>
<td>

<label class="sr-only" for="user-email">Password</label>
</td><td>
<input type="password" name="password" class="lineField robotoLight" placeholder="Password *">
<div>
            <input name="remember" type="checkbox" /> Remember me
        </div>
</td>
</tr>
	</table>								


								<br>
<input type="submit" class="trial-button" style="display:block;margin:0 auto;" value="Login">
								</form>

<div style="padding:30px 0 10px;text-align:center;">
<a href="/password/email/">I forgot my password</a>
</div>



</div>








@stop



