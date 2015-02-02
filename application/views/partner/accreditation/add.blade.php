<form method="post" class="form-horizontal" action="{{ url('partner/accreditation/add') }}">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	     <h4 class="modal-title">{{ __('partner.accreditation.new_modal.heading') }}</h4>
	</div>
	<div class="modal-body">
	   <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.firstname') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="firstname" tabindex="1" placeholder="{{ __('profile.placeholder.firstname') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.surname') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="surname" tabindex="2" placeholder="{{ __('profile.placeholder.surname') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.phone') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="phone" tabindex="3" placeholder="{{ __('profile.placeholder.phone') }}"  class="form-control" autocomplete="off">
	      </div>
	    </div>
		<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="email" tabindex="4" placeholder="{{ __('profile.placeholder.email') }}"  class="form-control" autocomplete="off">
	      </div>
	    </div>
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('partner.modal.close') }}</button>
	    <button type="submit" class="btn btn-primary">{{ __('partner.accreditation.new_modal.button') }}</button>
	</div>
</form>
  