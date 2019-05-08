@extends('layout')

@section('title')
Login
@stop

@section('content')
<div class="login-block">

@if (Session::has('alert'))
<div class="alert alert-danger">
<p>{{ Session::get('alert') }}
</div>
@endif

<h2>Login</h2>
<form action="{{ action('Auth\AuthController@postLogin') }}" method="post">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="form-group">
<label for="level" class="col-sm-2 control-label">Email</label>
<div class="col-sm-5">
<input type="text" name="email">
</div>
</div>
<div class="form-group">
<label for="password" class="col-sm-2 control-label">Password</label>
<div class="col-sm-5">
<input type="password" name="password">
</div>
</div>
<div class="form-group">
<div class="col-sm-2">&nbsp;</div>
<div class="col-sm-5">


<div style="float:left;">
<input type="submit" class="button" value="Sign In">
</div>

<div style="float:right;">
<!--<a href="/auth/signup/">Sign Up</a>-->
<br>
<a href="/password/email/">Recover</a>
</div>

<div class="clear"></div>

</div>
</div>
</form>
</div>

@stop