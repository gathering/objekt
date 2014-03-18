<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<img src="{{ asset('images/pushover.png') }}" alt="" />    <br /><br />
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.pushover_api') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="pushover_key" value="{{ Auth::user()->pushover_key }}" tabindex="1" placeholder="{{ __('user.pushover_placeholder') }}…" class="form-control" autocomplete="off">
	      	<br />
          	<div class="alert alert-info">
				<i class="icon-info-sign icon-large"></i>
				{{ __('user.pushover_description') }}
			</div>
	      </div>
	    </div>

	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">{{ __('admin.field.save_changes') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>