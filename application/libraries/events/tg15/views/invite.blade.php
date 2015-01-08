<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

	<title>Invite - Objekt - Transolini</title>

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
			width: 440px;
			margin: 0 auto;
		}
	</style>
</head>

<body>

<div class="login-page">	
	<div class="login-wrapper">
		<form id="form-login" action="{{ url('/invite') }}" method="post">
			<center>
				<img src="{{ asset('images/logo_backolini_invite.png') }}" />
				+
				<img src="http://wannabe.gathering.org/img/wlogo.png" />
			</center>
			<h3>Invitiation to use Objekt for<br />The Gathering 2015</h3>
			<p>
				As a member of <b>Core:Sponsor</b>, <b>Info:Desk</b>, <b>Core:Floor</b> or <b>Economy</b> you are eligible to create an account on Objekt for The Gathering 2015. You will be granted a user-role based on what crew you a part of.<br /><br />We would like you to take note that we only save the password you enter here, and do not synchronize with Wannabe any time later.<br /><br />
				To create an account, you need to enter your <a href="http://wannabe.gathering.org/tg15" target="_blank">Wannabe</a> account details below. This can only be done once.
			</p>
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
				@if ( Session::get('error') )
				<hr>
				<div class="notify notify-error">
					
					<a href="javascript:;" class="close">&times;</a>
					
					<h3>{{ __('common.error_title_login') }}</h3>
					
					<p>{{ Session::get('error') }}</p>

				</div>
				@endif
			</p>
		</form>
	</div>	
</div> <!-- #login -->

<script src="//cdnjs.cloudflare.com/ajax/libs/retina.js/1.0.1/retina.js"></script> 


</body>
</html>