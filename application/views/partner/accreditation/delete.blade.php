<form method="post" class="form-horizontal" action="{{ url('partner/accreditation/delete/'.$person->id) }}">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	     <h4 class="modal-title">{{ __('partner.accreditation.delete_modal.heading') }}</h4>
	</div>
	<div class="modal-body">
	   {{ __('partner.accreditation.delete_modal.description', array('name' => $person->firstname." ".$person->surname)) }}
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('partner.modal.close') }}</button>
	    <button type="submit" class="btn btn-danger">{{ __('partner.accreditation.delete_modal.button') }}</button>
	</div>
</form>
  