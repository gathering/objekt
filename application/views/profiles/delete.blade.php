<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<h3>{{ sprintf(__('profile.delete'), $profile->name) }}</h3><br /><br />
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('profile.delete_confirm') }}</label>
	      <div class="col-lg-8">
	        <input type="password" name="password" placeholder="{{ __('profile.placeholder.delete') }}" tabindex="1" class="form-control" autocomplete="off">
	        <br />
	      	<div class="alert alert-info">
				<i class="icon-info-sign icon-large"></i>
				{{ sprintf(__('profile.description.delete'), $profile->name) }}
			</div>
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">
	        <button type="submit" class="btn btn-danger">{{ sprintf(__('profile.confirm_delete'), $profile->name) }}</button>
	      </div>
	    </div>
	   </form>
	</div>
</section>