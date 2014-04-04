@section('top_buttons')
@if (Auth::user()->can("add_profile"))
<li>
	<div class="m-t-small">
		<a href="{{ url('profile/add') }}" class="btn btn-sm btn-info"><i class="fa fw fa-plus"></i> {{ __('user.add_new_profile') }}</a>
	</div>
</li>
@endif
@endsection
<section class="panel">
	<ul class="list-group">
	  @foreach (profile::all() as $profile)
	  <li class="list-group-item">
	    <div class="media">
	      <div class="media-body">
	        <div><a href="{{ $profile->url() }}" class="h5">{{ $profile->name }}</a></div>
	      </div>
	    </div>
	  </li>
	  @endforeach
	</ul>
</section>