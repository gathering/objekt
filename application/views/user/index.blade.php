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
		  	<div class="btn-group">
		  		@if (Auth::user()->can("reset_user_password"))
		         <a class="btn btn-xs btn-primary" onclick="alert('Denne funksjonen skal erstattes med brukerstyrt restting av passord. Derfor ser den forferdelig ut.')" href="{{url('user/'.$user->id.'/reset-password')}}">
		         	<i class="fa fa-key"></i>
		         	{{ __('user.reset_password') }}&nbsp;
		         </a>
		        @endif
		        @if (Auth::user()->can("delete_user"))
		        <a class="btn btn-xs btn-danger" href="{{url('user/'.$user->id.'/delete-user')}}">
		        	<i class="fa fa-times"></i>
		        </a>
		        @endif
		    </div>
		  </div>
		  <div class="media-body">
		  	<div class="pull-right">
		    	<span class="label label-info"></span>
		    </div>
		    <div><a href="#" class="h5">{{ $user->display_name() }}</a></div>
		    <small class="text-muted">{{ $user->email }}</small>
		  </div>
		</div>
	</li>
	@endforeach
	</div>
</section>
@endforeach