<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<title>Login - Objekt - Transolini</title>

	<meta charset="utf-8" />
	<meta name="description" content="" />
	<meta name="author" content="" />		
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<link rel="stylesheet" href="{{ asset('css/backolini/bootstrap.css') }}">
  	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('css/backolini/font.css') }}">
	<link rel="stylesheet" href="{{ asset('css/backolini/app.css') }}">
</head>

<body>
<div class="container w-xxl w-auto-xs" style="background: white;">
	<a href class="navbar-brand block m-t">{{ $event->name }}</a>
	<div class="m-b-lg">
		<div class="wrapper text-center">
			<strong>Innlogging for partnere</strong><br />
			<i>Kun for {{ $event->name }}s partnere.</i>
		</div>
		<form name="form" method="post" class="form-validation">
			@if ( Session::get('error') )
			<div class="text-danger wrapper text-center">
				<p>{{ Session::get('error') }}</p>
			</div>
			@endif
			@if ( Session::get('success') )
			<div class="text-info wrapper text-center">
				<p>{{ Session::get('success') }}</p>
			</div>
			@endif
			@if ( Session::get('referer') )
			<input type="hidden" name="referer" value="{{ Session::get('referer') }}" />
			@endif
			<div class="list-group list-group-sm">
				<div class="list-group-item">
					<input type="text" name="username" placeholder="{{ __('common.phone') }}" class="form-control no-border">
				</div><br />
				<div class="alert alert-info">{{ __('profile.phone_news_login') }}</div>
				<div class="list-group-item">
					<input type="password" name="password" placeholder="{{ __('common.password') }}" class="form-control no-border">
				</div>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block" title="{{ __('common.logged_in_title') }}">{{ __('common.login_button') }}</button>
			
			<div class="text-center m-t m-b"><a href="{{ url('partner/forgot') }}">{{ __('common.forgot_password') }}</a></div>
			<div class="line line-dashed"></div>
			<p class="text-center"><small>Første gang du bruker Objekt?</small></p>
			<a href="{{ url('partner/new') }}" class="btn btn-lg btn-default btn-block">Få passord tilsendt</a>
		</form>
	</div>
</div>

</body>
</html>