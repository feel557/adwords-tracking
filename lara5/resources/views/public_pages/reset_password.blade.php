@extends('lp_layout')

@section('title')
Log In
@stop


@section('header')

@stop

@section('content')



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



<form action="{{ action('Auth\PasswordController@postReset') }}" method="post">
<meta name="csrf-token" content="{{ csrf_token() }}" />



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
<label class="sr-only" for="user-name">New Password</label>
</td><td>
<input type="password" name="password" class="lineField robotoLight" placeholder="New password *">
</td>
</tr>

<tr>
<td>
<label class="sr-only" for="user-name">Confirm Password</label>
</td><td>
<input type="password" name="password_confirmation" class="lineField robotoLight" placeholder="Confirm Password *">
</td>
</tr>


</table>
<div style="text-align:center;padding-top:20px;">
<input type="hidden" name="token" value="{{ $token }}" />
<input type="submit" class="trial-button" value="Reset">
</div>
</form>



</div>








@stop



