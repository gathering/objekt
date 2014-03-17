@layout('profiles.person_profile_template')
@section('content')
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<h3>{{ __('sms.send_to_person') }}</h3>    
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('sms.to') }}</label>
	      <div class="col-lg-8">
	        <input type="text" value="{{ $person->firstname }} {{ $person->surname }} ({{ $person->phone }})" tabindex="1" readonly placeholder="{{ __('sms.placeholder.to') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('sms.content') }}</label>
	      <div class="col-lg-8">
	        <textarea placeholder="{{ __('sms.placeholder.content') }}" tabindex="2" name="content" rows="5" class="form-control"></textarea>
	      </div>
	    </div>
	    @if (Auth::user()->is("superSponsorAdmin"))
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('sms.from') }}</label>
	      <div class="col-lg-8">
	        <input type="text" tabindex="3" value="OBJEKT" placeholder="{{ __('sms.placeholder.from') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    @endif
	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">{{ __('sms.submit') }}</button>
	      </div>
	    </div>
	  </form>
	</div>
@endsection