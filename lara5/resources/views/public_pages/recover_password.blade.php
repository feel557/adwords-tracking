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

<? if($errors->has('status')){
echo "<div class='errors-block' style='padding-bottom:10px;'>".$errors->first()."</div>";
echo "<br><br><a href='/login/'>Login</a>";

}else{
?>

<form action="{{ action('Auth\PasswordController@postRemind') }}" method="post" class="support-form form-inline">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<table style="width:100%;">
<tr>
<td>
<label class="sr-only" for="user-name">Email address: </label>
</td><td>
<input type="text" name="email" style="width:250px;" placeholder="Email *">
</td>
</tr>

	</table>								


								<br>
<input type="submit" class="trial-button" style="display:block;margin:0 auto;" value="OK">
								</form>

<? } ?>


</div>








@stop