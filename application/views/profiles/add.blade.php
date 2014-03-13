<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post" data-validate="parsley" action="{{ url('profile/add') }}">  
	  	<h3>{{ __('user.add_new_profile') }}</h3>    
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.name') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="name" tabindex="1" placeholder="{{ __('profile.placeholder.name') }}" class="bg-focus form-control parsley-validated" data-required="true" data-type="email" data-cip-id="cIPJQ342845640" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.website') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="website" tabindex="2" placeholder="{{ __('profile.placeholder.website') }}." class="bg-focus form-control parsley-validated" data-required="true" data-type="email" data-cip-id="cIPJQ342845640" autocomplete="off">
	      </div>
	    </div>
		<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.primary_email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="email" tabindex="3" placeholder="{{ __('profile.placeholder.email') }}" class="bg-focus form-control parsley-validated" data-required="true" data-type="email" data-cip-id="cIPJQ342845640" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">Legg til ny bruker</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>