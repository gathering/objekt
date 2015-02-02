<form method="post" class="form-horizontal" action="{{ url('partner/accreditation/edit/'.$person->id) }}">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	     <h4 class="modal-title">{{ __('partner.accreditation.edit_modal.heading') }}</h4>
	</div>
	<div class="modal-body">
	   <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.firstname') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="firstname" tabindex="1" value="{{ $person->firstname }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.surname') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="surname" tabindex="2" value="{{ $person->surname }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.phone') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="phone" tabindex="3" value="{{ $person->phone }}"  class="form-control" autocomplete="off">
	      </div>
	    </div>
		<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="email" tabindex="4" value="{{ $person->email }}"  class="form-control" autocomplete="off">
	      </div>
	    </div>
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('partner.modal.close') }}</button>
	    <button type="submit" class="btn btn-primary">{{ __('partner.accreditation.edit_modal.button') }}</button>
	</div>
</form>
  