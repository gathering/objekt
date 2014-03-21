
@section('styles')
<link rel="stylesheet" href="{{ asset('js/minicolors/jquery.minicolors.css') }}">
@endsection
@section('scripts')
<script src="{{ asset('js/minicolors/jquery.minicolors.min.js') }}"></script>
<script>
	$( document ).ready(function() {
		$('.minicolors').minicolors({
			control: 'wheel'
		});
	});
</script>
@endsection
<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post" enctype="multipart/form-data">  
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
	      <label class="col-lg-3 control-label">{{ __('user.logo') }}</label>
	      <div class="col-lg-8">
	        <input type="file" name="logo" class="form-control">
	        <br />
	      	<div class="alert alert-info">
				<i class="icon-info-sign icon-large"></i>
				{{ __('user.description.logo') }}
			</div>
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.color') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="color" tabindex="4" value="{{ $profile->color }}" placeholder="{{ __('profile.placeholder.color') }}" class="form-control minicolors" autocomplete="off">
	      	<br /><br />
	      	<div class="alert alert-info">
				<i class="icon-info-sign icon-large"></i>
				{{ __('user.description.color') }}
			</div>
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