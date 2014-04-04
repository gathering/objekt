<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">
	  	<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.name') }}</label>
	      <div class="col-lg-8">
	        <input type="text" tabindex="1" name="name" placeholder="{{ __('user.placeholder.role_name') }}" class="form-control">
	        <div class="line line-dashed m-t-large"></div>
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.permissions') }}</label>
	      <div class="col-lg-2">
	      	<? $i=0; ?>
	      	@foreach(Permission::all() as $permission)
	      	<? $i++; ?>
            <div class="checkbox">
				<label class="checkbox-custom">
					<input type="checkbox" name="permission[{{ $permission->id }}]">
					<i class="fa fa-check-square-o checked"></i>
					{{ __('user.permission.'.$permission->name) }}
				</label>
			</div>
			@if($i == 6)
		</div>
		<div class="col-lg-2">
			<? $i=0; ?>
			@endif
			@endforeach
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">
	     	<div class="line line-dashed m-t-large"></div><br /><br />
	        <button type="submit" tabindex="7" class="btn btn-primary">{{ __('user.add_role') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>