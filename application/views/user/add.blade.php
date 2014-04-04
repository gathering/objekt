<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.username') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="username" tabindex="1" placeholder="{{ __('user.placeholder.username') }}" class="form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.name') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="name" tabindex="2" placeholder="{{ __('user.placeholder.name') }}" class="form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.role') }}</label>
	      <div class="col-lg-4">
	        <select name="account" tabindex="3" class="form-control">
	          @foreach ($current_event->roles()->get() as $role)
			  <option value="{{ $role->id }}">{{ $role->name }}</option>
			  @endforeach
	        </select>
	        <div class="line line-dashed m-t-large"></div>
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.password') }}</label>
	      <div class="col-lg-8">
	        <input type="password" tabindex="4" name="password" placeholder="{{ __('user.placeholder.password') }}" class="form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.password2') }}</label>
	      <div class="col-lg-8">
	        <input type="password" tabindex="5" name="password2" placeholder="{{ __('user.placeholder.password2') }}" class="form-control">
	        <div class="line line-dashed m-t-large"></div>
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" tabindex="6" name="email" placeholder="{{ __('user.placeholder.email') }}" class="form-control">
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" tabindex="7" class="btn btn-primary">{{ __('user.add_new_user') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>