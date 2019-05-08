@extends('layout_admin')

@section('title')
Users
@stop

@section('content')

@include('admin/top_menu')

<div class="container">
<h1>Users</h1>
<div class="content-zone">

						
@if (Session::has('message'))
<div class="warning-message">
<p>{{ Session::get('message') }}</p>
</div>
@endif


<form action="/admin/users/" method="get">
<div style="padding-bottom:20px;">
<div style="float:left;">

<b>Search:</b> <input type="text" name="value" value="<? if(isset($_GET['value'])){ echo $_GET['value']; } ?>">
<select name="value_type">
<option value="1" <? if(isset($_GET['value_type']) && $_GET['value_type'] == 1){echo "selected";} ?>>Email</option>
<option value="2" <? if(isset($_GET['value_type']) && $_GET['value_type'] == 2){echo "selected";} ?>>User name</option>
</select>
</div>

<div style="float:left;padding-left:20px;">
<b>Sort:</b> 
<select name="sort">
<option value="1" <? if(isset($_GET['sort']) && $_GET['sort'] == 1){echo "selected";} ?>>ID</option>
<?/*<option value="2" <? if(isset($_GET['sort']) && $_GET['sort'] == 2){echo "selected";} ?>>Trial period</option>*/?>
<option value="3" <? if(isset($_GET['sort']) && $_GET['sort'] == 3){echo "selected";} ?>>User name</option>
</select>
<input type="submit" value="OK">

</div>

<div style="float:left;padding:10px 0 20px 20px;"><a href="/admin/users/">Clear search</a></div>
<div class="clear"></div>
</div>
</form>


<?/*
<form action="/admin/users/" method="get">
<div style="padding-bottom:20px;">
<div style="float:left;">

<b>Special search:</b>
<select name="special_search">
<option value="1" <? if(isset($_GET['value_type']) && $_GET['value_type'] == 1){echo "selected";} ?>>Users use more 5 domains last 30 days</option>
<!-- <option value="2" <? if(isset($_GET['value_type']) && $_GET['value_type'] == 2){echo "selected";} ?>>User name</option> -->
</select>
</div>

<div style="float:left;padding-left:20px;">
<input type="submit" value="OK">
</div>

<div style="float:left;padding:10px 0 20px 20px;"><a href="/admin/users/">Clear search</a></div>
<div class="clear"></div>
</div>
</form>
*/?>

<div class="messages-menu" style="padding:10px 0 20px;">
<div style="padding:0px 0 20px;">
<a href="javascript:void(0);" onclick="sendToAll();">Send Message To All Users</a> &nbsp; | &nbsp; 
<a href="javascript:void(0);" onclick="sendToSelected();">Send Message To Selected Users</a>
</div><div>
<a href="javascript:void(0);" onclick="selectAll();">Select all</a> &nbsp; | &nbsp; 
<a href="javascript:void(0);" onclick="deselectAll();">Unselect all</a>
</div>
</div>


<div style="float:right;">
<a href="/admin/export-all-users/">Export users (CSV)</a>
</div>
<div class="clear"></div>

<?

foreach($users_array as $user){

// created ava login email active

if($user->ava!=''){
$ava = $user->ava;
}else{
$ava = "/img/profile-img.jpg";
}

if($user->is_active==1){
$block = "<a href='/admin/block-user/?id=".$user->id."'>Block</a>";
}else{
$block = "<a href='/admin/unblock-user/?id=".$user->id."'>Unblock</a>";
}


echo "<div class='post-block'><table class='table-forum'><tr>
<td style='width:100px;'><input type='checkbox' class='user-list-checkbox' user-id='".$user->id."'></td>
<td style='width:100px;'><a href='/admin/user-detail/?id=".$user->id."'><img src='".$ava."' class='asa'></a></td>
<td style='width:300px;'><a href='/admin/user-detail/?id=".$user->id."'><b>".$user->first_name." ".$user->last_name."</b></a></td>
<td style='width:300px;'>".$user->email."</td>
<td style='width:200px;'><a href='/admin/add-message/?add_message_type=3&id=".$user->id."'><b>+ New message</b></a></td>
<td style='width:100px;'>".$block."</td>
<td style='width:100px;'><a href='javascript:void(0);' onclick='deleteUser(".$user->id.");' href2='/admin/delete-user/?id=".$user->id."'>Delete</a></td>
</tr></table></div>";

}


?>

<? echo $users_array->render(); ?>

</div>

</div>


<div style="display:none;">
<form action="/admin/add-message/" id="add_message_form" method="get">
<input type="hidden" name="users_array_json">
<input type="hidden" name="add_message_type">
<input type="submit">
</form>
</div>

<script>

$(document).ready(function(){

	setTimeout(function(){

		$(".warning-message").hide();

	}, 2000);

})

function selectAll(){
$("input[class='user-list-checkbox']").prop('checked', true);
}
function deselectAll(){
$("input[class='user-list-checkbox']").prop('checked', false);
}


function sendToSelected(){

var selectedUsersArray = [];
$("input[class='user-list-checkbox']:checked").each(function(){
	selectedUsersArray.push( $(this).attr('user-id') );
});

console.log(selectedUsersArray);
var myJsonString = JSON.stringify(selectedUsersArray);


$("input[name='users_array_json']").val(myJsonString);
$("input[name='add_message_type']").val(1);

setTimeout(function(){
	$("#add_message_form").submit();
},500);



}


function sendToAll(){

$("input[name='add_message_type']").val(2);

setTimeout(function(){
	$("#add_message_form").submit();
},500);

}






function deleteUser(id){
var truthBeTold = window.confirm("Are you sure want to delete?");
	if (truthBeTold) {
		document.location.href = "/admin/delete-user/?id="+id;
	}
}

</script>



<div class="clear"></div>
@stop