@extends('layout_index')

@section('title')
Reset password
@stop

@section('content')

<div class="login-block">

@if (Session::has('error'))
<div class="alert alert-danger">
{{ Session::get('error') }}
</div>
@endif

<h2>Reset password</h2>
<form action="{{ action('Auth\PasswordController@postReset') }}" method="post">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="form-group">
<label for="email" class="col-sm-2 control-label">E-Mail</label>
<div class="col-sm-5">
<input type="text" name="email">
</div>
</div>

<div class="form-group">
<label for="password" class="col-sm-2 control-label">New password</label>
<div class="col-sm-5">
<input type="password" name="password">
</div>
</div>

<div class="form-group">
<label for="password_confirmation" class="col-sm-2 control-label">Confirm Password</label>
<div class="col-sm-5">
<input type="password" name="password_confirmation">
</div>
</div>

<input type="hidden" name="token" value="{{ $token }}" />

<div class="form-group">
<div class="col-sm-2">&nbsp;</div>
<div class="col-sm-5">
<input type="submit" class="button" value="Save">
</div>
</div>
</form>
</div>
@stop