<section id="content">
	<section class="main padder">
		<a href="{{ url('sponsor/add') }}" class="btn btn-small btn-primary">{{ __('user.add_new_sponsor') }}</a><br /><br />
		
		<section class="panel">
			<ul class="list-group">
			  @foreach (Sponsor::all() as $sponsor)
			  <li class="list-group-item">
			    <div class="media">
			      <div class="media-body">
			        <div><a href="{{ $sponsor->url() }}" class="h5">{{ $sponsor->name }}</a></div>
			      </div>
			    </div>
			  </li>
			  @endforeach
			</ul>
		</section>
	</section>
</section>