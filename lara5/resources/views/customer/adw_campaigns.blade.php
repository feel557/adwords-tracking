@extends('layout_admin')

@section('title')
Adwords campaigns
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>Adwords campaigns</h1>

<div style="padding:20px;font-weight:bold;">tracking url example with parameters (required) : http://example.com/?season={_season}&promocode={_promocode}&u={lpurl}</div>

<table class='tab-list' style='width:100%;'>
<tr class='tab-header'><td>ID</td><td>Name</td><td>Block IP</td><td>Tracking URL</td><td>#</td></tr>
<?


foreach($adwordsArray as $campaign){

echo "<tr>
<td>".$campaign->adwords_campaign_id."</td>
<td>".$campaign->campaign_name."</td>
<td style='width:200px;'>
<input name='block-ip' placeholder=' IP address' campaign_id='".$campaign->adwords_campaign_id."' style='width:120px;'>
<a href='javascript:void(0);' class='button-common set-block-ip' campaign_id='".$campaign->adwords_campaign_id."'>OK</a>
</td>
<td style='width:200px;'>
<input name='track-url' placeholder=' Tracking URL' campaign_id='".$campaign->adwords_campaign_id."' style='width:120px;'>
<a href='javascript:void(0);' class='button-common set-track-url' campaign_id='".$campaign->adwords_campaign_id."'>OK</a>
</td>
<td style='width:50px;'><a href='#'>#</a></td>
</tr>";

}

?>
</table>

<? //echo $adwordsArray->appends(['id' => Input::get('id')])->render(); ?>

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