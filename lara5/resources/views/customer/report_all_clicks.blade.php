@extends('layout_admin')

@section('title')
Reports - All clicks
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>All clicks</h1>


<table class='tab-list' style='width:100%;'>
<tr class='tab-header'><td>Tracker ID</td><td>Date</td><td>Location</td><td>IP</td><td>Keyword</td><td>Campaign</td><td>Mobile Device</td></tr>
<?

if(isset($data) && count($data) > 0 ){
foreach($data as $item){
$locationString = "";
if(isset($item->ip_location) && $item->ip_location!=""){
	$locationJson = json_decode($item->ip_location);
	if( isset($locationJson->location->cityName) && isset($locationJson->location->regionName) && isset($locationJson->location->countryCode) && isset($locationJson->location->zipCode) ){
		$locationString = $locationJson->location->cityName . ", " . $locationJson->location->regionName . ", " . $locationJson->location->zipCode . ", " .  $locationJson->location->countryCode;
	}
}else{
    

}
$decodedJson = json_decode($item->adwords_input_data);

$decodedCampaign = "";
if(isset($decodedJson->campaign)){
$decodedCampaign = $decodedJson->campaign;
$campaignData = DB::table('_adwords_campaigns')->where('adwords_campaign_id', '=', $decodedJson->campaign)->get();
}

if($item->device_is_mobile == 0){$isMobile = "No";}
if($item->device_is_mobile == 1){$isMobile = "Mobile Device";}



echo "<tr>
<td>".$item->tracker_id."</td>
<td>".date("d/m/Y H:m:s", strtotime($item->click_date))."</td>
<td>".$locationString."</td>
<td>".$item->user_ip."</td>
<td>".(isset($decodedJson->keyword) ? $decodedJson->keyword : "")."</td>
<td>".(isset($campaignData[0]->name) ? $campaignData[0]->name : $decodedCampaign )."</td>
<td>".$isMobile."</td>
</tr>";

}
}

?>
</table>

<? echo $data->appends(['id' => Input::get('id')])->render(); ?>

</div>
</div>











<script>

$(document).ready(function(){

//Add IP to Black List in Adwords Account for particular campaign
$(".set-block-ip").click(function(){

var campaign_id = $(this).attr("campaign_id");
var ip = $("input[name='block-ip'][campaign_id='"+campaign_id+"']").val();

if(campaign_id!='' && ip!=''){

$.ajax({
type: "POST",
url: "/phpExternalClasses/setIpBlock.php",
data: {
"ip": ip,
"campaign_id":campaign_id
},
cache: false,
success: function(response){
if(response == 1){
alert("IP was added successfull");
}
}
});
}else{alert("Fill ip correct");}
});


//Add URL as Tracking URL in Adwords Account for particular campaign
$(".set-track-url").click(function(){

var campaign_id = $(this).attr("campaign_id");
var tracking_url = $("input[name='track-url'][campaign_id='"+campaign_id+"']").val();

if(campaign_id!='' && tracking_url!=''){
$.ajax({
type: "POST",
url: "/phpExternalClasses/setTrackingUrl.php",
data: {
"tracking_url": tracking_url,
"campaign_id":campaign_id
},
cache: false,
success: function(response){
if(response == 1){
alert("Tracking url was added successfull");
}
}
});
}else{alert("Fill url correct");}
});



})


</script>



@stop