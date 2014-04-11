@layout('logistics.template')
@section('scripts')
<script>
$(function() {
	$(document).on('click', '[data-wizard]', function (e) {
		var $this   = $(this), href;
	    var $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, ''));
	    var option = $this.data('wizard');
	    var item = $target.wizard('selectedItem');
	    var $step = $(this).closest('.step-content').find('.step-pane:eq(' + (item.step-1) + ')');

	    $target.wizard(option);
	    var activeStep = (option=="next") ? (item.step+1) : (item.step-1);
	    var prev = ($(this).hasClass('btn-prev') && $(this)) || $(this).prev();
	    var next = ($(this).hasClass('btn-next') && $(this)) || $(this).next();
	    prev.attr('disabled', (activeStep == 1) ? true : false);
	    next.attr('disabled', (activeStep == 1 || activeStep == 3) ? true : false);
	});

	$("button[name=parcel_single],button[name=parcel_multi],button[name=parcel_bulk],button[name=parcel_consumable]").on('click', function(){
		var name = $(this).attr('name');
		$("#parcel_type").find('.name').html($(this).text());
		$("#parcel_type").attr("data-target", name);
		$(".wizard").wizard('next');
		$("#type").removeClass('active');
		$("#" + name).addClass('active');
		$(".actions").removeClass('hide');
		$(".btn-prev").attr('disabled', false);
		$(".btn-next").attr('disabled', false);
		$("#" + name).find('input:first').focus();
	});

	$('.tags').on( "change click removed added", function($item) {
		var items = $(this).pillbox('items');
		var items_formated = new Array();

		$.each(items, function( index, data ) {
			items_formated.push(data.text);
		});

		items_formated.join(",");
		$(this).find(".tags_field").val(items_formated);
	});

	var owners = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  prefetch: '{{ url('logistics/owners.json') }}',
	  remote: '{{ url('logistics/owners') }}/%QUERY.json'
	});

	owners.initialize();
	owners.clearPrefetchCache();

	$('input[name=owner]').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	},
	{
		name: 'owner',
		displayKey: 'value',
		source: owners.ttAdapter(),
		templates: {
			suggestion: Handlebars.compile('<p><?='{'.'{'?>name}}</p>')
		}
	});
});
</script>
@endsection
@section('content')
<section class="panel">
	<div class="wizard clearfix" id="form-wizard">
	  <ul class="steps">
	    <li data-target="#type" class="active"><span class="badge badge-info">1</span>{{ __('logistics.parcel_type') }}</li>
	    <li data-target="" id="parcel_type" class=""><span class="badge">2</span><span class="name">{{ __('logistics.parcel') }}</span></li>
	    <li data-target="#finish"><span class="badge">3</span>{{ __('logistics.done') }}</li>
	  </ul>
	</div>
	<div class="step-content">
	    <div class="step-pane active" id="type">
	    	<button type="button" name="parcel_single" class="btn btn-info"><i class="fa fa-square"></i> {{ __('logistics.parcel_single') }}</button>
	    	<button type="button" name="parcel_multi" class="btn btn-info"><i class="fa fa-sitemap"></i> {{ __('logistics.parcel_multi') }}</button>
	    	<button type="button" name="parcel_bulk" class="btn btn-info"><i class="fa fa-list-ol"></i> {{ __('logistics.parcel_bulk') }}</button>
	    	<button type="button" name="parcel_consumable" class="btn btn-info"><i class="fa fa-pencil-square"></i> {{ __('logistics.parcel_consumable') }}</button>
	    </div>
	    <div class="step-pane" id="parcel_single">
	    	<form id="form" class="form-horizontal" method="post" action="{{ url('logistics/'.$storage->slug.'/add_parcel/single') }}">
		    	<div class="form-group">
			      <label class="col-lg-3 control-label">{{ __('logistics.parcel_name') }}</label>
			      <div class="col-lg-8">
			        <input type="text" name="name" tabindex="1" value="{{ Session::get('post')['name'] }}" placeholder="{{ __('logistics.placeholder.parcel_name') }}" class="form-control" autocomplete="off">
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="col-lg-3 control-label">{{ __('logistics.description') }}</label>
			      <div class="col-lg-8">
			        <input type="text" name="description" value="{{ Session::get('post')['description'] }}" tabindex="2" placeholder="{{ __('logistics.placeholder.description') }}" class="form-control" autocomplete="off">
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="col-lg-3 control-label">{{ __('logistics.comment') }}</label>
			      <div class="col-lg-8">
			      	<textarea name="comment" class="form-control" tabindex="3" placeholder="{{ __('logistics.placeholder.comment') }}">{{ Session::get('post')['comment'] }}</textarea>
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="col-lg-3 control-label">{{ __('logistics.owner') }}</label>
			      <div class="col-lg-8">
			        <input type="text" name="owner" tabindex="4" value="{{ Session::get('post')['owner'] }}" placeholder="{{ __('logistics.placeholder.owner') }}" class="form-control" autocomplete="off">
			      </div>
			    </div>
			    <hr />
			    <div class="form-group">
	              <label class="col-lg-3 control-label">{{ __('logistics.tags') }}</label>
	              <div class="col-lg-8">
	            	<div class="pillbox clearfix m-b tags">
						<ul>
							<input type="text" tabindex="5" placeholder="{{ __('admin.placeholder.tags') }}">
						</ul>
						<input type="hidden" name="tags" class="tags_field" />
					</div>
	              </div>
	            </div>
	            <hr />
	            <div class="form-group">
			      <label class="col-lg-3 control-label">{{ __('logistics.serialnumber') }}</label>
			      <div class="col-lg-8">
			        <input type="text" name="serialnumber" value="{{ Session::get('post')['serialnumber'] }}" tabindex="6" placeholder="{{ __('logistics.placeholder.serialnumber') }}" class="form-control" autocomplete="off">
			      </div>
			    </div>
			    <div class="form-group">
				    <div class="col-lg-9 col-lg-offset-3">                      
			        	<a href="{{ url('/logistics/'.$storage->slug.'/add_parcel') }}" class="btn btn-white">{{ __('admin.field.cancel') }}</a>
			        	<button type="submit" tabindex="7" class="btn btn-primary">{{ __('logistics.add_parcel') }}</button>
			        </div>
			    </div>
			</form>
	    </div>
	    <div class="step-pane" id="parcel_multi"><img src="{{ asset('img/not_done_yet_small.png') }}" alt="" /></div>
	    <div class="step-pane" id="parcel_bulk"><img src="{{ asset('img/not_done_yet_small.png') }}" alt="" /></div>
	    <div class="step-pane" id="parcel_consumable"><img src="{{ asset('img/not_done_yet_small.png') }}" alt="" /></div>
	  </form>
	</div>
</section>
@endsection