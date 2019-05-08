@extends('layout_admin')

@section('title')
ACCOUNT SETTINGS
@stop

@section('content')

@include('customer/top_menu')

<div class="container">
<h1>ACCOUNT SETTINGS</h1>
<div class="content-zone">


<h2>Edit account details</h2>
<? if($errors->has('email')){
echo "<div class='errors-block'>".$errors->first()."</div>";
}
?>
<form action="/user/edit-settings/" method="post">
<table>
<?/*<tr><td style="padding:10px;">Timezone:</td><td style="padding:10px;">
<select name="timezone" style="width:220px;">
<?php

$zones = timezone_identifiers_list();

foreach ($zones as $zone)
{
$zone = explode('/', $zone); // 0 => Continent, 1 => City

// Only use "friendly" continent names
if ($zone[0] == 'Africa' || $zone[0] == 'America' || $zone[0] == 'Antarctica' || $zone[0] == 'Arctic' || $zone[0] == 'Asia' || $zone[0] == 'Atlantic' || $zone[0] == 'Australia' || $zone[0] == 'Europe' || $zone[0] == 'Indian' || $zone[0] == 'Pacific')
{
if (isset($zone[1]) != '')
{
$locations[$zone[0]][$zone[0]. '/' . $zone[1]] = str_replace('_', ' ', $zone[1]); // Creates array(DateTimeZone => 'Friendly name')

}
}
}


foreach($zones as $array_loc){

$dtz = new \DateTimeZone($array_loc);
$time_in_dtz = new \DateTime('now', $dtz);
$offset = $dtz->getOffset( $time_in_dtz ) / 3600;

if(Auth::user()->timezone == $array_loc){$selected = "selected";}else{$selected = "";}

echo "<option value='".$array_loc."' ".$selected.">". $array_loc . "  GMT" . ($offset < 0 ? $offset : "+".$offset) ."</option>";

}

?>
</select>
</td></tr>
*/
?>
<tr><td style="padding:10px;">Email (Log in):</td><td style="padding:10px;"><input type="text" name="email" value="{{ Auth::user()->email }}"></td></tr>

<tr><td style="padding:10px;">Daily email summary?:</td><td style="padding:10px;"><input type="checkbox" name="daily_summary_email" value="1" <? if(Auth::user()->daily_summary_email == 1){echo "checked";} ?>></td></tr>

<tr><td style="padding:10px;"></td><td style="padding:10px;"><input type="submit" class="button" value="Save"></td></tr>
</table>

</form>



<div style="padding:20px 0;">
<h2>Change password</h2>
<? if($errors->has('password')){
echo "<div class='errors-block'>".$errors->first()."</div>";
}
?>
<form action="/user/edit-password/" method="post">
<table>
<tr><td style="padding:10px;">Current Password:</td><td style="padding:10px;"><input type="password" name="old_password" autocomplete="off" placeholder=" Current Password *"></td></tr>
<tr><td style="padding:10px;">New Password:</td><td style="padding:10px;"><input type="password" name="password" autocomplete="off" placeholder=" New Password *"></td></tr>
<tr><td style="padding:10px;">Confirm New Password:</td><td style="padding:10px;"><input type="password" name="password_2" autocomplete="off" placeholder=" New Password *"></td></tr>
<tr><td style="padding:10px;"></td><td style="padding:10px;"><input type="submit" class="button" value="Save"></td></tr>
</table>
</form>
</div>








<div style="padding:20px 0;">
<h2>Billing</h2>

<?

if( isset($billing_subscription) && $billing_subscription != "" ){

echo "<div style='padding:20px;'>".$billing_subscription."</div>";

}

?>



<div style="padding:20px;width:400px;">
<a href="/user/paynow/">Pay now</a>
</div>


<?
/*
if( isset($transactions) && count($transactions) > 0 ){
?>
<table class='tab-list' style='width:100%;'>
<tr class='tab-header'><td>ID</td><td>Date</td><td>Amount</td><td>Status</td></tr>
<?

foreach($transactions->transactions as $transactionItem){

$trDate = $transactionItem->createdAt->format('Y-m-d H:i:s');

echo "<tr>
<td>".$transactionItem->id."</td>
<td>".$trDate."</td>
<td>".$transactionItem->amount."</td>
<td>".$transactionItem->status."</td>
</tr>";

}

?>
</table>
<?
}
*/
?>


</div>




<div style="padding:20px 0;">
<h2>Link account to Adwords</h2>
Please <a href="<? echo "https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/adwords&response_type=code&access_type=offline&redirect_uri=http://tiroks.com/phpExternalClasses/adwordsAppRedirect.php&client_id=688017308969-a84rong3bm8udcrel685raj5l2ee4jh5.apps.googleusercontent.com"; ?>">Click Here</a> to link your Adwords account.

</div>






<script type="text/javascript">




<?
if(Auth::user()->timezone == ""){
?>
$(document).ready(function () {

var tz = jstz.determine();

if (typeof (tz) === 'undefined') {
response_text = 'No timezone found';
} else {
$("select[name='timezone']").val(tz.name());
}

})
<?
}
?>

$(document).ready(function(){

$(".add-new-credit-card").click(function(){
$("#billing-form").show();
})

$(".close-billing-form").click(function(){
$("#billing-form").hide();
})

})

</script>
<script type="text/javascript" src="/js/jstz.min.js"></script>

</div>

</div>
<div class="clear"></div>
@stop