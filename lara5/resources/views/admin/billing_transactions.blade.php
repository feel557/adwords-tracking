@extends('layout_admin')

@section('title')
Transactions
@stop

@section('content')

@include('admin/top_menu')

<div class="container">

<h1>Transactions</h1>
<div class="content-zone">


<div class="reports-date-range-block" style="display:block;">
<div style="padding:20px;">
<div class="header-inner-block" style="padding:0 0 10px;font-weight:bold;">Date Range:</div>
<form method="get" action="/admin/billing-transactions/">
<div style="float:left;padding:0 10px;"> From: <input class="datetimepicker" style="width:100px;" name="date1" id="datetimepicker1" type="text" placeholder=" From date *" value="<? if(isset($_GET["date1"])){echo $_GET["date1"];} ?>">  </div>
<div style="float:left;padding:0 10px;"> To: <input class="datetimepicker" style="width:100px;" name="date2" id="datetimepicker2" type="text" placeholder=" To date *" value="<? if(isset($_GET["date2"])){echo $_GET["date2"];} ?>"> </div>
<div style="float:left;padding:3px 10px;"> <input type="submit" class="button-common" value="Apply"></div>
<div class="clear"></div>
<input type="hidden" name="range_type" value="2">
</form>
</div>

<div class="clear"></div>
</div>

<table class='tab-list' style='width:100%;'>
<tr class='tab-header'>
<td><b>ID</b></td>
<td><b>Amount</b></td>
<td><b>Name</b></td>
<td><b>CreatedAt</b></td>
<td><b>Status</b></td>
</tr>
<?


foreach($data as $transactionItem) {

echo "<tr>";
echo "<td>".$transactionItem->id."</td>";
echo "<td>".$transactionItem->amount."</td>";
echo "<td>".$transactionItem->customerDetails->firstName."</td>";
echo "<td>".$transactionItem->createdAt->format('Y-m-d H:i:s')."</td>";
echo "<td>".$transactionItem->status."</td>";
echo "</tr>";

}


?>
</table>

<? //echo $users_array->render(); ?>

</div>

</div>

<script>

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

<div class="clear"></div>
@stop