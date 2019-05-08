@extends('layout_admin')

@section('title')
Messages
@stop

@section('content')

@include('admin/top_menu')

<div class="container">
<h1>Messages</h1>
<div class="content-zone">

<td style="width:430px;" class="right">

<?


if(!empty(Input::get('out'))){$get_out = Input::get('out');}else{$get_out = 0;}
if($get_out == 0){echo "<a href='/admin/messages/?out=0'><b>Inbox (".count($data).")</b></a>";}else{echo "<a href='/admin/messages/?out=0'>Inbox</a>";}
echo " &nbsp; | &nbsp; ";
if($get_out == 1){echo "<a href='/admin/messages/?out=1'><b>Outbox (".count($data).")</b></a>";}else{echo "<a href='/admin/messages/?out=1'>Outbox</a>";}
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
<a href='/admin/messages/?id=".$messageItem->id."'>
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


function AjaxDelMess(){

var valCh = '';
$('input:checkbox:checked').each(function(){
valCh = valCh + ',' + $(this).attr('value');
});

$.ajax({
type: "GET",
url: "/admin/delete-messages/",
data: {"string": valCh},
cache: false,
timeout: 10000,
error: function(){
alert("Too long a the retrieval. Try refreshing the page");
},
success: function(responce){
if(responce == "1"){
var idCheck = valCh.split(',');
jQuery.each(idCheck, function() {
if(this != '')
{
$("#mess_"+this).hide();
$("#mess_"+this).empty();
}

})

}
}
});
}



$(document).ready(function(){

$("#delete_mess").click(function(){
AjaxDelMess();
})

})


</script>



@stop