@extends('layout_admin')

@section('title')
IP Whitelist
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>IP Whitelist</h1>

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

<div class="content-zone" style="display:block;background:#fff;">
<h2>Add IP Address to Whitelist</h2>
<form action="/user/add-whitelist-ip/" method="post">
<input type="text" name="ip" placeholder=" IP Address *">
<input type="submit" class="button-common" value="Add IP">
</form>
</div>


<table class='tab-list' style='width:100%;'>
<tr class='tab-header'>
<td>ID</td>
<td>IP Address</td>
<td>Delete</td></tr>
<?

if(isset($data) && count($data) > 0 ){
$i=0;
foreach($data as $item){
$i++;

echo "<tr>
<td>".$i."</td>
<td>".$item->ip."</td>
<td style='width:50px;'><a href='/user/delete-whitelist-ip/?id=".$item->id."'>Delete</a></td>
</tr>";

}
}

?>
</table>

<? //echo $adwordsArray->appends(['id' => Input::get('id')])->render(); ?>

</div>
</div>
<? } ?>
@stop