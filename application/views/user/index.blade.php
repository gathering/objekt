<br />
<a href="{{ url('users/add') }}" style="float: right;" class="btn btn-small btn-primary"><i class="icon-plus-sign"></i> {{ __('user.add_new_user') }}</a><br /><br />
<br />
<section class="panel">
	<ul class="list-group">
	  @foreach (User::current() as $user)
	  <li class="list-group-item">
	    <div class="media">
	      <span class="pull-left thumb-small"><img src="http://www.gravatar.com/avatar/{{ md5( strtolower( trim( $user->email ) ) ) }}&s=36" alt="John said" class="img-rounded"></span>
	      <div class="pull-right m-t-small">
	      	<div class="btn-group">
		         <a class="btn btn-xs btn-primary" href="{{url('user/'.$user->id.'/reset-password')}}">
		         	<i class="icon-key"></i>
		         	{{ __('user.reset_password') }}&nbsp;
		         </a>
		        <a class="btn btn-xs btn-danger" href="{{url('user/'.$user->id.'/delete-user')}}">
		        	<i class="icon-remove"></i>
		        </a>
		    </div>
	      </div>
	      <div class="media-body">
	      	<div class="pull-right">
	        	<span class="label label-info">{{ __('user.roles.'. strtolower($user->role()->first()->name)) }}</span>
	        </div>
	        <div><a href="#" class="h5">{{ $user->username }}</a></div>
	        <small class="text-muted">{{ $user->email }}</small>
	      </div>
	    </div>
	  </li>
	  @endforeach
	</ul>
</section>
@if (Auth::can('multievents'))
<div class="clearfix">
	<h4><i class="icon-th-list"></i> {{ __('user.non_activated_users') }}</h4>
</div>
<section class="panel">
	<ul class="list-group">
	  @foreach (User::non_current() as $user)
	  <li class="list-group-item">
	    <div class="media">
	      <span class="pull-left thumb-small"><img src="http://www.gravatar.com/avatar/{{ md5( strtolower( trim( $user->email ) ) ) }}&s=36" alt="John said" class="img-rounded"></span>
	      <div class="pull-right m-t-small">
	      	<div class="btn-group">
		        <a class="btn btn-xs btn-primary" href="{{url('user/'.$user->id.'/activate-user')}}">
		        	<i class="icon-plus"></i>
		        	{{ __('user.activate_user') }}&nbsp;
		        </a>
		        <a class="btn btn-xs btn-danger" href="{{url('user/'.$user->id.'/delete-user')}}">
		        	<i class="icon-remove"></i>
		        </a>
		    </div>
	      </div>
	      <div class="media-body">
	        <div><a href="#" class="h5">{{ $user->username }}</a></div>
	        <small class="text-muted">{{ $user->email }}</small>
	      </div>
	    </div>
	  </li>
	  @endforeach
	</ul>
</section>
@endif