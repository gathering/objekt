<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">
	  	<div class="form-group">
		  <label class="col-lg-3 control-label">{{ __('user.firstname') }}</label>
		  <div class="col-lg-8">
		    <input type="text" name="firstname" value="{{ $user->firstname }}" tabindex="1" placeholder="{{ __('profile.placeholder.firstname') }}" class="form-control" autocomplete="off">
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-lg-3 control-label">{{ __('user.surname') }}</label>
		  <div class="col-lg-8">
		    <input type="text" name="surname" value="{{ $user->surname }}" tabindex="2" placeholder="{{ __('profile.placeholder.surname') }}" class="form-control" autocomplete="off">
		    <div class="line line-dashed m-t-large"></div>
		  </div>
		</div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" tabindex="1" name="email" value="{{ $user->email }}" placeholder="{{ __('user.placeholder.email') }}" class="form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.password') }}</label>
	      <div class="col-lg-8">
	        <input type="password" tabindex="2" name="password" placeholder="{{ __('user.placeholder.password') }}" class="form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.password2') }}</label>
	      <div class="col-lg-8">
	        <input type="password" tabindex="3" name="password2" placeholder="{{ __('user.placeholder.password2') }}" class="form-control">
	        <div class="line line-dashed m-t-large"></div>
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.phone') }}</label>
	      <div class="col-lg-8">
	        <input type="text" tabindex="4" name="phone" value="{{ $user->phone }}" placeholder="{{ __('user.placeholder.phone') }}" class="form-control"><br />
	        <div class="alert alert-info">
      {{ __('profile.phone_news') }}
    </div>
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" tabindex="5" class="btn btn-primary">{{ __('user.edit_user') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>