@extends('lp_layout')

@section('title')
Sign Up
@stop


@section('header')

@stop

@section('content')




<? if (isset($errors) && $errors->all()){ ?>
<div style="text-align:center;padding:20px;background:#d88680;border-radius:10px;margin:20px 0;color:#fff;">
@foreach($errors->all() as $error)
<p><? echo $error; ?></p>
@endforeach
</div>
<? } ?>
	

<?

/*
array(14) { ["first_name"]=> string(7) "wgrveeb" ["last_name"]=> string(5) "gewgw" ["email"]=> string(8) "k@st.com" ["address1"]=> string(10) "wwwwwwwwww" ["address2"]=> string(0) "" ["state"]=> string(4) "Iowa" ["zipcode"]=> string(5) "34238" ["country_id"]=> string(3) "236" ["credit_card_number"]=> string(0) "" ["exp_date_m"]=> string(0) "" ["exp_date_y"]=> string(0) "" ["credit_card_cvv"]=> string(0) "" ["password"]=> string(0) "" ["password_2"]=> string(0) "" }
*/
if( isset($_GET['name1']) ){
$first_name = $_GET['name1'];
}else{
$first_name = old("first_name");
}
if( isset($_GET['name2']) ){
$last_name = $_GET['name2'];
}else{
$last_name = old("last_name");
}
if( isset($_GET['email']) ){
$email = $_GET['email'];
}else{
$email = old("email");
}








?>

<div class="center-block" style="border:2px solid #ddd;padding:30px;border-radius:10px;width:350px;margin:0 auto;margin-top:70px;">
<div style="padding-bottom:20px;">
<table style="width:100%;">
<tr>
<td style="text-align:center;">
<img src="http://www.clickmonitor.co.uk/wp-content/uploads/2016/03/logo-1.png">
</td>
</tr>
</table>
</div>


<form action="{{ action('Auth\AuthController@postAdd') }}" method="post" id="braintree-payment-form" class="support-form form-inline">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<table style="width:100%;font-size:14px;">
<tr>
<td>
<label class="sr-only"><b>Company Name</b></label>
</td><td>
<input type="text" name="company_name" class="lineField robotoLight" placeholder="Company Name *" value="<?= old("company_name") ?>">
</td>
</tr>



<tr>
<td>
<label class="sr-only"><b>Contact Name</b></label>
</td><td>
<input type="text" name="first_name" class="lineField robotoLight" placeholder="Contact Name *" value="<?= old("first_name") ?>">
</td>
</tr>


<tr>
<td>
<label class="sr-only"><b>Contact Number</b></label>
</td><td>
<input type="text" name="phone" class="lineField robotoLight" placeholder="Contact Number *" value="<?= old("phone") ?>">
</td>
</tr>


<tr>
<td>
<label class="sr-only"><b>Website</b></label>
</td><td>
<input type="text" name="website" class="lineField robotoLight" placeholder="Website *" value="<?= old("website") ?>">
</td>
</tr>




<tr>
<td>
<label class="sr-only"><b>Email</b></label>
</td><td>
<input type="text" name="email" class="lineField robotoLight" placeholder="Email *" value="<?= old("email") ?>">
</td>
</tr>

<tr>
<td>
<label class="sr-only" for="user-email"><b>Password</b></label>
</td><td>
<input type="password" name="password" class="lineField robotoLight" autocomplete="off" placeholder="Password *">
</td>
</tr>


<tr>
<td>
<label class="sr-only" for="user-email"><b>Confirm Password</b></label>
</td><td>
<input type="password" name="password_2" class="lineField robotoLight" autocomplete="off" placeholder="Confirm Password *">
</td>
</tr>

</table>

<br>
<input type="submit" class="trial-button" style="display:block;margin:0 auto;" value="Sign Up">
</form>
</div>
							


@stop
