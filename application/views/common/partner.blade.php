<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{ $title }} ({{ $current_event->name }}) Objekt - Transolini</title>
	<meta name="viewport"     content="width=device-width, initial-scale=1, maximum-scale=1">	
	<link rel="stylesheet"    href="{{ asset('css/bootstrap.css') }}">
  <link rel="stylesheet"    href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet"    href="{{ asset('css/style.css') }}">
  <link rel="stylesheet"    href="{{ asset('css/plugin.css') }}">
  <link rel="stylesheet"    href="{{ asset('css/landing.css') }}">

  <link rel="stylesheet"    href="{{ asset('css/messenger.css') }}">
  <link rel="stylesheet"    href="{{ asset('css/messenger-theme-flat.css') }}">
  <link rel="stylesheet"    href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.0.0/css/bootstrap-datetimepicker.min.css">
  <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}"/>
  <!--[if lt IE 9]>
    <script src="{{ asset('js/ie/respond.min.js') }}"></script>
    <script src="{{ asset('js/ie/html5.js') }}"></script>
    <script src="{{ asset('js/ie/excanvas.js') }}"></script>
  <![endif]-->
  @yieldSection('styles')
</head>
<body>
<!-- header -->
	<header id="header" class="navbar bg bg-black">
    <ul class="nav navbar-nav navbar-avatar pull-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">            
          <span class="hidden-xs-only">{{ partnerAuth::user()->firstname." ".partnerAuth::user()->surname }}</span>
          <span class="thumb-small avatar inline"><img src="{{ partnerAuth::user()->gravatar() }}" style="height: 36px;" class="img-circle"></span>
          <b class="caret hidden-xs-only"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="{{ url('/partner/logout') }}">{{ __('nav.logout') }}</a></li>
        </ul>
      </li>
    </ul>
    <a class="navbar-brand" href="{{ url('/partner') }}"><img src="{{ asset('images/logo_transolini.png') }}" alt="" /></a>
    <button type="button" class="btn btn-link pull-left nav-toggle visible-xs" data-toggle="class:slide-nav slide-nav-left" data-target="body">
      <i class="fa fa-bars icon-xlarge text-default"></i>
    </button>
    <ul class="nav navbar-nav hidden-xs pull-right">
      
    </ul>
	</header>
  <!-- / header -->
  <!-- nav -->
  <nav id="nav" class="nav-primary hidden-xs">
    <ul class="nav" data-spy="affix" data-offset-top="50">
      <li {{ URI::segment(3) == '' ? 'class="active"' : '' }}>
        <a href="{{ url('/partner') }}">
          <i class="fa fa-dashboard icon-xlarge"></i><span>{{ __('nav.dashboard') }}</span>
        </a>
      </li>
      <li {{ URI::segment(3) == '' ? 'class="active"' : '' }}>
        <a href="{{ url('/partner/accreditation') }}">
          <i class="fa fa-users icon-xlarge"></i><span>{{ __('nav.partner.accreditation') }}</span>
        </a>
      </li>
      <!--<li {{ URI::segment(3) == 'shop' ? 'class="active"' : '' }}><a href="{{ url('/partner/shop') }}"><i class="fa fa-cubes icon-xlarge"></i><span>{{ __('nav.partner.shop') }}</span></a></li>
      @if(isset($cart) && count($cartItems) > 0)
      <li {{ URI::segment(3) == 'shop' ? 'class="active"' : '' }}><a href="{{ url('/partner/shop/cart') }}"><i class="fa fa-shopping-cart icon-xlarge"></i><span>{{ __('nav.partner.cart') }}</span></a></li>
      @endif
    -->
    </ul>
  </nav>
  <!-- / nav -->
  @if (!tplConstructor::has())
  <section id="content">
    <section class="main padder">
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
      <span class="label bg-inverse">{{ $current_event->name }} // {{ partnerAuth::user()->profile()->name }}</span><br /><br />
	  </p>
	</div>
	</footer>
	<a href="#" class="hide slide-nav-block" data-toggle="class:slide-nav slide-nav-left" data-target="body"></a>
	<!-- / footer -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.2/typeahead.bundle.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/2.0.0-alpha.2/handlebars.min.js"></script>
  <!-- app -->
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/app.plugin.js') }}"></script>
	<script src="{{ asset('js/app.data.js') }}"></script>

  <script src="{{ asset('js/messenger.min.js') }}"></script>
  <script src="{{ asset('js/messenger-theme-flat.js') }}"></script>

  <script>
  $(function() {
    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom',
        theme: 'flat'
    }
  });
  </script>

  @if ( Session::get('error') || Session::get('success') )

  

  <script>
  $(function() {
    @if ( is_object(Session::get('error')) && isset(Session::get('error')->messages) && is_array(Session::get('error')->messages) )
    @foreach (Session::get('error')->messages as $message)
    Messenger().post({
      message: '{{ $message[0] }}',
      type: 'error'
    });
    @endforeach
    @elseif ( Session::get('error') )
    Messenger().post({
      message: '{{ Session::get('error') }}',
      type: 'error'
    });
    @else
    Messenger().post({
      message: '{{ Session::get('success') }}',
      type: 'success'
    });
    @endif
  });
  </script>

  @endif

  <!-- TODO: Add authentication -->
  <!--<script src="{{ asset('js/strophe.js') }}"></script>
  <script src="{{ asset('js/chat.js') }}"></script>-->

	<!-- fuelux -->
	<script src="{{ asset('js/fuelux/fuelux.js') }}"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore-min.js"></script>
  
	<!-- Sparkline Chart -->
	<script src="{{ asset('js/charts/sparkline/jquery.sparkline.min.js') }}"></script>  
	<!-- Easy Pie Chart -->
	<script src="{{ asset('js/charts/easypiechart/jquery.easy-pie-chart.js') }}"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/retina.js/1.0.1/retina.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/pusher/2.1.6/pusher.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/2.1.30/js/locales/bootstrap-datetimepicker.nb.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.0.0/js/bootstrap-datetimepicker.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.nb.min.js" charset="UTF-8"></script>

  @yieldSection('partner_scripts')
  @yieldSection('partner_custom_scripts')
</body>
</html>