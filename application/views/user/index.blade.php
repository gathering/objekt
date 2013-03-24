<div class="grid-24">
<a href="{{ url('users/add') }}" style="float: right;" class="btn btn-small btn-primary">{{ __('user.add_new_user') }}</a><br /><br />
	<div class="widget widget-table">
						
		<div class="widget-header">
			<span class="icon-list"></span>
			<h3 class="icon chart">{{ __('common.index.users') }}</h3>		
		</div>

		<div class="widget-content">
			
			<table class="table table-bordered table-striped data-table">
				<thead>
					<tr>
						<th></th>
						<th>{{ __('user.id') }}</th>
						<th>{{ __('user.username') }}</th>
						<th>{{ __('user.email') }}</th>
						<th>{{ __('user.role') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach (User::all() as $user)
					<tr>
						<td><img src="http://www.gravatar.com/avatar/{{ md5( strtolower( trim( $user->email ) ) ) }}&s=40"></td>
						<td>{{ $user->id }}</td>
						<td>{{ $user->username }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ __('user.roles.'.strtolower($user->role->name)) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

</div>