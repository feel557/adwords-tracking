@extends('layout_admin')

@section('title')
Monitors
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>Monitors</h1>



<?

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


<div style="padding:0 20px 20px 20px;font-weight:bold;">
<a href="/user/add-tracker/" class="button-common">+ Create New Monitor</a>
</div>

<table class='tab-list' style='width:100%;'>
<tr class='tab-header'><td>ID</td><td>Name</td><td>Level</td><td>Final URL</td><td>Tracking URL</td><td>Reports</td><td>Status</td><td>Change status</td><td>Edit</td><td>Delete</td></tr>
<?

if(isset($adwordsArray) && count($adwordsArray) > 0 ){
foreach($adwordsArray as $item){

if($item->tracking_level == 1){$tracker_level = "<b>Account</b>";}
if($item->tracking_level == 2){$tracker_level = "Campaign";}

if($item->act == 0){$tracker_act = "Disabled";$tracker_act_set = 1;}
if($item->act == 1){$tracker_act = "Enabled";$tracker_act_set = 0;}

echo "<tr>
<td>".$item->id."</td>
<td>".$item->name."</td>
<td>".$tracker_level."</td>
<td>".$item->landing_page."</td>
<td><input type='text' id='tracker-url-".$item->id."' class='copyTarget' value='http://account.clickmonitor.co.uk/tracker/ad-redirect/?url={lpurl}&tracker_id=".$item->id."&kw={keyword}&nw={network}&pl={placement}&cmp={campaignid}' readonly><div style='padding:5px;font-size:10px;color:#555;'>Click on URL to copy</div></td>
<td><a href='/user/reports/?tracker_id=".$item->id."'>View Report</a></td>
<td>".$tracker_act."</td>
<td style='width:50px;'><a href='/user/update-act-tracker/?tracker_id=".$item->id."&act=".$tracker_act_set."'>Change status</a></td>
<td style='width:50px;'><a href='/user/edit-tracker/?id=".$item->id."'>Edit</a></td>
<td style='width:50px;'><a href='/user/delete-tracker/?tracker_id=".$item->id."'>Delete</a></td>
</tr>";

}
}

?>
</table>

<? //echo $adwordsArray->appends(['id' => Input::get('id')])->render(); ?>

</div>
</div>











<script>


//document.getElementsByClassName("copyButton").addEventListener("click", function() {
//    copyToClipboard(document.getElementsByClassName("copyTarget"));
//});

function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}




$(document).ready(function(){

$("body").on("click", ".copyTarget", function(){

var id = $(this).attr("id");
copyToClipboard(document.getElementById(id));
alert("URL was copied to clipboard");

});


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

<?}?>

@stop