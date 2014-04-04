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
	<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
  	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
  	<link rel="stylesheet" href="{{ asset('css/plugin.css') }}">
  	<link rel="stylesheet" href="{{ asset('css/landing.css') }}">

	<style>
		.login-page .login-wrapper {
			position: relative;
			z-index: 2;
		}
		.login-page .login-wrapper form {
			background-color: #fff;
			padding: 20px;
			width: 340px;
			margin: 0 auto;
		}
	</style>
</head>

<body>

<div class="login-page">	
	<div class="login-wrapper">
		<form id="form-login" action="{{ url('/login') }}" method="post">
			<center>
				<img src="{{ asset('images/logo_transolini_black.png') }}" />
			</center>
			<h3>Logg inn på din konto</h3>
			@if ( Session::get('referer') )
			<input type="hidden" name="referer" value="{{ Session::get('referer') }}" />
			@endif
			<hr>
			<div class="form-group">
				<div class="controls">
					<input type="text" name="username" placeholder="{{ __('common.username') }}" class="form-control" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<div class="controls">
					<input type="password" name="password" placeholder="{{ __('common.password') }}" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary form-control" title="{{ __('common.logged_in_title') }}">{{ __('common.login_button') }}</button>
				</div>
			</div>
			<hr>
			<p>
				Tilgang til denne tjenesten gis kun i anledning et arrangement.
			</p>
			<p>
				@if ( Session::get('error') )
				<hr>
				<div class="notify notify-error">
					
					<a href="javascript:;" class="close">&times;</a>
					
					<h3>{{ __('common.error_title_login') }}</h3>
					
					<p>{{ Session::get('error') }}</p>

				</div>
				@endif
				@if ( Session::get('success') )
				<hr>
				<div class="notify notify-success">
					
					<a href="javascript:;" class="close">&times;</a>					
					<p>{{ Session::get('success') }}</p>

				</div>
				@endif
			</p>
		</form>
	</div>	
</div> <!-- #login -->

<script src="//cdnjs.cloudflare.com/ajax/libs/retina.js/1.0.1/retina.js"></script> 


</body>
</html>