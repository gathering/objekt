@section('top_buttons')
@if (Auth::user()->can("add_users"))
<li>
	<div class="m-t-small">
		<a href="{{ url('users/add') }}" class="btn btn-sm btn-info"><i class="fa fw fa-plus"></i> {{ __('user.add_new_user') }}</a>
	</div>
</li>
@endif
@if (Auth::user()->can("manage_roles"))
<li>
	<div class="m-t-small">
		<a href="{{ url('users/roles') }}" class="btn btn-sm btn-info"><i class="fa fw fa-group"></i> {{ __('user.role_settings') }}</a>
	</div>
</li>
@endif
@endsection
<section class="panel">
	<header class="panel-heading bg bg-inverse">
		{{ __('user.admins') }}
	</header>
	<div class="list-group">
	@foreach (Role::where("name", "=", "superAdmin")->get() as $role)
	@foreach ($role->users()->get() as $user)
	<li class="list-group-item">
		<div class="media">
		  <span class="pull-left thumb-small"><img src="{{ $user->image() }}" class="img-rounded"></span>
		  <div class="pull-right m-t-small">
		  </div>
		  <div class="media-body">
		  	<div class="pull-right">
		    	<span class="label label-info"></span>
		    </div>
		    <div><a href="{{ url('user/'.$user->id.'/edit') }}" onclick="alert('Ved å lagre denne så fjerner du tilgangen til administratoren.')" class="h5">{{ $user->display_name() }}</a></div>
		    <small class="text-muted">{{ $user->email }}</small>
		  </div>
		</div>
	</li>
	@endforeach
	@endforeach
	</div>
</section>
@foreach($current_event->roles()->get() as $role)
<section class="panel">
	<header class="panel-heading bg bg-inverse">
		{{ $role->name }}
	</header>
	<div class="list-group">
	@foreach ($role->users()->get() as $user)
	<li class="list-group-item">
		<div class="media">
		  <span class="pull-left thumb-small"><img src="{{ $user->image() }}" class="img-rounded"></span>
		  <div class="pull-right m-t-small">
	        @if (Auth::user()->can("delete_user"))
	        <a class="btn btn-xs btn-danger" href="{{url('user/'.$user->id.'/delete-user')}}">
	        	<i class="fa fa-times"></i> {{ __('user.delete') }}
	        </a>
	        @endif
		  </div>
		  <div class="media-body">
		  	<div class="pull-right">
		    	<span class="label label-info"></span>
		    </div>
		    <div><a href="{{ url('user/'.$user->id.'/edit') }}" class="h5">{{ $user->display_name() }}</a></div>
		    <small class="text-muted">{{ $user->email }}</small>
		  </div>
		</div>
	</li>
	@endforeach
	</div>
</section>
@endforeach
@if (Auth::user()->can("import_users"))
<section class="panel">
	<header class="panel-heading bg bg-default">
		{{ __('user.import_users') }}
	</header>
	<div class="list-group">
	@foreach (Role::non_current()->get() as $role)
	@foreach ($role->users()->get() as $user)
	<li class="list-group-item">
		<div class="media">
		  <span class="pull-left thumb-small"><img src="{{ $user->image() }}" class="img-rounded"></span>
		  <div class="pull-right m-t-small">
	        @if (Auth::user()->can("delete_user"))
	        <a class="btn btn-xs btn-danger" href="{{url('user/'.$user->id.'/delete-user')}}">
	        	<i class="fa fa-times"></i> {{ __('user.delete') }}
	        </a>
	        @endif
		  </div>
		  <div class="media-body">
		  	<div class="pull-right">
		    	<span class="label label-info"></span>
		    </div>
		    <div><a href="{{ url('user/'.$user->id.'/edit') }}" class="h5">{{ $user->display_name() }}</a></div>
		    <small class="text-muted">{{ $user->email }}</small>
		  </div>
		</div>
	</li>
	@endforeach
	@endforeach
	</div>
</section>
@endif