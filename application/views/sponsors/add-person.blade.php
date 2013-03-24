<div class="grid-24">
	<form method="post" class="form uniformForm">
		<div class="widget">
			
			<div class="widget-header">
				<span class="icon-article"></span>
				<h3>{{ isset($person) ? __('user.add_child') : __('sponsor.add_personel') }}</h3>
			</div> <!-- .widget-header -->
			
			<div class="widget-content">
			
				<div class="field-group">
					<label>{{ __('user.details') }}</label>

					<div class="field">
						<input type="text" tabindex="1" name="firstname" autocomplete="off" id="firstname">			
						<label for="firstname">{{ __('user.firstname') }}</label>
					</div>
					<div class="field">
						<input type="text" tabindex="2" name="surname" autocomplete="off" id="surname">			
						<label for="surname">{{ __('user.surname') }}</label>
					</div>
				</div> <!-- .field-group -->
				<div class="field-group">
					<div class="field">
						<input type="text" tabindex="3" name="phone" autocomplete="off" id="phone">			
						<label for="phone">{{ __('user.phone') }}</label>
					</div>
					<div class="field">
						<input type="text" tabindex="4" name="email" autocomplete="off" id="email">			
						<label for="email">{{ __('user.email') }}</label>
					</div>
				</div> <!-- .field-group -->
				<div class="field-group">		
					<label for="message">{{ __('user.notes') }}</label>

					<div class="field">
						<textarea name="note" tabindex="5" id="note" rows="5" cols="50"></textarea>
					</div>		
				</div>
				<div class="field-group">
					@if (isset($sponsor))
					<div class="field" style="width: 200px;">
						<b>Tilknyttet</b><br />
						<label>{{ $sponsor->name }}{{ isset($person) ? " > ".$person->firstname." ".$person->surname : "" }}</label>
					</div>
					@else
					<div class="field-group">
						<div class="field">
							<select name="sponsor" tabindex="6" id="sponsor">
								<option>{{ __('sponsor.choose_sponsor') }}</option>
								@foreach (Sponsor::all() as $sponsor)
								<option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
								@endforeach
							</select>
							<label for="sponsor">{{ __('sponsor.choose_sponsor2') }}</label>
						</div>		
					</div> <!-- .field-group -->
					@endif
					@if (!isset($person))
					<div class="field">
						<input type="checkbox" name="contact_person" id="contact_person" value="1" />
						<label for="contact_person">{{ __('sponsor.contactperson') }}</label>
					</div>
					@endif
				</div>

				<input type="submit" class="btn btn-small btn-primary" tabindex="100" value="{{ isset($person) ? __('user.add_child') : __('user.add_new_sponsor') }}" />
			
			</div> <!-- .widget-content -->
			
		</div>
	</form>
</div>