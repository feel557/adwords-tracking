@extends('layout_admin')

@section('title')
Messages
@stop

@section('content')

@include('admin/top_menu')

<div class="container">
<h1>Messages</h1>
<div class="content-zone">
<h2>New Message</h2>

<div style="width:720px;margin:0 auto;">
<form action='/admin/add-message/' method='post'>
<?

if($data["type"] == 3){

echo "<input type='text' name='theme' value='".$data["userData"][0]->email."' readonly>";

}

if($data["type"] == 2){

echo "Send to all users";

}

if($data["type"] == 1){

echo "<div style='padding:10px 0;'>Selected users:</div><div style='height:120px;border:1px solid #ddd;width:700px;padding:10px;background:#fff;overflow-y:scroll;'><ul>";

foreach($data["usersArray"] as $userItem){

echo "<li>".$userItem[0]->email."</li>";

}

echo "</ul></div>";

}

?>

<br><br>
<input type='text' name='theme' style="width:700px;" placeholder=" Subject *">
<br><br>
<textarea id='mess_textarea' name='text' style='border:1px solid #ddd;width:700px;height:160px;'></textarea>
<br><br>
<? if($data["type"] == 3){ ?>
<input type='hidden' name='id' value='<? echo $data["userData"][0]->id; ?>'>
<? } ?>
<? if($data["type"] == 1){

echo "<input type='hidden' name='usersJson' value='".$data["usersJson"]."'>";

}

?>
<input type='hidden' name='type' value="<?= $data["type"] ?>">
<input type='submit' value="Send Message" style="float:right;">
<div class="clear"></div>
</form>
</div>
</div>
</div>


@stop