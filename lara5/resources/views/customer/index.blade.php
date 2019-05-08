@extends('layout_admin')

@section('title')
Main
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<h1>Dashboard</h1>

<?

//echo date("H:i:s");
//echo "<br>";

$created = strtotime(Auth::user()->created_at);
$diff = time() - $created;


if(Auth::user()->trial != 2 && $diff>14*60*60*24){
?>


<div class="content-zone">
Please pay for using service <a href="/user/paynow/">here</a>
</div>

<?
}else{
?>

<?
if($data['user_trackers'] > 0 && isset($trackers_data) && count($trackers_data)>0 ){
?>


<div class="content-zone" style="display:block;background:#fff;">
	<div class="header-inner-block" style="padding:0 0 10px;font-weight:bold;">Date Range:</div>
	<form method="get" action="/user/main/">

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

	<div class="date-range-dates-block" style="float:left;padding:0 10px;display:none;"> From: <input class="datetimepicker" style="width:100px;" name="date1" id="datetimepicker1" type="text" placeholder=" From date *">  </div>
	<div class="date-range-dates-block" style="float:left;padding:0 10px;display:none;"> To: <input class="datetimepicker" style="width:100px;" name="date2" id="datetimepicker2" type="text" placeholder=" To date *"> </div>
	<div style="float:left;padding:3px 10px;"> <input type="submit" class="button-common" value="Apply"></div>
	<div class="clear"></div>
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


<?/*
<div class="content-zone" style="display:block;background:#fff;">
<h2>View Reports</h2>
<ul class="list-of-reports">
<li><a href="/user/reports-all/">View All clicks from all trackers</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=0&report_type=1&range_type=1">Top Visitors by IP address</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=0&report_type=3&range_type=1">Keyword Activity Overview</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=0&report_type=4&range_type=1">Top Visitors by Cookie ID</a></li>
</ul>
<div class="clear"></div>
</div>
*/?>


<div class="content-zone" style="display:block;background:#fff;">
<h2>Lookup IP Address</h2>
<div style="padding:0px 0;">
<form action="/user/report-ip-detail/" method="get">
<div style="float:left;padding:0 10px;"> IP <input type="text" name="ip" placeholder=" IP *">  </div>
<div style="float:left;padding:3px 10px;"> <input type="submit" class="button-common" value="Lookup IP"> </div>
<div class="clear"></div>
</form>
</div>


</div>










<?
}
?>


<div class="content-zone">

<? if (isset($errors) && $errors->all()){ 
$i=0;
echo "<div class='errors-block'>";
	foreach($errors->all() as $error){
$i++;if($i==3){
		echo "<p>".$error."</p>";
	}}
echo "</div>";
} ?>



<?php

if ( $data["adwords_user_exist"] == 0 ){

$adwordsInternalClass = new \App\Libraries\AdwordsInternalClasses\AdwordsClass;
$adwordsConfig = $adwordsInternalClass->adwordsConfig();

$adwordsScopes = "https://www.googleapis.com/auth/adwords";
$getAccountAccessUrl = "https://accounts.google.com/o/oauth2/auth?scope=".urldecode($adwordsScopes)."&response_type=code&access_type=offline&redirect_uri=".urlencode($adwordsConfig->redirect_uri)."&client_id=".$adwordsConfig->client_id;


echo "Please <a href='".$getAccountAccessUrl."'>Click Here</a> to link your Adwords account so that we can get started.";

echo "<br><br>To skip linking your Adwords account and create a monitor for a different platform (Twitter, Facebook, Bing/Yahoo), <a href='/user/add-tracker/'>Click Here</a>";

}else{

?>

<? if(isset($_GET['get_adwords_data']) && $_GET['get_adwords_data']==1){ ?><div id="warning-getting-campaign-data">Please wait while we retrieve data from your Adwords account. Thank you!</div><? } ?>


<?

if($data['user_trackers'] > 0 && isset($trackers_data) && count($trackers_data)>0 ){

?>


<div class="chart-item-block">
<h2>TOP IP ADDRESSES</h2>
<div style="width:80%;">
<div class="chart-item-block">
<?
if(count($trackers_data["reports"]["ip_array"]) > 0){ ?>
<canvas id="myChartIP" width="700" height="400"></canvas>
<? }else{ echo "There is no data yet";} ?>
</div>
<div class="chart-item-block">
<h2>TOP Keywords</h2>
<?
if(count($trackers_data["reports"]["keyword_array"]) > 0){ ?>
<canvas id="myChartKeywords" width="700" height="400"></canvas>
<? }else{ echo "There is no data yet";} ?>
</div>
<div class="chart-item-block">
<h2>TOP Locations</h2>
<?
if(count($trackers_data["reports"]["location_array"]) > 0){ ?>
<canvas id="myChartCities" width="700" height="400"></canvas>
<? }else{ echo "There is no data yet";} ?>
</div>
<div class="chart-item-block">
<h2>TOP Countries</h2>
<?
if(count($trackers_data["reports"]["country_array"]) > 0){ ?>
<div style="width:50%;"><canvas id="myChartCountryReport" width="400" height="400"></canvas></div>
<? }else{ echo "There is no data yet";} ?>
</div>
</div>

<script>

$(document).ready(function(){



// /* -------------------- CHART JS GLOBAL ----------------------- */




Chart.defaults.global = {
   // Boolean - Whether to animate the chart
    animation: true,

    // Number - Number of animation steps
    animationSteps: 60,

    // String - Animation easing effect
    // Possible effects are:
    // [easeInOutQuart, linear, easeOutBounce, easeInBack, easeInOutQuad,
    //  easeOutQuart, easeOutQuad, easeInOutBounce, easeOutSine, easeInOutCubic,
    //  easeInExpo, easeInOutBack, easeInCirc, easeInOutElastic, easeOutBack,
    //  easeInQuad, easeInOutExpo, easeInQuart, easeOutQuint, easeInOutCirc,
    //  easeInSine, easeOutExpo, easeOutCirc, easeOutCubic, easeInQuint,
    //  easeInElastic, easeInOutSine, easeInOutQuint, easeInBounce,
    //  easeOutElastic, easeInCubic]
    animationEasing: "easeOutQuart",

    // Boolean - If we should show the scale at all
    showScale: true,

    // Boolean - If we want to override with a hard coded scale
    scaleOverride: false,

    // ** Required if scaleOverride is true **
    // Number - The number of steps in a hard coded scale
    scaleSteps: null,
    // Number - The value jump in the hard coded scale
    scaleStepWidth: null,
    // Number - The scale starting value
    scaleStartValue: null,

    // String - Colour of the scale line
    scaleLineColor: "rgba(0,0,0,.1)",

    // Number - Pixel width of the scale line
    scaleLineWidth: 1,

    // Boolean - Whether to show labels on the scale
    scaleShowLabels: true,

    // Interpolated JS string - can access value
    scaleLabel: "<%=value%>",

    // Boolean - Whether the scale should stick to integers, not floats even if drawing space is there
    scaleIntegersOnly: true,

    // Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero: false,

    // String - Scale label font declaration for the scale label
    scaleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Scale label font size in pixels
    scaleFontSize: 12,

    // String - Scale label font weight style
    scaleFontStyle: "normal",

    // String - Scale label font colour
    scaleFontColor: "#666",

    // Boolean - whether or not the chart should be responsive and resize when the browser does.
    responsive: true,

    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: true,

    // Boolean - Determines whether to draw tooltips on the canvas or not
    showTooltips: true,

    // Function - Determines whether to execute the customTooltips function instead of drawing the built in tooltips (See [Advanced - External Tooltips](#advanced-usage-custom-tooltips))
    customTooltips: false,

    // Array - Array of string names to attach tooltip events
    tooltipEvents: ["mousemove", "touchstart", "touchmove"],

    // String - Tooltip background colour
    tooltipFillColor: "rgba(0,0,0,0.8)",

    // String - Tooltip label font declaration for the scale label
    tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Tooltip label font size in pixels
    tooltipFontSize: 14,

    // String - Tooltip font weight style
    tooltipFontStyle: "normal",

    // String - Tooltip label font colour
    tooltipFontColor: "#fff",

    // String - Tooltip title font declaration for the scale label
    tooltipTitleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

    // Number - Tooltip title font size in pixels
    tooltipTitleFontSize: 14,

    // String - Tooltip title font weight style
    tooltipTitleFontStyle: "bold",

    // String - Tooltip title font colour
    tooltipTitleFontColor: "#fff",

    // Number - pixel width of padding around tooltip text
    tooltipYPadding: 6,

    // Number - pixel width of padding around tooltip text
    tooltipXPadding: 6,

    // Number - Size of the caret on the tooltip
    tooltipCaretSize: 8,

    // Number - Pixel radius of the tooltip border
    tooltipCornerRadius: 6,

    // Number - Pixel offset from point x to tooltip edge
    tooltipXOffset: 10,

    // String - Template string for single tooltips
    tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",

    // String - Template string for multiple tooltips
    multiTooltipTemplate: "<%= value %>",

    // Function - Will fire on animation progression.
    onAnimationProgress: function(){},

    // Function - Will fire on animation completion.
    onAnimationComplete: function(){}
};




// Chart.defaults.global.responsive = true;

var optionsIP = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero : true,

    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,

    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,

    //Boolean - If there is a stroke on each bar
    barShowStroke : true,

    //Number - Pixel width of the bar stroke
    barStrokeWidth : 1,

    //Number - Spacing between each of the X value sets
    barValueSpacing : 25,

    //Number - Spacing between data sets within X values
    barDatasetSpacing : 1,

scaleStartValue: 0,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

};






<?
if(count($trackers_data["reports"]["ip_array"]) > 0){
?>

// Chart 1 - Top IP
var dataIP = {
    labels: [
<?

$i=0;
foreach($trackers_data["reports"]["ip_array"] as $arrItem){
$i++;

echo "\"".$arrItem['sortParameter']."\"";
if($i < count($trackers_data["reports"]["ip_array"]) ){echo ",";}

}

?>
],
    datasets: [
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [
<?

$i=0;
foreach($trackers_data["reports"]["ip_array"] as $arrItem){
$i++;
echo "\"".$arrItem['data']."\"";
if($i < count($trackers_data["reports"]["ip_array"]) ){echo ",";}

}

?>

]
        }
    ]
};

var ctxIP = document.getElementById("myChartIP").getContext("2d");
var myChartIP = new Chart(ctxIP).Bar(dataIP, optionsIP);

<?
}
?>

<?
if(count($trackers_data["reports"]["keyword_array"]) > 0){ 
?>
// Chart 2 - Top Keywords
var dataKeywords = {
    labels: [
<?

$i=0;
foreach($trackers_data["reports"]["keyword_array"] as $arrItem){
$i++;
echo "\"".$arrItem['sortParameter']."\"";
if($i < count($trackers_data["reports"]["keyword_array"]) ){echo ",";}

}

?>
],
    datasets: [
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [
<?

$i=0;
foreach($trackers_data["reports"]["keyword_array"] as $arrItem){
$i++;
echo "\"".$arrItem['data']."\"";
if($i < count($trackers_data["reports"]["keyword_array"]) ){echo ",";}

}

?>

]
        }
    ]
};






var ctxKeywords = document.getElementById("myChartKeywords").getContext("2d");
var myChartKeywords = new Chart(ctxKeywords).Bar(dataKeywords, optionsIP);

<?
}
?>


<?
if(count($trackers_data["reports"]["location_array"]) > 0){ 
?>
// Chart 3 - Top Cities
var dataCities = {
    labels: [
<?

$i=0;
foreach($trackers_data["reports"]["location_array"] as $arrItem){
$i++;
echo "\"".$arrItem['sortParameter']."\"";
if($i < count($trackers_data["reports"]["location_array"]) ){echo ",";}

}

?>
],
    datasets: [
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [
<?

$i=0;
foreach($trackers_data["reports"]["location_array"] as $arrItem){
$i++;
echo "\"".$arrItem['data']."\"";
if($i < count($trackers_data["reports"]["location_array"]) ){echo ",";}

}

?>

]
        }
    ]
};





var ctxCities = document.getElementById("myChartCities").getContext("2d");
var myChartCities = new Chart(ctxCities).Bar(dataCities, optionsIP);

<?
}
?>


<?
if(count($trackers_data["reports"]["country_array"]) > 0){ 
?>
// Chart #4 Country Report
var dataCountryReport = [
   
<?

$i=0;
foreach($trackers_data["reports"]["country_array"] as $arrItem){
$i++;

if($i%5==0){
$color = "#F7464A";
$highlight = "#FF5A5E";
}
elseif($i%4==0){
$color = "#46BFBD";
$highlight = "#5AD3D1";
}
elseif($i%3==0){
$color = "#FDB45C";
$highlight = "#FFC870";
}
elseif($i%2==0){
$color = "#B48EAD";
$highlight = "#C69CBE";
}else{
$color = "rgba(151,187,205,1)";
$highlight = "rgba(151,187,205,0.75)";
}

echo '{
        value: '.$arrItem['data'].',
        color:"'.$color.'",
        highlight: "'.$highlight.'",
        label: "'.$arrItem['sortParameter'].'"
    }
';

if($i < count($trackers_data["reports"]["country_array"]) ){echo ",";}

}

?>
];


var optionsCountryReport = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke : true,

    //String - The colour of each segment stroke
    segmentStrokeColor : "#fff",

    //Number - The width of each segment stroke
    segmentStrokeWidth : 2,

    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout : 50, // This is 0 for Pie charts

    //Number - Amount of animation steps
    animationSteps : 100,

    //String - Animation easing effect
    animationEasing : "easeOutBounce",

    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate : true,

    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale : false,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

};


var ctxCountryReport = document.getElementById("myChartCountryReport").getContext("2d");
// For a pie chart
var myChartCountryReport = new Chart(ctxCountryReport).Pie(dataCountryReport,optionsCountryReport);

// And for a doughnut chart
//var myDoughnutChart = new Chart(ctx[1]).Doughnut(dataCountryChart,optionsCountryChart);

<?
}
?>











})






</script>

<script src="/js/сhart_js/Chart.js"></script>


<?

}else{

echo "Please create or enable a Monitor to begin detecting click fraud and collecting data.";

} ?>

<? } ?>

</div>
</div>
<div class="clear"></div>

<script>

$(document).ready(function(){

var getAdwordsData = <? if(isset($_GET['get_adwords_data']) && $_GET['get_adwords_data']==1){echo 1;}else{echo 0;} ?>

if(getAdwordsData == 1){

$.ajax({
type: "GET",
url: "/user/adwords-campaigns/",
data: {
"id": 0
},
cache: false,
success: function(response){
$("#warning-getting-campaign-data").hide();
document.location.href = "/user/add-tracker/?create_tracker_message=1";
}
});

}

});

</script>
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


<? } ?>


@stop