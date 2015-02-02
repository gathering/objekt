<form method="post" class="form-horizontal" action="{{ url('partner/accreditation/add') }}">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	     <h4 class="modal-title">{{ __('partner.error_modal.heading') }}</h4>
	</div>
	<div class="modal-body">
		<div class="alert alert-danger" role="alert">{{ $error }}</div>
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('partner.modal.close') }}</button>
	</div>
</form>
  