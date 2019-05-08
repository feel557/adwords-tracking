@extends('layout_admin')

@section('title')
Billing Plans
@stop

@section('content')

@include('admin/top_menu')

<div class="container">

<h1>Billing Plans</h1>
<div class="content-zone">

<table class='tab-list' style='width:100%;'>
<tr class='tab-header'><td>ID</td><td>Price</td><td>Billing Cycle, month(s)</td><td>Actions</td></tr>
<?

//var_dump($default_plan);

foreach($billing_plans as $billing_plan_item){

if( $default_plan[0]->value == $billing_plan_item->id ){$actionVar = "Default";}else{$actionVar = "<a href='/admin/set-default-billing-plan/?plan_id=".$billing_plan_item->id."'>Set as default</a>";}

echo "<tr>
<td>".$billing_plan_item->id."</td>
<td>".$billing_plan_item->price."</td>
<td>".$billing_plan_item->billingFrequency."</td>
<td>".$actionVar."</td>
</tr>";

}


?>

</div>

</div>
<div class="clear"></div>
@stop