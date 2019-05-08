@extends('layout_admin')

@section('title')
User detail
@stop

@section('content')

@include('admin/top_menu')

<div class="container">

<h1>Dashboard</h1>
<div class="content-zone">


<?

if( isset($data) ){

?>


<div>

<div class="reports-date-range-block" style="display:block;">
<div style="padding:20px;">
<div class="header-inner-block" style="padding:0 0 10px;font-weight:bold;">Date Range:</div>
<form method="get" action="/admin/main/">
<div style="float:left;padding:0 10px;"> From: <input class="datetimepicker" style="width:100px;" name="date1" id="datetimepicker1" type="text" placeholder=" From date *" value="<? if(isset($_GET["date1"])){echo $_GET["date1"];} ?>">  </div>
<div style="float:left;padding:0 10px;"> To: <input class="datetimepicker" style="width:100px;" name="date2" id="datetimepicker2" type="text" placeholder=" To date *" value="<? if(isset($_GET["date2"])){echo $_GET["date2"];} ?>"> </div>
<div style="float:left;padding:3px 10px;"> <input type="submit" class="button-common" value="Apply"></div>
<div class="clear"></div>
<input type="hidden" name="range_type" value="2">
</form>
</div>

<div class="clear"></div>
</div>

<table class="tab-list" style="width:100%;">
<tr class="tab-header"><td><b>Field</td><td><b>Value</b></td></tr>
<tr><td class="td-left">Total monthly revenue</td><td class="td-right">$ <? echo $data['month_total_revenue']; ?></td></tr>
<tr><td class="td-left first-td">Total clicks</td><td class="td-right first-td"><? echo $data['total_clicks']; ?></td></tr>
<tr><td class="td-left">Total Blocked IPs</td><td class="td-right"><? echo $data['total_clicks']; ?></td></tr>
<tr><td class="td-left first-td">Number of customers</td><td class="td-right first-td"><? echo count($data['users']); ?></td></tr>
<tr><td class="td-left"><a href="/admin/users/?value_type=3">Customers in Trial</a></td><td class="td-right"><? echo count($data['users_trial']); ?></td></tr>
</table>

</div>

<? } ?>

<div class="clear"></div>


<script>

$(document).ready(function(){

$(".reports-links").click(function(){

	var date1 = $('#datetimepicker1').val();
	var date2 = $('#datetimepicker2').val();

	var baseUrl = $(this).attr("href2");
	var resultUrl = baseUrl+"&date1="+date1+"&date2="+date2;

	document.location.href = resultUrl;

});

$('#datetimepicker1').datetimepicker({
timepicker:false,
format:'Y-m-d'
});

$('#datetimepicker2').datetimepicker({
timepicker:false,
format:'Y-m-d'
});


$("#get-report").click(function(){

var date = $('#datetimepicker').val();

$.ajax({
type: "POST",
url: "/phpExternalClasses/showAdwordsReport.php",
data: {
"date":date
},
cache: false,
success: function(response){
$("#response-page").html(response);
}})

})


})
</script>
<link rel="stylesheet" type="text/css" href="/js/jquery.datetimepicker.css"/ >
<script src="/js/jquery.datetimepicker.js"></script>



@stop