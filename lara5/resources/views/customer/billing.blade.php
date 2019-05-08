@extends('layout_admin')

@section('title')
Billing
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>Billing</h1>

<?

if( $billing_subscription != "" ){

	echo "<div style='padding:20px;'>".$billing_subscription."</div>";

}

?>

<div class="add-new-credit-card" style="padding:20px;cursor:pointer;">+ Add/Update Credit Card</div>

<div id="billing-form" style="display:none;padding:20px;">
<div class="relative">
<div class="close-billing-form">Close X</div>
</div>
<div style="display:none;font-size:14px;">If you already have billing subscription it will be cancelled.</div>

@include('customer.braintree_form')

</div>

<table class='tab-list' style='width:100%;'>
<tr class='tab-header'><td>ID</td><td>Date</td><td>Amount</td><td>Status</td></tr>
<?

if( isset($transactions) && count($transactions) > 0 ){
foreach($transactions->transactions as $transactionItem){

$trDate = $transactionItem->createdAt->format('Y-m-d H:i:s');

echo "<tr>
<td>".$transactionItem->id."</td>
<td>".$trDate."</td>
<td>".$transactionItem->amount."</td>
<td>".$transactionItem->status."</td>
</tr>";

}
}

?>
</table>

<? //echo $adwordsArray->appends(['id' => Input::get('id')])->render(); ?>

</div>
</div>











<script>

$(document).ready(function(){

$(".add-new-credit-card").click(function(){

$("#billing-form").show();

})


$(".close-billing-form").click(function(){

$("#billing-form").hide();

})


})


</script>



@stop