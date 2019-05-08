<div class="navbar-top">
<div class="container">

<div class="navbar-logo">
<a class="navbar-brand" href="/user/main/"><img src="http://www.clickmonitor.co.uk/wp-content/uploads/2016/03/logo-1.png" height="60"></a>
</div>


<ul class="navbar-list" style="margin-top:20px;">
<li><a href="/admin/users/">Users</a></li>
<li><a href="/admin/maps-settings/">Adwords Settings</a></li>
<li><a href="/admin/settings/">Account Settings</a></li>
<!--<li><a href="/admin/messages/">Messages</a></li>-->
</ul>


<div class="navbar-login" style="margin-top:20px;">

@if(Auth::check())
<a href="/auth/logout/" class="button-common">Exit</a>
@else
<a href="/auth/login/" class="button-common">LogIn</a>
@endif

</div>

<div class="clear"></div>
</div>
</div>