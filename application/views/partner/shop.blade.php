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
					<div style="font-size: 48px;">{{ $product->price }},-</div>
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