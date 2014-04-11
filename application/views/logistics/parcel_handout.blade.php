@layout('logistics.template')
@section('scripts')
<script>
$(function() {
	var owners = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  prefetch: '{{ url('logistics/owners.json') }}',
	  remote: '{{ url('logistics/owners') }}/%QUERY.json'
	});

	owners.initialize();
	owners.clearPrefetchCache();

	$('input[name=receiver]').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	},
	{
		name: 'receiver',
		displayKey: 'value',
		source: owners.ttAdapter(),
		templates: {
			suggestion: Handlebars.compile('<p><?='{'.'{'?>name}}</p>')
		}
	});

	$('#expected_back_datetime').datetimepicker({
		useSeconds: false,
		language: 'no',
		icons: {
			time: "fa fa-clock-o",
	        date: "fa fa-calendar",
	        up: "fa fa-arrow-up",
	        down: "fa fa-arrow-down"
	    }
	});
});
</script>
@endsection
@section('content')
<section class="panel">
	<header class="panel-heading bg-inverse">
	  <div class="h5">{{ sprintf(__('logistics.handingout'), $parcel->name) }}</div>
	</header>
	<div class="panel-body">
		<form method="post" class="form-horizontal">
			<div class="form-group">
		      <label class="col-lg-3 control-label">{{ __('logistics.receiver') }}</label>
		      <div class="col-lg-8">
		        <input type="text" name="receiver" tabindex="4" value="{{ Session::get('post')['receiver'] }}" placeholder="{{ __('logistics.placeholder.receiver') }}" class="form-control" autocomplete="off">
		      </div>
		    </div>
		    <div class="form-group">
		      <label class="col-lg-3 control-label">{{ __('logistics.expected_back') }}</label>
		      <div class="col-lg-8 input-group date" id="expected_back_datetime">
		        <input class="form-control" data-date-format="yyyy-mm-dd" type="text" name="expected_back" tabindex="4" value="{{ Session::get('post')['expected_back'] }}" placeholder="{{ __('logistics.placeholder.expected_back') }}" autocomplete="off">
		      	<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		      </div>
		    </div>
		    <div class="form-group">
			    <div class="col-lg-9 col-lg-offset-3">                      
		        	<a href="{{ url('/logistics/'.$storage->slug.'/'.$parcel->id) }}" class="btn btn-white">{{ __('admin.field.cancel') }}</a>
		        	<button type="submit" tabindex="7" class="btn btn-primary">{{ __('logistics.handout') }}</button>
		        </div>
		    </div>
		</form>
	</div>
</section>
@endsection