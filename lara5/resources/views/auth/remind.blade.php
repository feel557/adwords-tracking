@extends('layout_index')

@section('title')
Recover Password
@stop

@section('content')

<div class="login-block">

@if (Session::has('status'))
<div class="alert alert-success">
{{ Session::get('status') }}
</div>
@elseif (Session::has('error'))
<div class="alert alert-danger">
{{ Session::get('error') }}
</div>
@endif


<h2>Recover Password</h2>
<form action="{{ action('Auth\PasswordController@postRemind') }}" method="post">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="form-group">
<label for="level" class="col-sm-2 control-label">Email</label>
<div class="col-sm-5">
<input type="text" name="email">
</div>
</div>

<div class="form-group">
<div class="col-sm-2">&nbsp;</div>
<div class="col-sm-5">
<input type="submit" class="button" value="ОК">
</div>
</div>
</form>
</div>


@stop