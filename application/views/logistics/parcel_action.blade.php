@layout('logistics.template')
@section('content')
<section class="panel">
	<div class="wizard clearfix">
	  <ul class="steps">
	    <li><span class="badge">1</span>{{ __('logistics.parcel_type') }}</li>
	    <li><span class="badge">2</span><span class="name">{{ __('logistics.parcel') }}</span></li>
	    <li class="active"><span class="badge badge-info">3</span>{{ __('logistics.done') }}</li>
	  </ul>
	</div>
	<div class="step-content">
		<div class="step-pane active">
			<div class="alert alert-info">
				<i class="fa fa-info icon-large"></i>
				{{ sprintf(__('logistics.parcel_placed_on_storage'), $parcel->name, $storage->name) }}
			</div>
			<a href="{{ url('logistics/'.$storage->slug.'/add_parcel') }}" tabindex="1" class="btn btn-primary">
		        <i class="fa fa-plus"></i> {{ __('logistics.add_parcel') }}
		    </a>
			<a href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id) }}" tabindex="2" class="btn btn-info">
				<i class="fa fa-info"></i> {{ __('logistics.parcel_info') }}
			</a>
			<a href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/dispens') }}" tabindex="3" class="btn btn-warning">
				<i class="fa fa-hand-o-right"></i> {{ __('logistics.dispens') }}
			</a>
		</div>
	</div>
</section>
@endsection