@extends('layout_admin')

@section('title')
Tracker reports
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<h1>MONITOR <? if( isset($_GET['tracker_id']) ){ echo "#".$_GET['tracker_id']; } ?> REPORTS</h1>


<div class="content-zone" style="display:block;background:#fff;">
	<div class="header-inner-block" style="padding:0 0 10px;font-weight:bold;">Date Range:</div>
	<form method="get" action="/user/reports/?tracker_id=<? if( isset($_GET['tracker_id']) ){ echo $_GET['tracker_id']; }else{ echo 0; }?>">
		<div style="float:left;padding:0 10px;">
		<select id="date-range-type-select" name="range_type">
		<option value="1" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 1){ echo "selected";} ?>>Last 7 days</option>
	<option value="3" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 3){ echo "selected";} ?>>Today</option>
	<option value="4" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 4){ echo "selected";} ?>>Yesterday</option>
	<option value="5" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 5){ echo "selected";} ?>>Last week</option>
	<option value="6" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 6){ echo "selected";} ?>>This week</option>
	<option value="7" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 7){ echo "selected";} ?>>Last 14 days</option>
	<option value="8" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 8){ echo "selected";} ?>>Last 30 days</option>
	<option value="9" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 9){ echo "selected";} ?>>Last month</option>
	<option value="10" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 10){ echo "selected";} ?>>This month</option>
	<?/*<option value="2" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 2){ echo "selected";} ?>>Custom</option>*/?>
	</select>
		</div>
		<div class="date-range-dates-block" style="float:left;padding:0 10px;display:none;">From: <input class="datetimepicker" style="width:100px;" name="date1" id="datetimepicker1" type="text" placeholder=" From date *" value="<? if( isset($_GET['date1']) ){ echo $_GET['date1']; } ?>">  </div>
		<div class="date-range-dates-block" style="float:left;padding:0 10px;display:none;"> To: <input class="datetimepicker" style="width:100px;" name="date2" id="datetimepicker2" type="text" placeholder=" To date *" value="<? if( isset($_GET['date2']) ){ echo $_GET['date2']; } ?>"> </div>
		<div style="float:left;padding:3px 10px;"> <input type="submit" class="button-common" value="Change Dates"></div>
		<div class="clear"></div>
		<input type="hidden" name="tracker_id" value="<? if( isset($_GET['tracker_id']) ){ echo $_GET['tracker_id']; }else{ echo 0; }?>">
		<input type="hidden" name="report_type" value="<? if( isset($_GET['report_type']) ){ echo $_GET['report_type']; }else{ echo 1; }?>">
	</form>
	<div class="clear"></div>
</div>






<div style="margin-bottom:20px;">
	<div class="summary-total" style="display:block;">
	<div class="summary-total-block" style="background:#fff;border-bottom:4px solid #22BAA0;"><div class="summary-total-block-title">Total clicks</div> <div class="summary-total-block-data"><? echo $trackers_data["all_statistic"]["clicks_count"]; ?></div></div>
	<div class="summary-total-block" style="background:#fff;border-bottom:4px solid #7a6fbe;"><div class="summary-total-block-title">Unique IPs</div> <div class="summary-total-block-data"><? echo $trackers_data["all_statistic"]["ip_count"]; ?></div></div>
	<div class="summary-total-block-last" style="background:#fff;border-bottom:4px solid #f25656;"><div class="summary-total-block-title">Unique Cookies</div> <div class="summary-total-block-data"><? echo $trackers_data["all_statistic"]["cookies_count"]; ?></div></div>
	<div class="clear"></div>
	</div>
</div>






<div style="">


	<div style="float:left;width:22%;margin-right:4%;background-color:#22BAA0;">
		<div style="padding:10px;color:#fff;text-align:right;">
			<span style="font-size:20px;font-weight:bold;"><? echo $trackers_data["all_statistic"]["alert_1_views"]; ?></span>
			<div style="font-size:12px;">Alert level 1 triggered</div>
		</div>
	</div>

	<div style="float:left;width:22%;margin-right:4%;background-color:#7a6fbe;">
		<div style="padding:10px;color:#fff;text-align:right;">
			<span style="font-size:20px;font-weight:bold;"><? echo $trackers_data["all_statistic"]["alert_2_views"]; ?></span>
			<div style="font-size:12px;">Alert level 2 triggered</div>
		</div>
	</div>


	<div style="float:left;width:22%;margin-right:4%;background-color:#f25656;">
		<div style="padding:10px;color:#fff;text-align:right;">
			<span style="font-size:20px;font-weight:bold;"><? echo $trackers_data["all_statistic"]["ip_blocked"]; ?></span>
			<div style="font-size:12px;">IP addresses blocked</div>
		</div>
	</div>


	<div style="float:left;width:22%;background-color:#12AFCB;">
		<div style="padding:10px;color:#fff;text-align:right;">
			<span style="font-size:20px;font-weight:bold;"><? echo $trackers_data["all_statistic"]["shown_warnings"]; ?></span>
			<div style="font-size:12px;">Warnings shown</div>
		</div>
	</div>


<div class="clear"></div>
</div>



<div class="content-zone" style="display:block;background:#fff;">
<h2>View Reports</h2>
<ul class="list-of-reports">
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? if( isset($_GET['tracker_id']) ){ echo $_GET['tracker_id']; }else{ echo 0; }?>&amp;report_type=1&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Top Visitors by IP address</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? if( isset($_GET['tracker_id']) ){ echo $_GET['tracker_id']; }else{ echo 0; }?>&amp;report_type=3&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Keyword Activity Overview</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? if( isset($_GET['tracker_id']) ){ echo $_GET['tracker_id']; }else{ echo 0; }?>&amp;report_type=2&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Visitor Locations</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? if( isset($_GET['tracker_id']) ){ echo $_GET['tracker_id']; }else{ echo 0; }?>&amp;report_type=4&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Top Visitors by Cookie ID</a></li>
</ul>
<div class="clear"></div>
</div>




</div>


<script>



$(document).ready(function(){



$("#date-range-type-select").change(function(){

	var dateRangeType = $(this).val();

	if(dateRangeType == 2){
		$(".date-range-dates-block").show();
	}else{
		$(".date-range-dates-block").hide();
	}


})


$(".reports-links").click(function(){

	var date1 = $('#datetimepicker1').val();
	var date2 = $('#datetimepicker2').val();

	var baseUrl = $(this).attr("href2");
	var resultUrl = baseUrl+"&date1="+date1+"&date2="+date2;

	document.location.href = resultUrl;

});


/* DateTimePicker */

$('#datetimepicker1').datetimepicker({
timepicker:false,
format:'d-m-Y',
value:<? if(isset($_GET['date1'])){echo "'".$_GET['date1']."'";}else{echo "getStartDate()";} ?>
});

$('#datetimepicker2').datetimepicker({
timepicker:false,
format:'d-m-Y',
value:<? if(isset($_GET['date2'])){echo "'".$_GET['date2']."'";}else{echo "getEndDate()";} ?>
});


})




/* date functions */

function getEndDate(){
	var currentDate = new Date();
	var monthEnd = currentDate.getMonth()+1;
	if (monthEnd < 10) {monthEnd = '0' + monthEnd;}
	var dayEnd = currentDate.getDate();
	if (dayEnd < 10) {dayEnd = '0' + dayEnd;}
	var resultEndDate = dayEnd +"-"+ monthEnd +"-"+ currentDate.getFullYear();
	return resultEndDate;
}

function getStartDate(){
	var startDate = getDateAgo(7);
	var monthStart = startDate.getMonth()+1;
	if (monthStart < 10) {monthStart = '0' + monthStart;}
	var dayStart = startDate.getDate();
	if (dayStart < 10) {dayStart = '0' + dayStart;}
	var resultStartDate = dayStart +"-"+ monthStart +"-"+ startDate.getFullYear();
	return resultStartDate;
}

function getDateAgo(days) {
  var dateCopy = new Date();
  dateCopy.setDate(dateCopy.getDate() - days);
  return dateCopy;
}








</script>
<link rel="stylesheet" type="text/css" href="/js/jquery.datetimepicker.css"/ >
<script src="/js/jquery.datetimepicker.js"></script>


@stop