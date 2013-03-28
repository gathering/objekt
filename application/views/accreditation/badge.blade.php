<div class="grid-16">
	<form class="form uniformForm" method="post">					
		<div class="widget">
			<div class="widget-header">
				<span class="icon-article"></span>
				<h3>{{ __('accreditation.existing_person') }}</h3>
			</div> <!-- .widget-header -->
			<div class="widget-content">
				<div class="field-group">
					<label>{{ __('accreditation.badge_details') }}</label>
					<div class="field-group inlineField">
						<div class="field">
							<input type="text" name="badge_id" id="badge_id" size="10" class="">			
							<label for="fname">{{ __('accreditation.badge_id') }}</label>
						</div>
						<div class="field">
							<input type="checkout" name="automatic" id="automatic" size="10" class="">			
							<label for="fname">{{ __('accreditation.automatic') }}</label>
						</div>
					</div>
					<div class="field-group inlineField">								
						<label for="datepicker">{{ __('accreditation.delivery_deadline') }}</label>
						
						<div class="field">
							<input type="text" name="date" placeholder="{{ date("Y-m-d") }}" id="datepicker" />				
						</div> <!-- .field -->
						<div class="field">kl. 
							<input type="text" name="time" size="10" placeholder="{{ date("H:i") }}" id="timepicker" />				
						</div> <!-- .field -->							
					</div> <!-- .field-group -->

					<input type="submit" class="btn btn-small btn-primary" tabindex="100" value="{{ __('accreditation.accredit') }}" />
				</div> <!-- .field-group -->
			</div>
		</div>					
	</form>			
</div> <!-- .grid -->
