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
<div class="container-fluid">
	<div class="row">
		@foreach ($current_event->productCategories()->get() as $category)
		<?php $count = 0; ?>
		<?php $count += $category->sortsize; ?>
		<div class="col-md-{{ $category->sortsize }}">
		<section class="panel">
			<header class="panel-heading">
				{{ $category->name }}
			</header>
			<ul class="list-group">
			  @foreach($category->products()->get() as $product)
			  <li class="list-group-item">
			    <div class="media">
			      <div class="pull-right m-t-small">
			        <a href="{{ url('/partner/shop/cart/'.$product->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-shopping-cart"></i> Legg i handlevogn</a>
			      </div>
			      <div class="pull-right m-t-small" style="text-align: right">
			      	{{ Format::price($product->price()) }}<br />
			      	<small class="text-muted">{{ $product->unit }}</small>
			      </div>
			      <div class="media-body">
			        <div>{{ $product->name }}</div>
			        <small class="text-muted">
			        	{{ $product->description }}
			        	@if($product->stock() > 0)
						<span class="badge bg-success">På lager: {{ $product->stock() }} stk</span>
						@endif
			        </small>
			      </div>
			    </div>
			  </li>
			  @endforeach
			</ul>
		</section>
	</div>
	@if($count == 12)
	</div>
	<div class="row">
	<?php $count=0; ?>
	@endif
	@endforeach