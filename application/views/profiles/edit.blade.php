<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<h3>{{ sprintf(__('user.edit_profile'), $profile->name) }}</h3>    
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.name') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="name" tabindex="1" value="{{ $profile->name }}" placeholder="{{ __('profile.placeholder.name') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.website') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="website" tabindex="2" value="{{ $profile->website }}" placeholder="{{ __('profile.placeholder.website') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
		<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.primary_email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="email" tabindex="3" value="{{ $profile->email }}" placeholder="{{ __('profile.placeholder.email') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">{{ __('user.save_changes') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>