@section('styles')
<link rel="stylesheet" href="{{ asset('js/select2/select2.css') }}">
@endsection
@section('scripts')
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script>
	$( document ).ready(function() {
		$('#date').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: true,
    		language: "nb"
		});

		$('#to_date').datepicker({
			format: "yyyy-mm-dd",
			todayBtn: true,
    		language: "nb"
		});

		$('#tags').on( "change click removed added", function($item) {
			var items = $(this).pillbox('items');
			var items_formated = new Array();

			$.each(items, function( index, data ) {
				items_formated.push(data.text);
			});

			items_formated.join(",");
			$("#tags_field").val(items_formated);
		});

		$("#aid_users").select2({
          data:{{ $users }},
          multiple: true,
          tokenSeparators: [",", " "]
      	});
	});
</script>
@endsection
<section class="panel">
	<header class="panel-heading text-right">
		<ul class="nav nav-tabs pull-left">
			<li class="active">
				<a href="#general_settings" data-toggle="tab">
					<i class="fa fa-cog icon-large text-default"></i> {{ __('admin.general_settings') }}
				</a>
			</li>
			<li>
				<a href="#technical_settings" data-toggle="tab">
					<i class="fa fa-hdd-o icon-large text-default"></i> {{ __('admin.technical_settings') }}
				</a>
			</li>
			<li>
				<a href="#files" data-toggle="tab">
					<i class="fa fa-file icon-large text-default"></i> {{ __('admin.files') }}
				</a>
			</li>
			@if($event->map())
	        <? $map = $event->map(); ?>
	        @if($map->pdf->converted == '1')
			<li>
				<a href="#map" data-toggle="tab">
					<i class="fa fa-map-marker icon-large text-default"></i> {{ __('admin.map') }}
				</a>
			</li>
			@endif
			@endif
			<li>
				<a href="#usage" data-toggle="tab">
					<i class="fa fa-flash icon-large text-default"></i> {{ __('admin.usage') }}
				</a>
			</li>
		</ul>
		<span class="hidden-sm">{{ sprintf(__('admin.settings_for'), $event->name) }}</span>
	</header>
	<form class="form-horizontal" method="post" enctype="multipart/form-data">
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
	                  <label class="col-lg-3 control-label">{{ __('admin.field.from_date') }}</label>
	                  <div class="col-lg-8">
	                    <input type="text" name="date" id="date" value="{{ $event->date }}" placeholder="{{ __('admin.placeholder.date') }}" class="form-control">
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.to_date') }}</label>
	                  <div class="col-lg-8">
	                    <input type="text" name="to_date" id="to_date" value="{{ $event->to_date }}" placeholder="{{ __('admin.placeholder.date') }}" class="form-control">
	                  	<br />
	                  	<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.date') }}
						</div>
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
	                  	@foreach($event->features() as $feature => $status)
	                    <div class="checkbox">
							<label class="checkbox-custom">
								<input type="checkbox" name="features[{{ $feature }}]" {{ $event->hasFeature($feature) ? 'checked="checked"' : '' }}>
								<i class="fa fa-check-square-o checked"></i>
								{{ __('admin.field.'.$feature) }}
							</label>
						</div>
						@endforeach
	                  </div>
	                </div>
	                <hr />
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.tags') }}</label>
	                  <div class="col-lg-8">
	                	<div id="tags" class="pillbox clearfix m-b">
							<ul>
								@foreach($event->tags() as $tag)
								<li class="label bg-default">{{ $tag }}</li>
								@endforeach
								<input type="text" placeholder="{{ __('admin.placeholder.tags') }}">
							</ul>
						</div>
						<input type="hidden" name="tags" id="tags_field" value="{{ $event->tags }}" />
						<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.tags') }}
						</div>
	                  </div>
	                </div>
	                <hr />
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.num_wristband') }}</label>
	                  <div class="col-lg-8">
	                    <input type="text" name="num_wristband" value="{{ $event->num_wristband }}" placeholder="{{ __('admin.placeholder.num_wristband') }}" class="form-control">
	                  </div>
	                </div>
	                <hr />
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('user.aid') }}</label>
	                  <div class="col-lg-8">
	                	<div class="m-b">
		                  <input type="hidden" name="aid_users" id="aid_users" style="width:100%" value="{{ $event->aid_users }}"/>
		                </div>
		                <div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.aid') }}
						</div>
	                  </div>
	                </div>
				</div>
				<div class="tab-pane in" id="technical_settings">
					<div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.badgeprinter') }}</label>
	                  <div class="col-lg-8">
						<select name="badgeprinter" class="form-control">
						<option {{ $event->badgeprinter == "0" || $event->badgeprinter == "" ? 'selected="selected"' : '' }} value="0">{{ __('admin.field.no_printer') }}</option>
						@foreach($printers as $printer)
						<option {{ $event->badgeprinter == $printer['id'] ? 'selected="selected"' : '' }} value="{{ $printer['id'] }}">{{ $printer['displayname'] }} (Status: {{ $printer['status'] }})</option>
						@endforeach
						</select>
						<br />
						<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ sprintf(__('admin.description.printing'), GoogleCloudPrint::$G_Email, GoogleCloudPrint::$G_Pass) }}
						</div>
	                  </div>
	                </div>
	                <hr />
	                <div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.deskprinter') }}</label>
	                  <div class="col-lg-8">
						<select name="deskprinter" class="form-control">
						<option {{ $event->deskprinter == "0" || $event->deskprinter == "" ? 'selected="selected"' : '' }} value="0">{{ __('admin.field.no_printer') }}</option>
						@foreach($printers as $printer)
						<option {{ $event->deskprinter == $printer['id'] ? 'selected="selected"' : '' }} value="{{ $printer['id'] }}">{{ $printer['displayname'] }}</option>
						@endforeach
						</select>
						<br />
						<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.deskprinter') }}
						</div>
	                  </div>
	                </div>
				</div>
				<div class="tab-pane in" id="files">
					<div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.welcomeletter') }}</label>
	                  <div class="col-lg-8">
	                    <input type="file" name="welcomeletter" class="form-control">
	                    <br />
	                    <div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.welcomeletter') }}
						</div>
	                  </div>
	                </div>
	                <div class="form-group">
	                	<div class="col-lg-8 col-lg-offset-3">
							<table class="table table-striped m-b-none text-small">
								<thead>
									<tr>
										<th colspan="2">{{ __('admin.filename') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($event->files("welcomeletter")->get() as $file)
									<tr>                    
										<td><a href="{{ $file->url }}" target="_blank">{{ $file->filename }}</a></td>
										<td style="width: 5%"><a href="{{ url('admin/event/'.$event->slug.'/delete_file/'.$file->id) }}" class="btn btn-xs">{{ __('admin.delete_file') }}</a></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="form-group">
	                  <label class="col-lg-3 control-label">{{ __('admin.field.map') }}</label>
	                  <div class="col-lg-8">
	                    <input type="file" name="map" class="form-control">
	                    <br />
	                    <div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>
							{{ __('admin.description.map') }}
						</div>
						<p>
	                    	@if(isset($map))
	                    	{{ __('admin.map_is_uploaded') }}
	                    	<a href="{{ url('admin/event/'.$event->slug.'/delete_file/'.$map->pdf->id) }}" class="btn btn-xs">{{ __('admin.delete_map') }}</a>
	                    	<a target="_blank" href="{{ $map->pdf->url }}" class="btn btn-xs btn-primary">{{ __('admin.show_map') }}</a>
	                    	<br />
	                    	@if($map->pdf->converted != '1')
	                    	<div class="alert alert-danger">
								<i class="icon-info-sign icon-large"></i>
								{{ __('admin.description.not_converted') }}
							</div>
	                    	@endif
	                    	@endif
	                    </p>
	                  </div>
	                </div>
				</div>
				@if(isset($map) && $map->pdf->converted == '1')
				<div class="tab-pane in" id="map">
					<img id="map_img" src="{{ $map->jpg->url }}" />
				</div>
				@endif
				<div class="tab-pane in" id="usage">
					<? $usage = $event->calculateMonths(); ?>
					<table class="table">
					<thead>
						<tr>
							<th width="60">{{ __('admin.qty') }}</th>
							<th>{{ __('admin.line_description') }}</th>
							<th width="140">{{ __('admin.unit_price') }}</th>
							<th width="90">{{ __('admin.total') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($usage['diskusage'] as $month => $result)
						<tr>
							<td>{{ $result['diskuse'] }}</td>
							<td>{{ sprintf(__('admin.s3_month'), $month) }}</td>
							<td>0.19 NOK</td>
							<td>{{ number_format($result['total'], 2, ".", " ") }} NOK</td>
						</tr>
						@endforeach
						@foreach($usage['requests'] as $month => $result)
						<tr>
							<td>5</td>
							<td>{{ sprintf(__('admin.requests_month'), $month) }}</td>
							<td>0.04 NOK</td>
							<td>{{ number_format($result['total'], 2, ".", " ") }} NOK</td>
						</tr>
						@endforeach
						<tr>
							<td colspan="3" class="text-right"><strong>{{ __('admin.sub_total') }}</strong></td>
							<td>{{ number_format($usage['subtotal'], 2, ".", " ") }}</td>
						</tr>
						<tr>
							<td colspan="3" class="text-right no-border"><strong>{{ __('admin.total') }}</strong></td>
							<td><strong>{{ number_format($usage['subtotal'], 2, ".", " ") }}</strong></td>
						</tr>
						<tr>
							<td colspan="4" class="no-border"><br><br></td>
						</tr>
						<tr>
							<td colspan="3" class="text-right no-border"><strong>{{ __('admin.total_diskuse') }}</strong></td>
							<td>{{ $usage['diskuse'] }}</td>
						</tr>
					</tbody>
					</table>
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