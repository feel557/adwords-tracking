@extends('layout_admin')

@section('title')
IP Address Details
@stop

@section('content')

@include('customer/top_menu')

<div class="container">

<h1>IP Address Details</h1>



<div class="content-zone" style="display:block;background:#fff;">
<h2>Lookup IP Address</h2>
<div style="padding:0px 0;">
<form action="/user/report-ip-detail/" method="get">
<div style="float:left;padding:0 10px;"> IP <input type="text" name="ip" placeholder=" IP *">  </div>
<div style="float:left;padding:3px 10px;"> <input type="submit" class="button-common" value="Lookup IP"> </div>
<div class="clear"></div>
</form>
</div>


</div>

<div class="content-zone">
<? if(isset($data[0])){ ?>
<script>

<? $json = json_decode($data[0]->ip_location); ?>

function initMap() {

var myLatLng = {lat: <? echo $json->location->latitude; ?>, lng: <? echo $json->location->longitude; ?>};

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 7,
    center: myLatLng
  });

  var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    title: 'IP Location'
  });


}


</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbtYNByEKlayCPjzf0orR4ifQyXdOO11w&callback=initMap"></script>



<div>
<div style="350px;float:left;">


<h2>Details for IP address</h2>

<table class="reports-activity-summary" style="width:300px;;">

<tr><td class="td-left first-td">IP</td><td class="first-td"><? echo $json->location->ipAddress; ?></td></tr>
<tr><td class="td-left">City</td><td><? echo $json->location->cityName; ?></td></tr>
<tr><td class="td-left first-td">State</td><td class="first-td"><? echo $json->location->regionName; ?></td></tr>
<tr><td class="td-left">Country</td><td><? echo $json->location->countryName; ?></td></tr>
<tr><td class="td-left first-td">Zip</td><td class="first-td"><? echo $json->location->zipCode; ?></td></tr>
<tr><td class="td-left">Longitude</td><td><? echo $json->location->longitude; ?></td></tr>
<tr><td class="td-left first-td">Latitude</td><td class="first-td"><? echo $json->location->latitude; ?></td></tr>
<tr><td class="td-left">Total clicks</td><td><? echo count($data); ?></td></tr>
</table>




</div>
<div style="550px;float:left;">
<div id="map" style="width:550px;height:400px;margin-left:30px;"></div>
</div>
<div class="clear"></div>
</div>

<? }else{ echo "No info about this IP Address"; } ?>

</div>
</div>






@stop