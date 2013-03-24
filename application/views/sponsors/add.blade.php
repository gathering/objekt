<div class="grid-24">
	<form method="post" class="form uniformForm" action="{{ url('sponsor/add') }}">
		<div class="widget">
			
			<div class="widget-header">
				<span class="icon-article"></span>
				<h3>{{ __('user.add_new_sponsor') }}</h3>
			</div> <!-- .widget-header -->
			
			<div class="widget-content">
			
				<div class="field-group">
					<label>{{ __('user.details') }}</label>

					<div class="field">
						<input type="text" tabindex="1" name="name" autocomplete="off" id="name">			
						<label for="name">{{ __('user.name') }}</label>
					</div>
					<div class="field">
						<input type="text" tabindex="2" name="website" autocomplete="off" id="website">			
						<label for="website">{{ __('user.website') }}</label>
					</div>
					<div class="field">
						<input type="text" tabindex="3" name="email" autocomplete="off" id="email">			
						<label for="email">{{ __('user.primary_email') }}</label>
					</div>
				</div> <!-- .field-group -->

				<input type="submit" class="btn btn-small btn-primary" value="{{ __('user.add_new_sponsor') }}" />
			
			</div> <!-- .widget-content -->
			
		</div>
	</form>
</div>