<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post" data-validate="parsley" action="{{ url('profile/add') }}">  
	  	<h3>{{ __('user.add_new_profile') }}</h3>    
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.name') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="name" tabindex="1" placeholder="{{ __('profile.placeholder.name') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.website') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="website" tabindex="2" placeholder="{{ __('profile.placeholder.website') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
		<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.primary_email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="email" tabindex="3" placeholder="{{ __('profile.placeholder.email') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">{{ __('user.add_new_profile') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>