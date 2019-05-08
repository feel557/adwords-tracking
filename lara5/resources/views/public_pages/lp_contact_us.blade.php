@extends('lp_layout')

@section('title')
Contact Us
@stop


@section('header')
<header class="header pageRow paralax" style="background-image: url('media/paralax/2.jpg');height:120px;position:relative;">
			
			<div class="wrapper">
				<div class="header-outer">
					<div class="header-inner">
						<div class="header-top row">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><a href="." class="logo"></a></div>
							<div class="col-lg-9 col-md-9 col-sm-6 col-xs-6">
								<i class="fa fa-bars mobileMenuNav whiteTxtColor"></i>
								<nav class="nav-container customBgColor">
									<i class="fa fa-times mobileMenuNav small-device"></i>
									<ul class="menu">
										<li><a class="roboto whiteTxtColor" href="/">HOME</a></li>
										<li><a class="roboto whiteTxtColor" href="/trial/">FREE TRIAL</a></li>
										<li><a class="roboto whiteTxtColor" href="/faq/">FAQ</a></li>
										<li><a class="roboto whiteTxtColor" href="/about/">ABOUT US</a></li>
										<li><a class="roboto whiteTxtColor" href="/contact/">CONTACT US</a></li>
										<li><a class="roboto whiteTxtColor" href="/login/">LOG IN</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>

</div>
		
		</header>
@stop

@section('content')


		<section id="support" class="pageRow skew" style="padding-top:40px;">
			<div class="wrapper">
				<div class="supportBox row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
						<h3 class="sectionTitle text-center ralewayLight balck">Contact Us</h3>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 clearfix">
							<div class="support-item clearfix wow fadeInLeft" data-wow-delay="0.3s">
								<div class="circle smallCircle">
									<span><i class="fa fa-map-marker customColor"></i></span>
								</div>
								<h4 class="support-item_name ralewaySemiBold blackTxtColor">Address</h4>
								<div class="support-item_info robotoLight">1456 MacDonald Ranch Drive Henderson, NV 89012</div>
							</div>
						
						
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 clearfix">
							<div class="support-desc robotoLight">Please use this form to email us any questions you have or just to introduce yourself.</div>
							<div class="support-formbox clearfix wow fadeInRight" data-wow-delay="0.3s">
<div style="border:1px solid #aaa;padding:30px;border-radius:10px;">
								<div id="success"></div>
								<form novalidate id="contactForm" class="support-form form-inline" name="sentMessage">
									<div class="form-group half-wigth pull-left">
										<label class="sr-only" for="user-name">Full Name</label>
										<input type="text" data-validation-required-message="Please enter your full name." required class="lineField robotoLight" id="user-name" placeholder="Full Name *">
										<p class="help-block text-danger"></p>
									</div>
									<div class="form-group half-wigth pull-right">
										<label class="sr-only" for="user-email">Email</label>
										<input type="email" data-validation-required-message="Please enter your email address." required class="lineField robotoLight" id="user-email" placeholder="Email *">
										<p class="help-block text-danger"></p>
									</div>
									<div class="form-group full-width pull-left">
										<label class="sr-only" for="user-message">Message</label>
										<textarea data-validation-required-message="Please enter a message." required class="lineField robotoLight" id="user-message" placeholder="Message"></textarea>
										<p class="help-block text-danger"></p>
									</div>
									<button type="submit" class="ellipseSubmitBtn whiteTxtColor smallBtn robotoMedium customBgColor hvr-pop pull-left clear">Send</button>
								</form>
<div style="clear:both;"></div>
</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</section>	


@stop