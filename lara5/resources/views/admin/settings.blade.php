@extends('layout_admin')

@section('title')
Settings
@stop

@section('content')

@include('admin/top_menu')

<div class="container">
<h1>Settings</h1>
<div class="content-zone">
<br>
<h2>Edit email</h2>
<form action="/admin/edit-settings/" method="post">

<table>
<tr><td style="padding:10px;">Email:</td><td style="padding:10px;"><input type="text" name="email" value="{{ Auth::user()->email }}"></td></tr>
<tr><td style="padding:10px;">Password:</td><td style="padding:10px;"><input type="password" name="password" autocomplete="off" placeholder=" Password *"></td></tr>
<tr><td style="padding:10px;">Confirm Password:</td><td style="padding:10px;"><input type="password" name="password_2" autocomplete="off" placeholder=" Password *"></td></tr>
<tr><td style="padding:10px;"></td><td style="padding:10px;"><input type="submit" class="button" value="Edit"></td></tr>
</table>

</form>


</div>

</div>
<div class="clear"></div>
@stop