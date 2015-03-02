<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{ $title }} ({{ $current_event->name }}) Objekt - Transolini</title>
	<meta name="viewport"     content="width=device-width, initial-scale=1, maximum-scale=1">	
	<link rel="stylesheet"    href="{{ asset('css/bootstrap.css') }}">
  <link rel="stylesheet"    href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css">
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
          <span class="hidden-xs-only">{{ Auth::user()->username }}</span>
          <span class="thumb-small avatar inline"><img src="{{ Auth::user()->image() }}" style="height: 36px;" alt="Mika Sokeil" class="img-circle"></span>
          <b class="caret hidden-xs-only"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="{{ url('/pushover') }}">{{ __('nav.pushover') }}</a></li>
          <li class="divider"></li>
          <li><a href="{{ url('/change_event') }}">{{ __('nav.change_event') }}</a></li>
          <li class="divider"></li>
          <li><a href="{{ url('/logout') }}">{{ __('nav.logout') }}</a></li>
        </ul>
      </li>
    </ul>
    <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/logo_transolini.png') }}" alt="" /></a>
    <button type="button" class="btn btn-link pull-left nav-toggle visible-xs" data-toggle="class:slide-nav slide-nav-left" data-target="body">
      <i class="fa fa-bars icon-xlarge text-default"></i>
    </button>
    <? $notifications = Auth::user()->notifications()->order_by('created_at', 'desc')->where("status", "=", "unread"); $notification_count = $notifications->count(); ?> 
    <ul class="nav navbar-nav hidden-xs">
      <li>
        <div class="m-t m-b-small" id="notifications">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-comment-o icon-xlarge text-default"></i><b id="notification-badge" class="badge badge-notes bg-danger count-n {{ $notification_count > 0 ? '' : 'hide' }}">{{ $notification_count }}</b>
          </a>
          <section class="dropdown-menu m-l-small m-t-mini">
            <section class="panel panel-large arrow arrow-top">
              <header class="panel-heading bg-white"><span class="h5"><strong>{{ sprintf(__('notification.you_have'), $notification_count) }}</strong></span></header>
              <div class="list-group" id="notification_list">
                @foreach ($notifications->get() as $notification)
                @if(!empty($notification->url))
                <a href="{{ $notification->url }}" class="media list-group-item">
                @else
                <div class="media list-group-item">
                @endif
                  <span class="media-body block m-b-none">
                    <b>{{ $notification->title }}</b><br>
                    {{ $notification->message }}
                    <br />
                    <small class="text-muted">{{ date::nice($notification->created_at) }}</small>
                  </span>
                @if(!empty($notification->url))
                </a>
                @else
                </div>
                @endif
                @endforeach
              </div>
              <footer class="panel-footer text-small">
                <a href="{{ url('notifications') }}">{{ __('notification.see_all') }}</a>
              </footer>
            </section>
          </section>
        </div>
      </li>
    </ul>
    @if (Auth::user()->can("search"))
    <form action="{{ url('search') }}" method="post" class="navbar-form pull-left shift">
      <i class="fa fa-search text-muted"></i>
      <input type="text" name="search" class="input-sm form-control" placeholder="{{ __('common.search') }}">
    </form>
    @endif
    <ul class="nav navbar-nav hidden-xs pull-right">
      @yieldSection('top_buttons')
    </ul>
	</header>
  <!-- / header -->
  <!-- nav -->
  <nav id="nav" class="nav-primary hidden-xs nav-vertical">
    <ul class="nav" data-spy="affix" data-offset-top="50">
      <li {{ URI::segment(2) == '' ? 'class="active"' : '' }}><a href="{{ url('/') }}"><i class="fa fa-dashboard icon-xlarge"></i><span>{{ __('nav.dashboard') }}</span></a></li>
      @if (Auth::user()->can("admin"))
      <li class="dropdown-submenu {{ URI::segment(2) == 'admin' || URI::segment(2) == 'users' ? ' active' : '' }}">
        @if (Auth::user()->is("superAdmin"))
        <a href="{{ url('/admin/event/'.$current_event->slug) }}"><i class="fa fa-suitcase icon-xlarge"></i><span>{{ __('nav.admin') }}</span></a>
        @else
        <a href="#"><i class="fa fa-suitcase icon-xlarge"></i><span>{{ __('nav.admin') }}</span></a>
        @endif
        <ul class="dropdown-menu">
          @if (Auth::user()->is("superAdmin"))
          <li{{ URI::segment(2) == 'admin' && URI::segment(3) =='events' ? ' class="active"' : '' }}>
            <a href="{{ url('/admin/events') }}">{{ __('nav.events') }}</a>
          </li>
          @endif
          @if (Auth::user()->can("users"))
          <li{{ URI::segment(2) == 'users' && URI::segment(3) != 'roles'  ? ' class="active"' : '' }}>
            <a href="{{ url('/users') }}">{{ __('nav.users') }}</a>
          </li>
          @endif
          @if (Auth::user()->can("manage_roles"))
          <li{{ URI::segment(2) == 'users' && URI::segment(3) == 'roles' ? ' class="active"' : '' }}>
            <a href="{{ url('/users/roles') }}">{{ __('nav.roles') }}</a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if (Auth::user()->can("mediabank"))
      <li {{ URI::segment(2) == 'mediabank' ? 'class="active"' : '' }}><a href="{{ url('/mediabank') }}"><i class="fa fa-picture-o icon-xlarge"></i><span>{{ __('nav.mediabank') }}</span></a></li>
      @endif
      @if (Auth::user()->can("profiles"))
      <li class="dropdown-submenu {{ URI::segment(2) == 'profiles' ? ' active' : '' }}">
        <a href="{{ url('/profiles') }}"><i class="fa fa-group icon-xlarge"></i><span>{{ __('nav.profiles') }}</span></a>
        <ul class="dropdown-menu">
          <li{{ URI::segment(2) == 'profiles' && URI::segment(3) =='' ? ' class="active"' : '' }}>
            <a href="{{ url('/profiles') }}">{{ __('nav.list_profiles') }}</a>
          </li>
          @if (Auth::user()->can("add_profile"))
          <li{{ URI::segment(2) == 'profile' && URI::segment(3) =='add' ? ' class="active"' : '' }}>
            <a href="{{ url('/profile/add') }}">{{ __('user.add_new_profile') }}</a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if (Auth::user()->can("accreditation"))
      <li {{ URI::segment(2) == 'accreditation' ? 'class="active"' : '' }}>
        <a href="{{ url('/accreditation') }}"><i class="fa fa-tags icon-xlarge"></i><span>{{ __('nav.accreditation') }}</span></a>
      </li>
      @endif
      @if (Auth::user()->can("logistics"))
      <li {{ URI::segment(2) == 'logistics' ? 'class="active"' : '' }}><a href="{{ url('/logistics') }}"><i class="fa fa-truck icon-xlarge"></i><span>{{ __('nav.logistics') }}</span></a></li>
      @endif
      @if (Auth::user()->is("superAdmin"))
      <li {{ URI::segment(2) == 'memo' ? 'class="active"' : '' }}>
        <a href="{{ url('memo') }}"><i class="fa fa-file-text icon-xlarge"></i><span>{{ __('memo.memo') }}</span></a>
      </li>
      @endif
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
      <span class="label bg-inverse">{{ $current_event->name }}</span><br /><br />
	    <small>{{__('common.footer')}}<br>
      <? echo shell_exec("git log -1 --pretty=format:'%h - %s (%ci)' --abbrev-commit"); ?></small><br><br>
	    <a href="https://twitter.com/objno" target="_blank" class="btn btn-xs btn-circle btn-twitter"><i class="fa fa-twitter"></i></a>
	    <a href="https://facebook.com/objno" target="_blank" class="btn btn-xs btn-circle btn-facebook"><i class="fa fa-facebook"></i></a>
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
  
  <script>
  $(function() {
    var num_notifications = {{ $notification_count }};
    function add_notification(data){
        if(data.url){
          var $div = $("<a class='media list-group-item new' />");
          $div.attr("href", url);
        } else {
         var $div = $("<div class='media list-group-item new' />");
        }
        var $message = $("<span class='media-body block m-b-none' />");
        var $title = $("<b/>");
        var $date = $("<small class='text-muted'/>").html('{{ __('notification.just_now') }}');

        $title.html(data.title);
        $message.append($title);
        $message.append('<br>'+data.message+'<br>');
        $message.append($date);
        $div.append($message);

        $("#notification-badge").removeClass('hide');
        $("#notification-badge").show();

        num_notifications += 1;
        $(".count-n").html(num_notifications);
        $("#notification_list").prepend($div);
    }

    $("#notifications").on('hidden.bs.dropdown', function () {
      $(".new").removeClass('.new');
      $("#notification-badge").hide();
      $("#notification_list").empty();
      num_notifications = 0;
      $(".count-n").html(num_notifications);
      $.ajax({
        url: '{{ url('notifications/readall') }}'
      });
    });

    var pusher = new Pusher('{{ Config::get('pusher.app_key') }}', { authEndpoint: '{{ url('/pusher_auth') }}' });
    var presenceChannel = pusher.subscribe('presence-obj');
    var channel = pusher.subscribe('{{ md5('user_'.Auth::user()->id) }}');
    channel.bind('notification', add_notification);
    @yieldSection('special_scripts')
  });
  </script>

  @yieldSection('scripts')
  @yieldSection('custom_scripts')
  @if(isset(serverConfig::get()->name))
  <br />
  <hr />
  <br />
  <b>{{ serverConfig::get()->name }}</b>({{ serverConfig::get()->ip }})<br />
  <br />
  @if(isset(serverConfig::get()->powered_by))
  <b>Powered by</b>
  <a href="{{ serverConfig::get()->powered_by->url }}">{{ serverConfig::get()->powered_by->logo }}</a>
  @endif
  @endif
</body>
</html>