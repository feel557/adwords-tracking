@extends('layout_admin')

@section('title')
Settings
@stop

@section('content')

@include('admin/top_menu')

<div class="container">

<h1>Settings</h1>
<div class="content-zone">

<div class="form-block">
<form class='myYandexForm' action="/admin/map-settings-update" method="post">
<ul class="form-list">
<li>
<div class="form-left-td">Adwords developerToken</div>
<div class="form-right-td"><input type='text' name='developerToken' value='<? echo $settings_array[0]->developerToken; ?>' readonly>
</div>
<div class="clear"></div>
</li>
<li>
<div class="form-left-td">userAgent</div>
<div class="form-right-td">
<input type='text' name='userAgent' value='<? echo $settings_array[0]->userAgent; ?>'  readonly>
</div>
<div class="clear"></div>
</li>
<li>
<div class="form-left-td">clientCustomerId</div>
<div class="form-right-td">
<input type='text' name='clientCustomerId' value='<? echo $settings_array[0]->managerClientCustomerId; ?>'  readonly>
</div>
<div class="clear"></div>
</li>
<li>
<div class="form-left-td">client_id</div>
<div class="form-right-td">
<input type='text' name='client_id' value='<? echo $settings_array[0]->client_id; ?>'  readonly>
</div>
<div class="clear"></div>
</li>
<li>
<div class="form-left-td">client_secret</div>
<div class="form-right-td">
<input type='text' name='client_secret' value='<? echo $settings_array[0]->client_secret; ?>'  readonly>
</div>
<div class="clear"></div>
</li>
<li>
<div class="form-left-td">refresh_token</div>
<div class="form-right-td">
<input type='text' name='refresh_token' value='<? echo $settings_array[0]->managerRefreshToken; ?>'  readonly>
</div>
<div class="clear"></div>
</li>
<li>
<div class="form-left-td">redirect_uri</div>
<div class="form-right-td">
<input type='text' name='redirect_uri' value='<? echo $settings_array[0]->redirect_uri; ?>'  readonly>
</div>
<div class="clear"></div>
</li>


<li>
<div style="padding:20px;text-align:center;">
<input type="hidden" name="requestType" value="1">
<input type="submit" value="OK">
</div>
</li>
</ul>
</form>
</div>






</div>
</div>

@stop