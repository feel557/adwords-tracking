<div class="navbar-top">
<div class="container">

<div class="navbar-logo">
<a class="navbar-brand" href="/user/main/">PPC Surge</a>
</div>


<ul class="navbar-list">
<li><a href="/user/main/">Dashboard</a></li>
<li><a href="/user/reports-page/">Reports</a></li>
<li><a href="/user/trackers/">Monitors</a></li>
<li><a href="/user/ip-whitelist/">IP Whitelist</a></li>
<li><a href="/user/faqs/">Support</a></li>
<li><a href="/user/settings/">Account Settings</a></li>
</ul>


<div class="navbar-login">

@if(Auth::check())
<a href="/auth/logout/" class="button-common">Log out</a>
@else
<a href="/auth/login/" class="button-common">LogIn</a>
@endif

</div>

<div class="clear"></div>
</div>
</div>