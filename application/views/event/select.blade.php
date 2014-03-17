<h1>{{ __('event.select_event') }}</h1>
<p>
	{{ __('event.select_event_text') }}
</p>
<div class="list-group m-b-small">
	@foreach($events->get() as $event)
	<a href="/{{ $event->slug }}" class="list-group-item">
		<i class="icon-chevron-right"></i>
		<span class="badge">{{ $event->date }}</span>
		{{ $event->name }}
	</a>
	@endforeach
</div>