@extends('layout_admin')

@section('title')
Payment
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<h1>Payment</h1>

<div class="content-zone">


<form action='/user/paynow/' METHOD='POST'>
<select name="amt">
<option value="20">Start-Up Package - £20.00 Per Month</option>
<option value="25">Professional Package - £25.00 Per Month</option>
<option value="50">Small Business Package- £50.00 Per Month</option>
</select>
<input type="submit" value="Pay"/>
</form>



</div>
</div>



@stop