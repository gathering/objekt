@layout('logistics.template')
@section('content')
<section class="panel">
	<header class="panel-heading bg bg-inverse">
		{{ __('common.index.search') }}
	</header>
	<div class="list-group">
		@foreach ($results as $result)
		<a href="{{ $result->url }}" class="list-group-item bg-lighter">
			{{ $result->name }}
		</a>
		@endforeach
	</div>
	@if($pagination->num_pages > 1)
	<center>
		<ul class="pagination pagination-lg">
			@if($pagination->prev > 0)
			<li><a href="{{ url('logistics/search?search='.urlencode(Input::get('search')).'&page='.$pagination->prev) }}"><i class="fa fa-chevron-left"></i></a></li>
			@endif
			@for ($i = 1; $i <= $pagination->num_pages; $i++)
			<li {{ $pagination->current == $i ? 'class="active"' : '' }}><a href="{{ url('logistics/search?search='.urlencode(Input::get('search')).'&page='.$i) }}">{{ $i }}</a></li>
			@endfor
			@if($pagination->next > 0)
			<li><a href="{{ url('logistics/search?search='.urlencode(Input::get('search')).'&page='.$pagination->next) }}"><i class="fa fa-chevron-right"></i></a></li>
	    	@endif
	    </ul>
	</center>
	@endif
</section>
@endsection