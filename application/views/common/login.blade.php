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

<div class="container w-xxl w-auto-xs">
	<a href class="navbar-brand block m-t"><img src="{{ asset('images/logo_backolini_whale.png') }}" /></a>
	<div class="m-b-lg">
		<div class="wrapper text-center">
			<strong>Logg inn på din konto</strong>
		</div>
		<form name="form" method="post" class="form-validation">
			@if ( Session::get('error') )
			<div class="text-danger wrapper text-center">
				<p>{{ Session::get('error') }}</p>
			</div>
			@endif
			@if ( Session::get('referer') )
			<input type="hidden" name="referer" value="{{ Session::get('referer') }}" />
			@endif
			<div class="list-group list-group-sm">
				<div class="list-group-item">
					<input type="text" name="username" placeholder="{{ __('common.username') }}" class="form-control no-border">
				</div>
				<div class="list-group-item">
					<input type="password" name="password" placeholder="{{ __('common.password') }}" class="form-control no-border">
				</div>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block" title="{{ __('common.logged_in_title') }}">{{ __('common.login_button') }}</button>
			
			<!--<div class="text-center m-t m-b"><a ui-sref="access.forgotpwd">Forgot password?</a></div>
			<div class="line line-dashed"></div>
			<p class="text-center"><small>Do not have an account?</small></p>
			<a ui-sref="access.signup" class="btn btn-lg btn-default btn-block">Create an account</a>-->
		</form>
	</div>
	<div class="text-center" ng-include="'tpl/blocks/page_footer.html'"></div>
</div>

</body>
</html>