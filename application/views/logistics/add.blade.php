<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<h3>{{ __('logistics.add') }}</h3>    
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.name') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="name" tabindex="1" placeholder="{{ __('logistics.placeholder.name') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">{{ __('logistics.add') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>