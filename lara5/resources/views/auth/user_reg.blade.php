@extends('layout_index')

@section('title')
Sign Up
@stop

@section('content')
<div class="login-block">
<?

if (isset($errors) && $errors->all()){

?>
<div class="alert alert-danger">
<? echo $errors->first('email'); ?>
<? echo $errors->first('password'); ?>
@foreach($errors->all() as $error)

<p><? echo $error; ?></p>

@endforeach
</div>
<?
}
?>
<h2>Sign Up</h2>

<form action="{{ action('Auth\AuthController@postAdd') }}" method="post">
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="form-group">
<label for="sector" class="col-sm-2 control-label">Name</label>
<div class="col-sm-5">
<input type="text" name="username" placeholder=" Name *">
</div>
</div>


<div class="form-group">
<label for="level" class="col-sm-2 control-label">Email</label>
<div class="col-sm-5">
<input type="text" name="email" placeholder=" Email *">
</div>
</div>

<div class="form-group">
<label for="level" class="col-sm-2 control-label">Address</label>
<div class="col-sm-5">
<input type="text" name="email" placeholder=" Address *">
</div>
</div>

<div class="form-group">
<label for="level" class="col-sm-2 control-label">Credit Card Number</label>
<div class="col-sm-5">
<input type="text" name="email" placeholder=" Credit Card Number *">
</div>
</div>


<div class="form-group">
<label for="password" class="col-sm-2 control-label">Password</label>
<div class="col-sm-5">
<input type="password" name="password" placeholder=" Password *">
</div>
</div>







<div class="form-group">
<div class="col-sm-2">&nbsp;</div>
<div class="col-sm-5">
<input type="submit" class="button" value="Sign Up">
</div>
</div>
</form>

</div>

@stop