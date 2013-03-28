<div class="grid-24">
	<form method="post" class="form uniformForm">
		<div class="widget">
			
			<div class="widget-header">
				<span class="icon-article"></span>
				<h3>{{ __('user.reset_password') }}</h3>
			</div> <!-- .widget-header -->
			
			<div class="widget-content">
			
				<div class="field-group">
					<label>{{ __('user.details') }}</label>

					<div class="field">
						<input type="password" name="password" autocomplete="off" id="password">			
						<label for="password">{{ __('user.password') }}</label>
					</div>
					<div class="field">
						<input type="password" name="password2" autocomplete="off" id="password2">			
						<label for="user_password">Gidder du å repetere?</label>
					</div>
				</div> <!-- .field-group -->

				<input type="submit" class="btn btn-small btn-primary" value="{{ __('user.reset_password') }}" />
			
			</div> <!-- .widget-content -->
			
		</div>
	</form>
</div>