@if(count($cart->all()) > 0)
@section('afterNavigation')
<div class="padder hidden-xs" style="color: white; text-align: center;">
	<div style="margin-bottom: 10px; font-weight: bold;">Handlevogn</div>
	<ul class="nav">
		@foreach($cart->all() as $item)
		<li><span class="badge">{{ $item->quantity }}</span> {{ $item->name }}</li>
		@endforeach
	</ul><br />
	<a href="{{ url('partner/shop/checkout') }}" class="btn btn-white btn-xs">Gå til bekreftelse</a>
</div>
@endsection
@endif

@if(count($cart->all()) > 0)
<div class="visible-xs panel">
	<header class="panel-heading">Handlevogn</header>
	<ul class="list-group m-b-small">
		@foreach($cart->all() as $item)
		<li class="list-group-item">
			<span class="badge">x {{ $item->quantity }}</span>
			{{ $item->name }}
		</li>
		@endforeach
	</ul>
	<div class="panel-footer">
		<small class="col-xs-6">Endre antall eller fjerne? Gjør det i bekreftelsen.</small>
		<a href="{{ url('partner/shop/checkout') }}" class="btn btn-info pull-right">Gå til bekreftelse <i class="fa fa-arrow-right"></i></a>
		<div class="clear"></div>
	</div>
</div>
@endif

<div class="row">
	<?php $count=0; ?>
	@foreach ($current_event->products()->get() as $product)
	<?php $count += $product->sortsize; ?>
	<div class="col-lg-{{ $product->sortsize }}">
		<section class="panel">
			<header class="panel-heading">{{ $product->name }}</header>
			<div class="panel-body">
				{{ $product->description }}
				<br />
			</div>
			<div class="panel-footer">
				<div class="pull-right">
					<div style="font-size: 48px;">{{ $product->price() }},-</div>
					På lager: {{ $product->stock() }} stk
				</div>
				<a href="{{ url('/partner/shop/cart/'.$product->id) }}" class="btn btn-primary btn-lg pull-right" style="margin-right: 30px; margin-top: 20px;">Legg i handlevogn</a>
				<div class="clear"></div>
			</div>
		</section>
	</div>
	@if($count == 12)
</div>
<div class="row">
	<?php $count=0; ?>
	@endif
	@endforeach
</div>