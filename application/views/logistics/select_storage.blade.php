@section('top_buttons')
@if (Auth::user()->can("add_storages"))
<li>
  <div class="m-t-small">
    <a href="{{ url('logistics/add') }}" class="btn btn-sm btn-info"><i class="fa fw fa-plus"></i> {{ __('logistics.add') }}</a>
  </div>
</li>
@endif
@endsection
<section class="panel">
  <header class="panel-heading bg bg-inverse">
    {{ __('logistics.select_storage') }}
  </header>
  <div class="list-group">
    @foreach(Storage::current() as $storage)
    <a href="{{ url('logistics/'.$storage->slug) }}" class="list-group-item bg-lighter">
      <span class="badge bg-success"> $storage->units()->count()</span>
      {{ $storage->name }}
    </a>
    @endforeach
  </div>
</section>