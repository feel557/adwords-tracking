@extends('layout_admin')

@section('title')
User detail
@stop

@section('content')

@include('admin/top_menu')

<div class="container">

<h1>User detail</h1>
<div class="content-zone">

@if($errors->has())
   @foreach ($errors->all() as $error)
		<div class="warning-message">{{ $error }}</div>
  @endforeach
@endif

<?

if( isset($data) ){

?>


<div>

<h2>User info</h2>

<table class="reports-activity-summary" id="data-form" style="width:100%;">
<tr><td class="td-left">
<a href="/admin/login-as-user/?id=<? echo $data["user_data"][0]->id; ?>" class="button-common" style="float:left;">Log In as user</a>
</td><td colspan="2">
<a class="button-common" onclick="showF();" style="float:right;">Edit user</a>
<div class="clear"></div>
</td></tr>
<tr class="table-header-tr"><td class="td-left"><b>Field</b></td><td class="td-right"><b>Value</b></td></tr>
<tr><td class="td-left">Created At</td><td class="td-right"><? echo $data["user_data"][0]->created_at; ?></td></tr>
<tr><td class="td-left first-td">Full Name</td><td class="td-right first-td"><? echo $data["user_data"][0]->first_name." ".$data["user_data"][0]->last_name; ?></td></tr>
<tr><td class="td-left">Phone</td><td class="td-right"><? echo $data["user_data"][0]->phone; ?></td></tr>
<tr><td class="td-left first-td">Email</td><td class="td-right first-td"><? echo $data["user_data"][0]->email; ?></td></tr>

<tr><td class="td-left">Trial</td><td class="td-right"><? if($data["user_data"][0]->trial == 2){echo "No";}else{echo "Yes";} ?></td></tr>
<?/*
<tr><td class="td-left">Time zone</td><td class="td-right"><? echo $data["user_data"][0]->timezone; ?></td></tr>
<tr><td class="td-left first-td">ZIP</td><td class="td-right first-td"><? echo $data["user_data"][0]->zipcode; ?></td></tr>
<tr><td class="td-left">Location</td><td class="td-right"><? echo $data["user_data"][0]->state; ?></td></tr>
<tr><td class="td-left first-td">Address 1</td><td class="td-right first-td"><? echo $data["user_data"][0]->address1; ?></td></tr>
<tr><td class="td-left">Address 2</td><td class="td-right"><? echo $data["user_data"][0]->address2; ?></td></tr>
*/?>
</table>



<form action="/admin/edit-user/" method="post">
<table class="reports-activity-summary" id="edit-form" style="width:100%;display:none;">
<tr><td colspan="2">
<a class="button-common" onclick="closeF();" style="float:right;">Close form</a>
<div class="clear"></div>
</td></tr>
<tr class="table-header-tr"><td class="td-left"><b>Field</b></td><td class="td-right"><b>Value</b></td></tr>
<tr><td class="td-left first-td">First Name</td><td class="td-right first-td">
<input type="text" name="first_name" placeholder="First Name *" value="<? if(isset($data["user_data"][0]->first_name)){echo $data["user_data"][0]->first_name;} ?>"></td></tr>

<tr><td class="td-left">Last Name</td><td class="td-right">
<input type="text" name="last_name" placeholder="Last Name *" value="<? if(isset($data["user_data"][0]->last_name)){echo $data["user_data"][0]->last_name;} ?>"></td></tr>

<tr><td class="td-left first-td">Phone</td><td class="td-right first-td"><input type="text" name="phone" value="<? if(isset($data["user_data"][0]->phone)){echo $data["user_data"][0]->phone;} ?>"></td></tr>

<tr><td class="td-left">Email</td><td class="td-right"><input type="text" name="email" placeholder="Last Name *" value="<? if(isset($data["user_data"][0]->email)){echo $data["user_data"][0]->email;} ?>"></td></tr>



<?/*
<tr><td class="td-left first-td">State</td><td class="td-right first-td"><input type="text" name="state" value="<? if(isset($data["user_data"][0]->state)){echo $data["user_data"][0]->state;} ?>"></td></tr>

<tr><td class="td-left">ZIP</td><td class="td-right"><input type="text" name="zipcode" value="<? if(isset($data["user_data"][0]->zipcode)){echo $data["user_data"][0]->zipcode;} ?>"></td></tr>

<tr><td class="td-left first-td">Address 1</td><td class="td-right first-td"><input type="text" name="address1" value="<? if(isset($data["user_data"][0]->address1)){echo $data["user_data"][0]->address1;} ?>"></td></tr>

<tr><td class="td-left">Address 2</td><td class="td-right"><input type="text" name="address2" value="<? if(isset($data["user_data"][0]->address2)){echo $data["user_data"][0]->address2;} ?>"></td></tr>


<tr><td class="td-left first-td">Time zone</td><td class="td-right first-td">

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

if($data["user_data"][0]->timezone == $array_loc){$selected = "selected";}else{$selected = "";}

echo "<option value='".$array_loc."' ".$selected.">". $array_loc . "  GMT" . ($offset < 0 ? $offset : "+".$offset) ."</option>";

}

?>
</select>
</td></tr>
*/?>
<tr><td colspan="2">
<input type="hidden" name="id" value="<? if(isset($data["user_data"][0]->id)){echo $data["user_data"][0]->id;} ?>">
<input type="submit" value="Save" style="display:block;margin:20px auto;"><div class="clear"></div></td></tr>
</table>
</form>
</div>

<? /* ?>
<div class="clear"></div>
<br><br>
<h2>Billing data</h2>

<table class="reports-activity-summary" style="width:100%;">
<tr><td class="td-left first-td">
Last billing
</td><td class="td-right first-td">
<?

if(isset($data["user_transactions"])){

	$i=0;
	foreach($data["user_transactions"]->transactions as $transactionItem){

		$trDate = $transactionItem->createdAt->format('Y-m-d H:i:s');

		echo " ".$trDate." <br> $ ".$transactionItem->amount." <br> ".$transactionItem->status." ";

		if($i==0){break;}

	}

}

?>
</td></tr>
<tr><td class="td-left">
Clicks from last billing
</td><td class="td-right">
<?

	echo $data["count_click_last_transaction"];

?>
</td></tr>



<tr><td class="td-left first-td">Billing Plan  &nbsp; &nbsp;</td><td class="td-right first-td">
<?

if(isset($data["user_data"][0]->billing_plan_id)){
	if($data["user_data"][0]->billing_plan_id!=""){
		echo $data["user_data"][0]->billing_plan_id;
	}else{
		echo "";
	}
} ?>

</td></tr>
<tr><td class="td-left">Is trial?</td><td class="td-right"><?
if($data["user_data"][0]->billing_plan_id!=""){
$date1 = $data["billing_subscription"]->firstBillingDate;
$date2 = $date1->getTimestamp();
$nowTime = time();
if($nowTime < $date2){echo "Trial";}else{echo "No";}
}
?></td></tr>
<tr><td class="td-left first-td">Credit card Number</td><td class="td-right first-td"><? if(isset($data["paymentMethod"]->bin)){echo $data["paymentMethod"]->bin . "***" . $data["paymentMethod"]->last4;} ?></td></tr>
<tr><td class="td-left">Expire date</td><td class="td-right"><? if(isset($data["paymentMethod"]->expirationMonth)){echo $data["paymentMethod"]->expirationMonth." / ".$data["paymentMethod"]->expirationYear;} ?></td></tr>
<tr><td class="td-left first-td">Change Billing Plan &nbsp; &nbsp;</td>
<td class="td-right first-td"><form action="/admin/change-billing-plan/" method="post"><select name="planId"><?

foreach($data['billing_plans'] as $planItem){

echo "<option value='".$planItem->id."'>".$planItem->id."</option>";

}

?></select>
<input type="hidden" name="userId" value="<? echo $data["user_data"][0]->id; ?>">
<input type="submit" value='Save'>
</form></td>
</tr>

</table>
<? */ ?>



<? } ?>


<script>

function closeF(){

$("#data-form").show();
$("#edit-form").hide();

}

function showF(){

$("#data-form").hide();
$("#edit-form").show();

}


$(document).ready(function(){

$('#datetimepicker1').datetimepicker({
timepicker:false,
format:'Y-m-d'
});

$('#datetimepicker2').datetimepicker({
timepicker:false,
format:'Y-m-d'
});

})
</script>
<link rel="stylesheet" type="text/css" href="/js/jquery.datetimepicker.css"/ >
<script src="/js/jquery.datetimepicker.js"></script>



@stop