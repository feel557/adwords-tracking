@extends('layout_admin')

@section('title')
Messages
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>Messages</h1>




<td style="width:430px;" class="right">
<?


if(!empty(Input::get('out'))){$get_out = Input::get('out');}else{$get_out = 0;}
if($get_out == 0){echo "<a href='/user/messages/?out=0'><b>Inbox (".count($data).")</b></a>";}else{echo "<a href='/user/messages/?out=0'>Inbox</a>";}
echo " &nbsp; | &nbsp; ";
if($get_out == 1){echo "<a href='/user/messages/?out=1'><b>Outbox (".count($data).")</b></a>";}else{echo "<a href='/user/messages/?out=1'>Outbox</a>";}
echo " &nbsp; | &nbsp; ";
/*
if($get_out == 2){echo "<a href='/user/messages/?out=2'><b>Корзина (".count($data).")</b></a>";}else{echo "<a href='/user/messages/?out=2'>Корзина</a>";}
echo " &nbsp; | &nbsp; ";
if($get_out == 3){echo "<a href='/user/messages/?out=3'><b>Спам (".count($data).")</b></a>";}else{echo "<a href='/user/messages/?out=3'>Спам</a>";}
*/

?>
</td>

<table class='table-100'>

<tr>
<td>
<div class="messages-menu" style='padding:5px 0;margin:10px 0;border-bottom:1px solid #eee;'>
<a href="javascript:void(0);" id="delete_mess">Delete</a> &nbsp; | &nbsp; 
<a href="javascript:void(0);" id="select_all">Select all</a> &nbsp; | &nbsp; 
<a href="javascript:void(0);" id="deselect_all">Unselect all</a>



</div>
</td>
</tr>





<tr>
<td>

<?


if(empty($_GET['id']))
{
if(count($data) < 1){echo "<div style='padding:20px;text-align:center;'>Empty</div>";}
else{
echo "<br>";

foreach($data as $messageItem){

if($messageItem->view == 1){$class = "viewed";}else{$class = "";}


echo "
<div id='mess_".$messageItem->id."' class='message_div ".$class."'>
<table class='table-100'>
<tr>
<td style='width:40px;'>
&nbsp; <input type='checkbox' name='mess_id' value='".$messageItem->id."'>
</td>
<td>
<a href='/user/messages/?id=".$messageItem->id."'>
<div>
<table class='table-100'><tr>
<td style='width:150px;'><div style='overflow:hidden;width:150px;height:20px;'>Administration</div></td>
<td style='width:360px;'><div style='overflow:hidden;width:360px;height:20px;'>".$messageItem->theme."</div></td>
<td style='width:190px;'>".date("d F Y, H:i",strtotime($messageItem->date))."
</td>
</tr></table>
</div>
</a>
</td>
</tr>
</table>
</div>
";

}

}}
else
{
//single message



echo "
<div style='margin:20px 0;border-bottom:1px dotted #ccc;'>
<div style='padding:15px 0;font-size:14pt;font-weight:normal;'>".$data[0]->theme."</div>
<table class='table-100'>
<tr>
<td>
".$data[0]->user_to."
</td>
<td class='right'>".date("d F Y, H:i",strtotime($data[0]->date))."</td>
</tr>
<tr>
<td colspan='2' style='padding:10px 0;'><div style='border-bottom:1px dotted #ccc;'></div>
<br><br>
".$data[0]->text."
</td>
</tr>
</table>
</div>
";

echo "
<div style='padding:15px;background:#eee;border:1px solid #ccc;border-radius:6px;'>
<div style='padding-bottom:10px;font-size:11pt;font-weight:bold;'> New Message: </div>
<form action='add_mess_enj.php' method='post'>
<textarea id='mess_textarea' name='text' style='border:1px solid #ccc;width:706px;height:140px;'></textarea>
<br><br>
<input type='hidden' name='to' value='".$data[0]->user_from."'>
<input type='hidden' name='theme' value='".$data[0]->theme."'>
<input type='hidden' name='type' value='1'>
<div style='margin:0 auto;text-align:right;'> <input type='submit' class='search-button' value='OK'> </div>
<a href='javascript:void(0);' onclick='fullTextarea();'>Enlarge +</a>
 &nbsp; &nbsp;
<a href='javascript:void(0);' onclick='smTextarea();'>Small size -</a>
</form>
</div>
";



}

?>

</td>
</tr>






</table>


















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