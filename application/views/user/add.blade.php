<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="get" data-validate="parsley">      
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.username') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="username" tabindex="1" placeholder="{{ __('user.placeholder.username') }}" class="bg-focus form-control parsley-validated" data-required="true" data-type="email">
	      </div>
	    </div>
	    @if (Auth::user()->is("superSponsorAdmin"))
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.role') }}</label>
	      <div class="col-lg-4">
	        <select name="account" tabindex="2" class="form-control">
	          @foreach (Role::all() as $role)
			  <option value="{{ $role->id }}">{{ __('user.roles.'. strtolower($role->name)) }}
			  @endforeach
	        </select>
	        <div class="line line-dashed m-t-large"></div>
	      </div>
	    </div>
		@endif
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.password') }}</label>
	      <div class="col-lg-8">
	        <input type="password" tabindex="3" name="password" placeholder="{{ __('user.placeholder.password') }}" class="bg-focus form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.password2') }}</label>
	      <div class="col-lg-8">
	        <input type="password" tabindex="4" name="password2" placeholder="{{ __('user.placeholder.password2') }}" class="bg-focus form-control">
	        <div class="line line-dashed m-t-large"></div>
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" tabindex="5" name="email" placeholder="{{ __('user.placeholder.email') }}" class="bg-focus form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">{{ __('user.add_new_user') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>