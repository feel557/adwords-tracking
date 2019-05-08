@extends('layout_admin')

@section('title')
<? echo $title; ?>
@stop

@section('content')

@include('customer/top_menu')
<? if(isset($_GET['tracker_id']) && $_GET['tracker_id'] != 0){$tracker_id_url = $_GET['tracker_id'];$title_ext = " for monitor #".$tracker_id_url;}else{$tracker_id_url = 0;$title_ext = " for all monitors";}?>
<div class="container">
<h1><? echo $title.$title_ext; ?></h1>


<div class="content-zone" style="display:block;background:#fff;">


<div class="header-inner-block" style="padding:0 0 10px;font-weight:bold;">Date Range:</div>
<form method="get" action="/user/report-detail/">
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
	<option value="2" <? if(isset($_GET['range_type']) && $_GET['range_type'] == 2){ echo "selected";} ?>>Custom</option>
	
</select>
</div>
<div class="date-range-dates-block" style="float:left;padding:0 10px;display:none;"> From: <input class="datetimepicker" style="width:100px;" name="date1" id="datetimepicker1" type="text" placeholder=" From date *" value="<? if( isset($_GET['date1']) ){ echo $_GET['date1']; } ?>">  </div>
<div class="date-range-dates-block" style="float:left;padding:0 10px;display:none;"> To: <input class="datetimepicker" style="width:100px;" name="date2" id="datetimepicker2" type="text" placeholder=" To date *" value="<? if( isset($_GET['date2']) ){ echo $_GET['date2']; } ?>"> </div>
<div style="float:left;padding:3px 10px;"> <input type="submit" class="button-common" value="Change Dates"></div>
<div class="clear"></div>
<input type="hidden" name="tracker_id" value="<? if( isset($_GET['tracker_id']) ){ echo $_GET['tracker_id']; }else{ echo 0; }?>">
<input type="hidden" name="report_type" value="<? if( isset($_GET['report_type']) ){ echo $_GET['report_type']; }else{ echo 1; }?>">

</form>








<div class="clear"></div>
</div>



<div class="content-zone" style="display:block;background:#fff;">
<h2>View Reports</h2>

<ul class="list-of-reports">
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? echo $tracker_id_url; ?>&amp;report_type=1&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Top Visitors by IP address</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? echo $tracker_id_url; ?>&amp;report_type=3&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Keyword Activity Overview</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? echo $tracker_id_url; ?>&amp;report_type=2&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Visitor Locations</a></li>
<li><a class="reports-links" href="javascript:void(0);" href2="/user/report-detail/?tracker_id=<? echo $tracker_id_url; ?>&amp;report_type=4&amp;range_type=<? if( isset($_GET['range_type']) ){ echo $_GET['range_type']; }else{ echo 1; }?>">Top Visitors by Cookie ID</a></li>
</ul>
<div class="clear"></div>
</div>


<div class="content-zone">

<?

if(count($data) < 1){
echo "<div style='padding:20px;'>No data found for selected period</div>";
}

?>

<script>


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




// CHARTS


var data = {
    labels: [
<?

$i=0;
foreach($data as $arrItem){
$i++;
echo "\"".$arrItem['sortParameter']."\"";
if($i < count($data) ){echo ",";}

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
foreach($data as $arrItem){
$i++;
echo "\"".count($arrItem['data'])."\"";
if($i < count($data) ){echo ",";}

}

?>

]
        }
    ]
};









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
    responsive: false,

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

var options = {
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
    barStrokeWidth : 2,

    //Number - Spacing between each of the X value sets
    barValueSpacing : 25,

    //Number - Spacing between data sets within X values
    barDatasetSpacing : 1,

scaleStartValue: 0,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

};

var ctx = document.getElementById("myChart").getContext("2d");
//var myNewChart = new Chart(ctx).PolarArea(data);
var myBarChart = new Chart(ctx).Bar(data, options);





})
</script>
<link rel="stylesheet" type="text/css" href="/js/jquery.datetimepicker.css"/ >
<script src="/js/jquery.datetimepicker.js"></script>




<script src="/js/сhart_js/Chart.js"></script>
<canvas id="myChart" width="700" height="400"></canvas>

</div>
</div>



@stop