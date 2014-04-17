<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post" data-validate="parsley" action="{{ url('memo') }}">  
	  	<h3>{{ __('memo.send_memo') }}</h3>    
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('memo.to') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="to" tabindex="1" placeholder="{{ __('memo.to_default') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('memo.content') }}</label>
	      <div class="col-lg-8">
	        <textarea name="content" class="form-control" style="height: 700px; "></textarea>
	      </div>
	    </div>
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">{{ __('memo.send_memo') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>