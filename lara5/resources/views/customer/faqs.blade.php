@extends('layout_admin')

@section('title')
Support
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<h1>Support</h1>

<div class="content-zone">
<h2>Request Support of Ask a Question:</h2>
<form action="/user/send-message/" method="post">
<table>
<tr><td style="padding:10px;">Name:</td><td style="padding:10px;"><input type="text" name="name" value="<?= Auth::user()->first_name; ?>" autocomplete="off"></td></tr>
<tr><td style="padding:10px;">Email Address:</td><td style="padding:10px;"><input type="text" name="email" value="<?= Auth::user()->email; ?>" autocomplete="off"></td></tr>
<tr><td style="padding:10px;">Your Comment or Question:</td><td style="padding:10px;"><textarea name="text" style="height:200px;"></textarea></td></tr>
<tr><td style="padding:10px;"></td><td style="padding:10px;"><input type="submit" class="button" value="Send"></td></tr>
</table>
</form>

</div>
</div>



@stop