

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<title>SuperSponsor</title>

	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="author" content="" />		
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
	<link rel="stylesheet" href="{{ asset('stylesheets/all.css') }}" type="text/css" />
	
	<!--[if gte IE 9]>
	<link rel="stylesheet" href="stylesheets/ie9.css" type="text/css" />
	<![endif]-->
	
	<!--[if gte IE 8]>
	<link rel="stylesheet" href="stylesheets/ie8.css" type="text/css" />
	<![endif]-->
	
</head>

<body>

<div id="wrapper">
	
	<div id="header">
		<h1><a href="{{ url('/') }}">SuperSponsor</a></h1>		
		
		<a href="javascript:;" id="reveal-nav">
			<span class="reveal-bar"></span>
			<span class="reveal-bar"></span>
			<span class="reveal-bar"></span>
		</a>
	</div> <!-- #header -->
	
	<div id="search">
		<form method="post" action="{{ url('search') }}">
			<input type="text" name="search" placeholder="{{ __('common.search') }}" id="searchField" />
		</form>		
	</div> <!-- #search -->
	
	<div id="sidebar">		
		
		<ul id="mainNav">			
			<li id="navDashboard" class="nav{{ URI::segment(2) == '' ? ' active' : '' }}">
				<span class="icon-home"></span>
				<a href="{{ url('/') }}">{{ __('nav.dashboard') }}</a>				
			</li>
			@if (Auth::user()->is("superSponsorAdmin"))
			<li id="navDashboard" class="nav{{ URI::segment(2) == 'users' ? ' active' : '' }}">
				<span class="icon-user"></span>
				<a href="{{ url('/users') }}">{{ __('nav.users') }}</a>				
			</li>
			@endif
			@if (Auth::user()->can("sponsorprofiles"))
			<li id="navDashboard" class="nav{{ URI::segment(2) == 'sponsors' ? ' active' : '' }}">
				<span class="icon-chat"></span>
				<a href="{{ url('/sponsors') }}">{{ __('nav.sponsors') }}</a>				
			</li>
			@endif
			@if (Auth::user()->can("accreditation"))
			<li id="navDashboard" class="nav{{ URI::segment(2) == 'accreditation' ? ' active' : '' }}">
				<span class="icon-document-fill"></span>
				<a href="{{ url('/accreditation') }}">{{ __('nav.accreditation') }}</a>				
			</li>
			@endif
		</ul>
				
	</div> <!-- #sidebar -->
	
	<div id="content">		
		
		<div id="contentHeader">
			<h1>{{ $title }}</h1>
		</div> <!-- #contentHeader -->	
		
		<div class="container">

			@if ( is_object(Session::get('error')) && isset(Session::get('error')->messages) && is_array(Session::get('error')->messages) )
			@foreach (Session::get('error')->messages as $message)
				<div class="grid-24">
					<div class="notify notify-error">
						<a href="javascript:;" class="close">&times;</a>
						<h3>{{ __('common.error_title') }}</h3>
						<p>{{ $message[0] }}</p>
					</div>
				</div>
			@endforeach
			@elseif ( Session::get('error') )
			<div class="grid-24">
				<div class="notify notify-error">
					
					<a href="javascript:;" class="close">&times;</a>
					
					<h3>{{ __('common.error_title') }}</h3>
					
					<p>{{ Session::get('error') }}</p>

				</div>
			</div>
			@endif
			@if ( Session::get('success') )
			<div class="grid-24">
				<div class="notify notify-success">
					
					<a href="javascript:;" class="close">&times;</a>
					
					<h3>{{ __('common.success_title') }}</h3>
					
					<p>{{ Session::get('success') }}</p>

				</div>
			</div>
			@endif
			
			{{ $content }}
			
			
		</div> <!-- .container -->
		
	</div> <!-- #content -->
	
	<div id="topNav">
		 <ul>
		 	<li>
		 		<span>{{ __('common.logged_in') }} <b>{{ Auth::user()->username }}</b></span>
	 		</li>
		 	<li><a href="{{ url('logout/') }}">{{ __('nav.logout') }}</a></li>
		 </ul>
	</div> <!-- #topNav -->
	
	@if (count($notifications) > 0)
	<div id="quickNav">
		<ul>
			<li class="quickNavNotification">
				<a href="#menuPie" class="menu"><span class="icon-chat"></span></a>
				<span class="alert">3</span>	
				<div id="menuPie" class="menu-container">
					<div class="menu-content cf">					
						
						<div class="qnc">
							
							<h3>{{ __('common.notifications') }}</h3>
					
							<a href="javascript:;" class="qnc_item">
								<div class="qnc_content">
									<span class="qnc_title">Notification #1</span>
									<span class="qnc_preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do.</span>
									<span class="qnc_time">3 hours ago</span>
								</div> <!-- .qnc_content -->
							</a>
							
							<a href="javascript:;" class="qnc_more">{{ __('common.view_all_notifications') }}</a>
							
						</div> <!-- .qnc -->
					</div>
				</div>				
			</li>
		</ul>		
	</div> <!-- .quickNav -->
	@endif	
	
</div> <!-- #wrapper -->

<div id="footer">
	{{__('common.footer')}}
</div>

<script src="{{ asset('javascripts/all.js') }}"></script>
<script>
$(function() {
	$("#datepicker").datepicker({ dateFormat: "yy-mm-dd", minDate: 0 });
	$("#timepicker").timepicker({ 
		showPeriod: false , showNowButton: true, showCloseButton: true,
		showPeriodLabels: false
	});
});
</script>
</body>
</html>