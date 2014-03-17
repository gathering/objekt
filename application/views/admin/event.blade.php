@section('styles')
<link rel="stylesheet" href="{{ asset('css/datepicker3.css') }}">
@endsection
@section('scripts')
<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/locales/bootstrap-datepicker.nb.js') }}" charset="UTF-8"></script>
<script>
	$( document ).ready(function() {
		$('#date').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: true,
    		language: "nb"
		});

		$('#tags').on( "change", function($item) {
			console.log($(this).pillbox().items());
		});
	});
</script>
@endsection
<section class="panel">
	<header class="panel-heading text-right">
		<ul class="nav nav-tabs pull-left">
			<li class="active">
				<a href="#general_settings" data-toggle="tab">
					<i class="icon-cog icon-large text-default"></i>{{ __('admin.general_settings') }}
				</a>
			</li>
			<li>
				<a href="#technical_settings" data-toggle="tab">
					<i class="icon-hdd icon-large text-default"></i>{{ __('admin.technical_settings') }}
				</a>
			</li>
		</ul>
		<span class="hidden-sm">{{ sprintf(__('admin.settings_for'), $event->name) }}</span>
	</header>
	<form class="form-horizontal" method="post">
		<div class="panel-body">
			<div class="tab-content">              
				<div class="tab-pane active in" id="general_settings">
					<div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.name') }}</label>
	                  <div class="col-lg-8">
	                    <input type="text" name="name" value="{{ $event->name }}" placeholder="{{ __('admin.placeholder.name') }}" class="form-control">
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.date') }}</label>
	                  <div class="col-lg-8">
	                    <input type="text" name="date" id="date" value="{{ $event->date }}" placeholder="{{ __('admin.placeholder.date') }}" class="form-control">
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.status') }}</label>
	                  <div class="col-lg-8">
	                  	<a href="{{ url('admin/event/'.$event->slug.'/deactivate') }}" onclick="return confirm('{{ __('admin.field.deactivate_message') }}');" class="btn btn-danger">{{ __('admin.field.deactivate') }}</a>
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.slug') }}</label>
	                  <div class="col-lg-8">
	                    <input type="text" name="slug" value="{{ $event->slug }}" placeholder="{{ __('admin.placeholder.slug') }}" class="form-control">
	                    <br />
	                    <div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.slug') }}
						</div>
	                  </div>
	                </div>
	                <hr />
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.features') }}</label>
	                  <div class="col-lg-8">
	                    <div class="checkbox">
							<label class="checkbox-custom">
								<input type="checkbox" name="features[mediabank]" checked="checked">
								<i class="icon-unchecked checked"></i>
								{{ __('admin.field.mediabank') }}
							</label>
						</div>
						<div class="checkbox">
							<label class="checkbox-custom">
								<input type="checkbox" name="features[profiles]" checked="checked">
								<i class="icon-unchecked checked"></i>
								{{ __('admin.field.profiles') }}
							</label>
						</div>
						<div class="checkbox">
							<label class="checkbox-custom">
								<input type="checkbox" name="features[accreditation]" checked="checked">
								<i class="icon-unchecked checked"></i>
								{{ __('admin.field.accreditation') }}
							</label>
						</div>
	                  </div>
	                </div>
	                <hr />
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.tags') }}</label>
	                  <div class="col-lg-8">
	                	<div id="tags" class="pillbox clearfix m-b">
							<ul>
								<input type="text" placeholder="{{ __('admin.placeholder.tags') }}">
							</ul>
						</div>
						<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.tags') }}
						</div>
	                  </div>
	                </div>
				</div>
				<div class="tab-pane in" id="technical_settings">
					<div class="alert alert-info">
						<i class="icon-info-sign icon-large"></i>
						{{ sprintf(__('admin.description.printing'), GoogleCloudPrint::$G_Email, GoogleCloudPrint::$G_Pass) }}
					</div>
					<div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.printer') }}</label>
	                  <div class="col-lg-8">
						<select name="printer" class="form-control">
						<option value="0">{{ __('admin.field.no_printer') }}</option>
						@foreach($printers as $printer)
						<option value="{{ $printer['id'] }}">{{ $printer['displayname'] }}</option>
						@endforeach
						</select>
	                  </div>
	                </div>
				</div>
			</div>
		</div>
		<div class="form-group" style="margin-bottom: 40px;">
          <div class="col-lg-9 col-lg-offset-3">                      
            <a href="{{ url('admin/events') }}" class="btn btn-white">{{ __('admin.field.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('admin.field.save_changes') }}</button>
          </div>
        </div>
	</form>
</section>