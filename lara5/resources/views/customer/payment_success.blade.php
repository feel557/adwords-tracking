@extends('layout_admin')

@section('title')
Payment
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<h1>Payment</h1>

<div class="content-zone">

<? if($data['response_status'] == 1){ ?>
<table>
	<tbody>
		<tr>
			<td>Name:
			<td><?= $data['name'] ?>
		</tr>
		<tr>
			<td>You will pay £<?= $data['amount'] ?> every month.
		</tr>
	<tbody>
</table>
<form action='/user/payment-confirm/' METHOD='POST'>
<input type="submit" value="Confirm"/>
</form>
<? }else{

echo $data['message'];

} ?>


</div>
</div>



@stop