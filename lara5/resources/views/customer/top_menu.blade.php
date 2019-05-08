<div class="navbar-top">
<div class="container">

<div class="navbar-logo">
<a class="navbar-brand" href="/user/main/"><img src="http://www.clickmonitor.co.uk/wp-content/uploads/2016/03/logo-1.png" height="60"></a>
</div>

<? if(Auth::user()->is_active != 1){ ?>
<ul class="navbar-list" style="margin-top:20px;">
<li><a href="/user/welcome/">Reports</a></li>
<li><a href="/user/welcome/">All Clicks</a></li>
<li><a href="/user/welcome/">Monitors</a></li>
<li><a href="/user/welcome/">IP Whitelist</a></li>
<li><a href="/user/welcome/">IP Look up</a></li>
<li><a href="/user/faqs/">Support</a></li>
<li><a href="/user/welcome/">Account Settings</a></li>
</ul>
<? }else{ ?>
<ul class="navbar-list" style="margin-top:20px;">
<li><a href="/user/main/">Reports</a></li>
<li><a href="/user/reports-all/">All Clicks</a></li>
<li><a href="/user/trackers/">Monitors</a></li>
<li><a href="/user/ip-whitelist/">IP Whitelist</a></li>
<li><a href="/user/report-ip-detail/">IP Look up</a></li>
<li><a href="/user/faqs/">Support</a></li>
<li><a href="/user/settings/">Account Settings</a></li>
</ul>
<? } ?>

<div class="navbar-login" style="margin-top:20px;">

@if(Auth::check())
<a href="/auth/logout/" class="button-common">Log out</a>
@else
<a href="/auth/login/" class="button-common">LogIn</a>
@endif

</div>

<div class="clear"></div>
</div>
</div>