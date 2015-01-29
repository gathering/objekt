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
	<a href class="navbar-brand block m-t">{{ $event->name }}</a>
	<div class="m-b-lg">
		<p>Har du glemt ditt passord? Vi sendte deg passordet per SMS tidligere.</p><br /><br />
		<form method="post">
			<div class="list-group list-group-sm">
				<div class="list-group-item">
					<input type="text" name="phone" placeholder="{{ __('common.phone') }}" class="form-control no-border">
				</div>
			</div>
			<button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('common.send_password') }}</button>
		</form>
	</div>
</div>

</body>
</html>