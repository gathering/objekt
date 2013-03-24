<div class="grid-24">
	<form method="post" class="form uniformForm" action="{{ url('users/add') }}">
		<div class="widget">
			
			<div class="widget-header">
				<span class="icon-article"></span>
				<h3>{{ __('user.add_new_user') }}</h3>
			</div> <!-- .widget-header -->
			
			<div class="widget-content">
			
				<div class="field-group">
					<label>{{ __('user.details') }}</label>

					<div class="field">
						<input type="text" name="username" autocomplete="off" id="username">			
						<label for="username">{{ __('user.username') }}</label>
					</div>
					@if (Auth::user()->is("superSponsorAdmin"))
					<div class="field">
						<select name="role" id="role">
							@foreach (Role::all() as $role)
							<option value="{{ $role->id }}">{{ __('user.roles.'. strtolower($role->name)) }}
							@endforeach
						</select>
						<label for="role">{{ __('user.role') }}</label>
					</div>
					@endif
					<br />
					<div class="field">
						<input type="password" name="password" autocomplete="off" id="password">			
						<label for="password">{{ __('user.password') }}</label>
					</div>
					<div class="field">
						<input type="password" name="password2" autocomplete="off" id="password2">			
						<label for="user_password">Gidder du å repetere?</label>
					</div>
				</div> <!-- .field-group -->

				<div class="field-group">
					<label>{{ __('user.contact') }}</label>

					<div class="field">
						<input type="text" name="email" id="email" autocomplete="off" placeholder="{{ __('user.email_placeholder') }}">			
						<label for="email">{{ __('user.email') }}</label>
					</div>
				</div>

				<input type="submit" class="btn btn-small btn-primary" value="{{ __('user.add_new_user') }}" />
			
			</div> <!-- .widget-content -->
			
		</div>
	</form>
</div>