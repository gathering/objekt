<form method="post" class="form-horizontal" action="{{ url('partner/accreditation/promote/'.$person->id) }}">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	     <h4 class="modal-title">{{ __('partner.accreditation.promote_modal.heading') }}</h4>
	</div>
	<div class="modal-body">
	   <p>
	   	{{ __('partner.accreditation.promote_modal.description') }}
	   </p>
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('partner.modal.close') }}</button>
	    <button type="submit" class="btn btn-primary">{{ __('partner.accreditation.promote_modal.button') }}</button>
	</div>
</form>
  