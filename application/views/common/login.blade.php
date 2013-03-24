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
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<link rel="stylesheet" href="{{ asset('stylesheets/all.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('stylesheets/reset.css') }}" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="{{ asset('stylesheets/text.css') }}" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="{{ asset('stylesheets/buttons.css') }}" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="{{ asset('stylesheets/theme-default.css') }}" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="{{ asset('stylesheets/login.css?v=2') }}" type="text/css" media="screen" title="no title" />
</head>

<body>

<div id="login">
	<h1></h1>
	@if ( Session::get('error') )
	<div class="notify notify-error">
		
		<a href="javascript:;" class="close">&times;</a>
		
		<h3>{{ __('common.error_title_login') }}</h3>
		
		<p>{{ Session::get('error') }}</p>

	</div>
	@endif
	<div id="login_panel">
		<form action="{{ url('/login') }}" method="post" accept-charset="utf-8">

			<div class="login_fields">
				<div class="field">
					<label for="username">{{ __('common.username') }}</label>
					<input type="text" name="username" value="" id="username" tabindex="1" placeholder="{{ __('common.placeholder_username') }}" />		
				</div>
				
				<div class="field">
					<label for="password">{{ __('common.password') }} <small><a href="javascript:alert('{{ __('common.forgot_alert') }}');">{{ __('common.forgot') }}</a></small></label>
					<input type="password" name="password" value="" id="password" tabindex="2" placeholder="{{ __('common.placeholder_password') }}" />			
				</div>
			</div> <!-- .login_fields -->
			
			<div class="login_actions">
				<button type="submit" class="btn btn-primary" tabindex="3" title="{{ __('common.logged_in_title') }}">{{ __('common.login_button') }}</button>
			</div>
		</form>
	</div> <!-- #login_panel -->		
</div> <!-- #login -->

<script src="{{ asset('javascripts/all.js') }}"></script>


</body>
</html>