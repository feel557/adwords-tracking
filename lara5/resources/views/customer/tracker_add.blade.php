@extends('layout_admin')

@section('title')
Create New Monitor
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>Create New Monitor</h1>

<div class="content-zone">

<? if(isset($_GET['create_tracker_message']) && $_GET['create_tracker_message'] == 1){ ?>
<div id="create-tracker-message" class="warning-message">Please Create a Monitor</div>
<? } ?>

<div style="width:780px;margin:0 auto;">
<form action="<? if( isset($data[0]->form_url) ){ echo $data[0]->form_url; } ?>" method="post" class="form-elements-block" autocomplete="off">
<input type="hidden" name="tracker-id" value="<? if( isset($data[0]->id) ){ echo $data[0]->id; } ?>">

<fieldset style="background:#dce7ec;">
<div class="form-block-title">Monitor Settings</div>

<div style="float:left;">
<div class="form-field-title">Monitor Name</div>
<input type="text" name="tracker-name" placeholder=" Monitor name" style="width:280px;" value="<? if( isset($data[0]->name) ){ echo $data[0]->name; } ?>">
</div>
<div style="float:right;">
<div class="form-field-title">Monitor Level</div>
<select name="tracker-level" style="width:280px;">
<option value="2" <? if( isset($data[0]->tracking_level) && $data[0]->tracking_level == 2){ echo "selected"; } ?>>Campaign</option>
<!--<option value="3" <? if( isset($data[0]->tracking_level) && $data[0]->tracking_level == 3){ echo "selected"; } ?>>Keyword</option>-->
<!--<option value="4" <? if( isset($data[0]->tracking_level) && $data[0]->tracking_level == 4){ echo "selected"; } ?>>Ad</option>-->
<option value="1" <? if( isset($data[0]->tracking_level) && $data[0]->tracking_level == 1){ echo "selected"; } ?>>Account</option>
</select>
</div>

<div class="clear"></div>

<div class="campaigns-list-block" style="padding:10px 0;">
<div class="form-field-title">Choose a Google Adwords Campaign to monitor:</div>
<div class="info-tips-block">
Selection of a Campaign is not necessary if you are monitoring at the Account level or if this Monitor is not for Google Adwords
</div>
<div style="height:120px;overflow-y:scroll;padding:10px 0;margin:10px 0;background:#fff;">
<table class="campaign-lines">
<?php

$customerClass = new App\Http\Controllers\Customer\CustomerController;
$userAwordsCampaignsArray = $customerClass->getUserAwordsCampaigns();
foreach($userAwordsCampaignsArray as $adwordsCampaign){
if( isset($data[0]->tracking_item) && $data[0]->tracking_item != 0 && $data[0]->tracking_item == $adwordsCampaign->adwords_campaign_id){$additional_class = "selected";}else{$additional_class = "";}
echo "<tr class='campaign-id-line ".$additional_class."'><td class='campaign-id'>".$adwordsCampaign->adwords_campaign_id."</td><td class='campaign-name'>".$adwordsCampaign->campaign_name."</td></tr>";
}


?>
</table>
</div>
<div style="padding:20px 0;font-weight:bold;" class="selected-campaign-name">Selected Campaign: <? if( isset($data[0]->tracking_item) && $data[0]->tracking_item != 0){ echo $data[0]->selected_campaign_name; } ?></div>
<input name="selected-campaign" type="hidden" value="<? if( isset($data[0]->tracking_item) && $data[0]->tracking_item != 0){ echo $data[0]->tracking_item; } ?>">
</div>

<div class="info-tips-block">
A landing page URL is not needed for Google Adwords, but is needed for Facebook, Twitter, and Bing/Yahoo. You may create separate trackers for each search engine if desired or use the same tracker for all of them.
</div>
<input type="text" name="final_url" style="width:680px;" placeholder=" Landing Page URL" value="<? if( isset($data[0]->landing_page) ){ echo $data[0]->landing_page; } ?>" autocomplete="off">

<div class="clear" style="margin-bottom:20px;"></div>
<div style="float:left;">
<div class="form-field-title">Email #1 to receive alerts</div>
<input type="text" style="width:280px;" name="email-1" placeholder=" Email #1" value="<? if( isset($data[0]->email_1_notification) ){ echo $data[0]->email_1_notification; } ?>">
</div>
<div style="float:right;">
<div class="form-field-title">Email #2 to receive alerts (Optional)</div>
<input type="text" style="width:280px;" name="email-2" placeholder=" Email #2" value="<? if( isset($data[0]->email_2_notification) ){ echo $data[0]->email_2_notification; } ?>">
</div>
<div class="clear"></div>
</fieldset>


<div class="info-tips-block">
The Alert settings below are recommended for most users, but feel free to change the settings as desired.
</div>

<div style="float:left;">
<fieldset style="height:630px;background:#a1e0a2;border-color:#7caf7c;">
<div class="form-block-title">Alert Level #1 Settings</div>
<div class="form-field-title">Number of clicks</div>
<input type="text" style="width:180px;" name="alert-1-clicks" placeholder=" Number of clicks" value="<? if( isset($data[0]->rules_array_1[0]->number_of_clicks) ){ echo $data[0]->rules_array_1[0]->number_of_clicks; } ?>">
<div class="form-field-title">Over what amount of time</div>

<select name="alert-1-time-period">
<option value="300" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 300){ echo "selected"; } ?>>5 mins</option>
<option value="600" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 600){ echo "selected"; } ?>>10 mins</option>
<option value="1200" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 1200){ echo "selected"; } ?>>20 mins</option>
<option value="1800" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 1800){ echo "selected"; } ?>>30 mins</option>
<option value="3600" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 3600){ echo "selected"; } ?>>1 hour</option>
<option value="7200" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 7200){ echo "selected"; } ?>>2 hours</option>
<option value="10800" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 10800){ echo "selected"; } ?>>3 hours</option>
<option value="18000" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 18000){ echo "selected"; } ?>>5 hours</option>
<option value="28800" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 28800){ echo "selected"; } ?>>8 hours</option>
<option value="43200" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 43200){ echo "selected"; } ?>>12 hours</option>
<option value="64800" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 64800){ echo "selected"; } ?>>18 hours</option>
<option value="86400" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 86400){ echo "selected"; } ?>>1 day</option>
<option value="172800" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 172800){ echo "selected"; } ?>>2 day</option>
<option value="259200" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 259200){ echo "selected"; } ?>>3 day</option>
<option value="345600" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 345600){ echo "selected"; } ?>>4 day</option>
<option value="432000" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 432000){ echo "selected"; } ?>>5 day</option>
<option value="864000" <? if( isset($data[0]->rules_array_1[0]->time_amount) && $data[0]->rules_array_1[0]->time_amount == 864000){ echo "selected"; } ?>>10 day</option>
</select>

<div class="form-field-title">Send Alert via Email?</div>
<select name="alert-1-is-send-email">
<option value="1" <? if( isset($data[0]->rules_array_1[0]->send_alert) && $data[0]->rules_array_1[0]->send_alert == 1){ echo "selected"; } ?>>Yes</option>
<option value="0" <? if( isset($data[0]->rules_array_1[0]->send_alert) && $data[0]->rules_array_1[0]->send_alert == 0){ echo "selected"; } ?>>No</option>
</select>
<div class="form-field-title">Add clicker's IP address to Adwords block list?</div>
<select name="alert-1-add-ip-block">
<option value="1" <? if( isset($data[0]->rules_array_1[0]->block_ip) && $data[0]->rules_array_1[0]->block_ip == 1){ echo "selected"; } ?>>Yes</option>
<option value="0" <? if( isset($data[0]->rules_array_1[0]->block_ip) && $data[0]->rules_array_1[0]->block_ip == 0){ echo "selected"; } ?>>No</option>
</select>
<div class="form-field-title">Show warning message?</div>
<select name="alert-1-show-warning">
<option value="1" <? if( isset($data[0]->rules_array_1[0]->show_message) && $data[0]->rules_array_1[0]->show_message == 1){ echo "selected"; } ?>>Yes</option>
<option value="0" <? if( isset($data[0]->rules_array_1[0]->show_message) && $data[0]->rules_array_1[0]->show_message == 0){ echo "selected"; } ?>>No</option>
</select>
<div class="form-field-title">Warning message text</div>
<textarea name="alert-1-warning-text" style="width:300px;height:100px;" placeholder=" Warning text message"><? if( isset($data[0]->rules_array_1[0]->alert_message) ){ echo $data[0]->rules_array_1[0]->alert_message; } ?></textarea>
<input type="hidden" name="alert-1-id" value="<? if( isset($data[0]->rules_array_1[0]->id) ){ echo $data[0]->rules_array_1[0]->id; } ?>">
</fieldset>
</div>
<div style="float:right;">
<fieldset style="height:630px;background:#e0a1a1;border-color:#af7c7c;">
<div class="form-block-title">Alert Level #2 Settings</div>
<div class="form-field-title">Use Alert #2 Level ?</div>
<select name="alert-2-act">
<option value="1" <? if( isset($data[0]->rules_array_2[0]->act) && $data[0]->rules_array_2[0]->act == 1){ echo "selected"; } ?>>Yes</option>
<option value="0" <? if( isset($data[0]->rules_array_2[0]->act) && $data[0]->rules_array_2[0]->act == 0){ echo "selected"; } ?>>No</option>
</select>
<div class="form-field-title">Number of clicks</div>
<input type="text" style="width:180px;" name="alert-2-clicks" placeholder=" Number of clicks" value="<? if( isset($data[0]->rules_array_2[0]->number_of_clicks) ){ echo $data[0]->rules_array_2[0]->number_of_clicks; } ?>">
<div class="form-field-title">Over what amount of time</div>
<select name="alert-2-time-period">
<option value="300" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 300){ echo "selected"; } ?>>5 mins</option>
<option value="600" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 600){ echo "selected"; } ?>>10 mins</option>
<option value="1200" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 1200){ echo "selected"; } ?>>20 mins</option>
<option value="1800" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 1800){ echo "selected"; } ?>>30 mins</option>
<option value="3600" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 3600){ echo "selected"; } ?>>1 hour</option>
<option value="7200" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 7200){ echo "selected"; } ?>>2 hours</option>
<option value="10800" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 10800){ echo "selected"; } ?>>3 hours</option>
<option value="18000" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 18000){ echo "selected"; } ?>>5 hours</option>
<option value="28800" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 28800){ echo "selected"; } ?>>8 hours</option>
<option value="43200" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 43200){ echo "selected"; } ?>>12 hours</option>
<option value="64800" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 64800){ echo "selected"; } ?>>18 hours</option>
<option value="86400" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 86400){ echo "selected"; } ?>>1 day</option>
<option value="172800" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 172800){ echo "selected"; } ?>>2 day</option>
<option value="259200" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 259200){ echo "selected"; } ?>>3 day</option>
<option value="345600" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 345600){ echo "selected"; } ?>>4 day</option>
<option value="432000" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 432000){ echo "selected"; } ?>>5 day</option>
<option value="864000" <? if( isset($data[0]->rules_array_2[0]->time_amount) && $data[0]->rules_array_2[0]->time_amount == 864000){ echo "selected"; } ?>>10 day</option>
</select>
<div class="form-field-title">Send Alert via Email?</div>
<select name="alert-2-is-send-email">
<option value="1" <? if( isset($data[0]->rules_array_2[0]->send_alert) && $data[0]->rules_array_2[0]->send_alert == 1){ echo "selected"; } ?>>Yes</option>
<option value="0" <? if( isset($data[0]->rules_array_2[0]->send_alert) && $data[0]->rules_array_2[0]->send_alert == 0){ echo "selected"; } ?>>No</option>
</select>
<div class="form-field-title">Add clicker's IP address to Adwords block list?</div>
<select name="alert-2-add-ip-block">
<option value="1" <? if( isset($data[0]->rules_array_2[0]->block_ip) && $data[0]->rules_array_2[0]->block_ip == 1){ echo "selected"; } ?>>Yes</option>
<option value="0" <? if( isset($data[0]->rules_array_2[0]->block_ip) && $data[0]->rules_array_2[0]->block_ip == 0){ echo "selected"; } ?>>No</option>
</select>
<div class="form-field-title">Show warning message?</div>
<select name="alert-2-show-warning">
<option value="1" <? if( isset($data[0]->rules_array_2[0]->show_message) && $data[0]->rules_array_2[0]->show_message == 1){ echo "selected"; } ?>>Yes</option>
<option value="0" <? if( isset($data[0]->rules_array_2[0]->show_message) && $data[0]->rules_array_2[0]->show_message == 0){ echo "selected"; } ?>>No</option>
</select>
<div class="form-field-title">Warning message text</div>
<textarea name="alert-2-warning-text" style="width:300px;height:100px;" placeholder=""><? if( isset($data[0]->rules_array_2[0]->alert_message) ){ echo $data[0]->rules_array_2[0]->alert_message; } ?></textarea>
<input type="hidden" name="alert-2-id" value="<? if( isset($data[0]->rules_array_2[0]->id) ){ echo $data[0]->rules_array_2[0]->id; } ?>">
</fieldset>
</div>
<div class="clear"></div>
<a href="/user/trackers/" class="button-common" style="float:left;background:#e0a1a1;border-color:#af7c7c;">Cancel</a>
<input type="submit" class="button-common" style="float:right;" value="Save">
<div class="clear"></div>
</form>
</div>
</div>

</div>
</div>











<script>

$(document).ready(function(){

$(".campaign-id-line").click(function(){

$(".campaign-id-line").css("background", "#fff");
$(this).css("background", "#eee");

var id = $(this).find("td.campaign-id").text();
var name = $(this).find("td.campaign-name").text();

$("input[name='selected-campaign']").val(id);
$(".selected-campaign-name").html("Selected: "+id+" - "+name);

})


$("select[name='tracker-level']").change(function(){

if($(this).val() == "2"){

$(".campaigns-list-block").show();

}else{

$(".campaigns-list-block").hide();

}


})


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
//$("#warning-getting-campaign-data").hide();
//document.location.href = "/user/add-tracker/?create_tracker_message=1";
}
});

}

});

</script>

@stop