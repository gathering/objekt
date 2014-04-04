@section('top_buttons')
<li>
	<div class="m-t-small">
		<a href="{{ url('users/roles/add') }}" class="btn btn-sm btn-info"><i class="fa fw fa-plus"></i> {{ __('user.add_role') }}</a>
	</div>
</li>
@endsection
<section class="panel">
	<header class="panel-heading bg bg-inverse">
		{{ __('user.roles') }}
	</header>
	<div class="list-group">
	@foreach ($current_event->roles()->get() as $role)
	<a href="{{ url('users/role/'.$role->id) }}" class="list-group-item">
      <span class="badge">{{ $role->users()->count() }}</span>
      {{ $role->name }}
    </a>
	@endforeach
	</div>
</section>