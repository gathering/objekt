<section id="content">
	<section class="main padder">
		<a href="{{ url('profile/add') }}" class="btn btn-small btn-primary">{{ __('user.add_new_profile') }}</a><br /><br />
		
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
	</section>
</section>