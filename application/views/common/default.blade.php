<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{ $title }} (<? $event = Config::get('application.event'); echo $event->name; ?>) SuperSponsor - Transolini</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
  	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
  	<link rel="stylesheet" href="{{ asset('css/plugin.css') }}">
  	<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
	<!--[if lt IE 9]>
		<script src="{{ asset('js/ie/respond.min.js') }}"></script>
		<script src="{{ asset('js/ie/html5.js') }}"></script>
		<script src="{{ asset('js/ie/excanvas.js') }}"></script>
	<![endif]-->
</head>
<body>
<!-- header -->
	<header id="header" class="navbar bg bg-black">
    <ul class="nav navbar-nav navbar-avatar pull-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">            
          <span class="hidden-xs-only">{{ Auth::user()->username }}</span>
          <span class="thumb-small avatar inline"><img src="http://www.gravatar.com/avatar/{{ md5( strtolower( trim( Auth::user()->email ) ) ) }}&s=36" alt="Mika Sokeil" class="img-circle"></span>
          <b class="caret hidden-xs-only"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="{{ url('logout/') }}">{{ __('nav.logout') }}</a></li>
        </ul>
      </li>
    </ul>
    <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/logo_transolini.png') }}" alt="" /></a>
    <button type="button" class="btn btn-link pull-left nav-toggle visible-xs" data-toggle="class:slide-nav slide-nav-left" data-target="body">
      <i class="icon-reorder icon-xlarge text-default"></i>
    </button>
    <ul class="nav navbar-nav hidden-xs">
      <li>
        <div class="m-t m-b-small" id="panel-notifications">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-comment-alt icon-xlarge text-default"></i><b class="badge badge-notes bg-danger count-n">2</b></a>
          <section class="dropdown-menu m-l-small m-t-mini">
            <section class="panel panel-large arrow arrow-top">
              <header class="panel-heading bg-white"><span class="h5"><strong>You have <span class="count-n">2</span> notifications</strong></span></header>
              <div class="list-group">
                <a href="#" class="media list-group-item">
                  <span class="pull-left thumb-small"><img src="images/avatar.jpg" alt="John said" class="img-circle"></span>
                  <span class="media-body block m-b-none">
                    Moved to Bootstrap 3.0<br>
                    <small class="text-muted">23 June 13</small>
                  </span>
                </a>
                <a href="#" class="media list-group-item">
                  <span class="media-body block m-b-none">
                    first v.1 (Bootstrap 2.3 based) released<br>
                    <small class="text-muted">19 June 13</small>
                  </span>
                </a>
              </div>
              <footer class="panel-footer text-small">
                <a href="#" class="pull-right"><i class="icon-cog"></i></a>
                <a href="#">See all the notifications</a>
              </footer>
            </section>
          </section>
        </div>
      </li>
    </ul>
    <form action="{{ url('search') }}" method="post" class="navbar-form pull-left shift">
      <i class="icon-search text-muted"></i>
      <input type="text" name="search" class="input-sm form-control" placeholder="{{ __('common.search') }}">
    </form>
	</header>
  <!-- / header -->
  <!-- nav -->
  <nav id="nav" class="nav-primary hidden-xs nav-vertical">
    <ul class="nav" data-spy="affix" data-offset-top="50">
      <li {{ URI::segment(2) == '' ? 'class="active"' : '' }}><a href="{{ url('/') }}"><i class="icon-dashboard icon-xlarge"></i><span>{{ __('nav.dashboard') }}</span></a></li>
      @if (Auth::user()->is("superSponsorAdmin"))
      <li class="dropdown-submenu {{ URI::segment(2) == 'users' ? ' active' : '' }}">
        <a href="#"><i class="icon-suitcase icon-xlarge"></i><span>{{ __('nav.admin') }}</span></a>
        <ul class="dropdown-menu">
          <li><a href="{{ url('/users') }}">{{ __('nav.users') }}</a></li>
        </ul>
      </li>
      @endif
      @if (Auth::user()->can("sponsorprofiles"))
      <li class="dropdown-submenu">
        <a href="{{ url('/sponsors') }}"><i class="icon-group icon-xlarge"></i><span>{{ __('nav.sponsors') }}</span></a>
        <ul class="dropdown-menu">
          <li><a href="{{ url('/sponsors') }}">{{ __('nav.list_sponsors') }}</a></li>
          <li><a href="{{ url('/sponsor/add') }}">{{ __('user.add_new_sponsor') }}</a></li>
        </ul>
      </li>
      @endif
      <li {{ URI::segment(2) == 'accreditation' ? 'class="active"' : '' }}><a href="{{ url('/accreditation') }}"><i class="icon-tags icon-xlarge"></i><span>{{ __('nav.accreditation') }}</span></a></li>
    </ul>
  </nav>
  <!-- / nav -->
  @if (!tplConstructor::has())
  <section id="content">
    <section class="main padder">
  @endif
  	@if ( is_object(Session::get('error')) && isset(Session::get('error')->messages) && is_array(Session::get('error')->messages) )
  	@foreach (Session::get('error')->messages as $message)
  	<div class="alert alert-danger">
  		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
  		<i class="icon-ban-circle icon-large"></i><strong>{{ __('common.error_title') }}</strong> {{ $message[0] }}.
  	</div>
  	@endforeach
  	@elseif ( Session::get('error') )
  	<div class="alert alert-danger">
  		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
  		<i class="icon-ban-circle icon-large"></i><strong>{{ __('common.error_title') }}</strong> {{ Session::get('error') }}.
  	</div>
  	@endif
  	@if ( Session::get('success') )
  	<div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
          <i class="icon-ok-sign icon-large"></i><strong>{{ __('common.success_title') }}</strong> {{ Session::get('success') }}.
      </div>
  	@endif
  	{{ $content }}
    @if (!tplConstructor::has())
    </section>
  </section>
  @endif
	<!-- footer -->
	<footer id="footer">
	<div class="text-center padder clearfix">
	  <p>
	    <small>{{__('common.footer')}}</small><br><br>
	    <a href="#" class="btn btn-xs btn-circle btn-twitter"><i class="icon-twitter"></i></a>
	    <a href="#" class="btn btn-xs btn-circle btn-facebook"><i class="icon-facebook"></i></a>
	    <a href="#" class="btn btn-xs btn-circle btn-gplus"><i class="icon-google-plus"></i></a>
	  </p>
	</div>
	</footer>
	<a href="#" class="hide slide-nav-block" data-toggle="class:slide-nav slide-nav-left" data-target="body"></a>
	<!-- / footer -->
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	<!-- Bootstrap -->
	<script src="{{ asset('js/bootstrap.js') }}"></script>
	<!-- app -->
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/app.plugin.js') }}"></script>
	<script src="{{ asset('js/app.data.js') }}"></script>

	<!-- fuelux -->
	<script src="{{ asset('js/fuelux/fuelux.js') }}"></script>
	<script src="{{ asset('js/underscore-min.js') }}"></script>
	<!-- datatables -->
	<script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>


	<!-- Sparkline Chart -->
	<script src="{{ asset('js/charts/sparkline/jquery.sparkline.min.js') }}"></script>  
	<!-- Easy Pie Chart -->
	<script src="{{ asset('js/charts/easypiechart/jquery.easy-pie-chart.js') }}"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/retina.js/1.0.1/retina.js"></script>  
</body>
</html>