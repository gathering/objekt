<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">
	  	<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.name') }}</label>
	      <div class="col-lg-8">
	        <input type="text" tabindex="1" name="name" {{ $role->static == '1' ? 'disabled="disabled" ' : '' }}placeholder="{{ __('user.placeholder.role_name') }}" value="{{ $role->name }}" class="form-control">
	        <div class="line line-dashed m-t-large"></div>
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.permissions') }}</label>
	      <div class="col-lg-2">
	      	<? $i=0; ?>
	      	@foreach($role->allPermissions() as $permission)
	      	<? $i++; ?>
            <div class="checkbox">
				<label class="checkbox-custom">
					<input type="checkbox" name="permission[{{ $permission->id }}]" {{ $permission->access ? 'checked="checked"' : '' }}>
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
	     	@if($role->users()->count() < 1 && $role->static == '0')
	     	<a href="{{ url('/users/role/'.$role->id.'/delete') }}" class="btn btn-danger">{{ __('user.delete_role') }}</a>
	     	@endif
	        <button type="submit" tabindex="7" class="btn btn-primary">{{ __('user.edit_role') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>