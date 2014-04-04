<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<h3>{{ __('accreditation.badge_details') }}</h3>    <br /><br />
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('accreditation.badge_id') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="badge_id" tabindex="1" placeholder="{{ __('accreditation.badge_id') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    @if (Auth::user()->can("accreditation_print_badge"))
	    <div class="form-group">
	      <div class="col-lg-8 col-lg-offset-3">
	        <div class="checkbox">
				<label class="checkbox-custom">
					<input type="checkbox" name="automatic" checked="checked" value="1">
					<i class="fa fa-check-square-o checked"></i>
					{{ __('accreditation.automatic') }}
				</label>
			</div>
	      </div>
	    </div>
	    @endif
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('accreditation.delivery_deadline') }}</label>
	      <div class="col-lg-4">
	        <input type="text" name="date" tabindex="2" placeholder="{{ date("Y-m-d") }}" class="form-control" autocomplete="off">
	      </div>
	      <div class="col-lg-4">
	      	<input type="text" name="time" tabindex="3" placeholder="{{ date("H:i") }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group" style="margin-bottom: 40px;">
          <div class="col-lg-9 col-lg-offset-3">                      
            <button type="submit" class="btn btn-primary">{{ __('accreditation.accredit') }}</button>
          </div>
        </div>
      </form>
    </div>
</section>
