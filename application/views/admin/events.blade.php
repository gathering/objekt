<h1>{{ __('admin.events') }}</h1>
<p>{{ __('admin.events_description') }}</p><br />
<div class="list-group m-b-small">
	@foreach(Events::all() as $event)
	<a href="{{ url('admin/event/'.$event->slug) }}" class="list-group-item">
		<i class="icon-chevron-right"></i>
		<span class="badge">{{ $event->date }}</span>
		{{ $event->name }}
	</a>
	@endforeach
</div>