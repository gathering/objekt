@layout('logistics.template')
@section('top_buttons')
@if (Auth::user()->can("add_storages"))
<li>
  <div class="m-t-small">
    <a href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/edit') }}" class="btn btn-sm btn-info"><i class="fa fw fa-pencil"></i> {{ __('logistics.edit_parcel') }}</a>
  </div>
</li>
@endif
@endsection
@section('content')
<div class="row">
	<div class="col-lg-7">
		<h1><i class="fa fa-square"></i> {{ $parcel->name }}</h1>
	</div>
	<div class="col-lg-5">
		<section class="toolbar clearfix m-b pull-right">
			@if($parcel->current_status()->can_handout())
		    <a tabindex="2" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/handout') }}" class="btn btn-warning btn-circle">
		        <i class="fa fa-hand-o-right"></i> {{ __('logistics.handout') }}
		    </a>
		    @endif
		    @if($parcel->current_status()->can_receive())
		    <a tabindex="2" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/receive') }}" class="btn btn-warning btn-circle">
		        <i class="fa fa-hand-o-left"></i> {{ __('logistics.receive') }}
		    </a>
		    @endif
		    @if($parcel->current_status()->can_place_in_stock())
		    <a tabindex="2" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/place_in_stock') }}" class="btn btn-warning btn-circle">
		        <i class="fa fa-table"></i> {{ __('logistics.place_in_stock') }}
		    </a>
		    @endif
		    <a tabindex="3" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/relocate') }}" class="btn btn-info btn-circle">
		        <i class="fa fa-location-arrow"></i> {{ __('logistics.relocate') }}
		    </a>
		    <a tabindex="4" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/connect') }}" class="btn btn-info btn-circle">
		        <i class="fa fa-chain"></i> {{ __('logistics.connect') }}
		    </a>
		    <a tabindex="5" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/print') }}" class="btn btn-info btn-circle">
		        <i class="fa fa-print"></i> {{ __('logistics.print') }}
		    </a>
		    <a tabindex="5" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/transport') }}" class="btn btn-primary btn-circle">
		        <i class="fa fa-truck"></i> {{ __('logistics.transport') }}
		    </a>
		    <a tabindex="6" href="{{ url('logistics/'.$storage->slug.'/'.$parcel->id.'/maintain') }}" class="btn btn-danger btn-circle">
		        <i class="fa fa-wrench"></i> {{ __('logistics.maintain') }}
		    </a>
		</section>
	</div>
</div>
<div class="row">
	<div class="col-lg-5">
		<section class="panel">
			<header class="panel-heading bg-inverse">
			  <div class="text-center h5">{{ __('logistics.generic_information') }}</div>
			</header>
			<div class="panel-body">
				
				<div class="row">
					<div class="col-lg-2">
						<b>{{ __('logistics.current_status') }}</b>
					</div>
					<div class="col-lg-10">
						<? $log = $parcel->current_status(); ?>
						@if($log->status == "created")
							{{ __('logistics.status.created') }}
						@else
							{{ sprintf(__('logistics.status.'.$log->status), $log->receiver()) }}
							@if(strtotime($log->expected_back) > 0 && strtotime($log->expected_back) > time())
								<br />
								<div class="label bg-black" title="{{ Date::nice($log->expected_back) }}">
									{{ sprintf(__('logistics.status.expected_back'), Date::countdown($log->expected_back)) }}
								</div><br />
							@endif
						@endif
					</div>
					<br />
					<hr />
				</div>
				<div class="row">
					<div class="col-lg-2">
						<b>{{ __('logistics.description') }}</b>
					</div>
					<div class="col-lg-10">
						{{ $parcel->description }}
					</div>
					<br />
					<hr />
				</div>
				<div class="row">
					<div class="col-lg-2">
						<b>{{ __('logistics.comment') }}</b>
					</div>
					<div class="col-lg-10">
						{{ $parcel->comment }}
					</div>
					<br /><br />
				</div>
			</div>
		</section>
	</div>
	<div class="col-lg-7">
		<section class="panel">
			<header class="panel-heading bg-inverse">
			  <div class="text-center h5">{{ __('logistics.logg') }}</div>
			</header>
			<ul class="list-group">
				@foreach($parcel->logs()->order_by('id', 'desc')->get() as $log)
				<li class="list-group-item">
					<div class="media">
						<span class="pull-left thumb-small m-t-mini">
							<i class="fa {{ __('logistics.icons.'.$log->status) }} icon-xlarge text-default"></i>
						</span>
						<div class="media-body">
							<div>
								{{ sprintf(__('logistics.status.'.$log->status), $log->receiver()) }}
							</div>
							<small class="text-muted">
								{{ Date::nice($log->created_at) }}
								- {{ $log->user()->first()->display_name() }}
							</small>
						</div>
					</div>
				</li>
				@endforeach
				<li class="list-group-item">
					<div class="media">
						<span class="pull-left thumb-small m-t-mini">
							<i class="fa fa-asterisk icon-xlarge text-default"></i>
						</span>
						<div class="media-body">
							<div>{{ __('logistics.parcel_added') }}</div>
							<small class="text-muted">{{ Date::nice($parcel->created_at) }} - {{ $parcel->user()->first()->display_name() }}</small>
						</div>
					</div>
				</li>
				
			</ul>
		</section>
	</div>
</div>
@endsection