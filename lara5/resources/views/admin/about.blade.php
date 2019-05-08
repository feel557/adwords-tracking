@extends('layout_admin')

@section('title')
Adwords reports
@stop

@section('content')

@include('admin/top_menu')

<div class="container">

<div style="padding:20px 0;">

<h1>About</h1>

<b>Server requirements:</b>
<br>
<ul>
<li>PHP > 5.4</li>
<li>SOAP php install</li>
<li>cURL</li>
</ul>
<br><br>
<b>Software:</b>
<br>
<ul>
<li>Laravel 5</li>
<li>php / mysql</li>
<li>Adwords MCC account</li>
<li>Adwords Live/Test/Client account</li>
<li>Google Console API: client id, secret, redirect url</li>
<li>Google Adwords API developer key</li>
</ul>

</div>
</div>

@stop