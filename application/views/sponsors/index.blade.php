<div class="grid-24">
<a href="{{ url('sponsor/add') }}" class="btn btn-small btn-primary">{{ __('user.add_new_sponsor') }}</a><br /><br />
	<div class="widget widget-table">
						
		<div class="widget-header">
			<span class="icon-list"></span>
			<h3 class="icon chart">{{ __('common.index.sponsors') }}</h3>		
		</div>

		<div class="widget-content">
			
			<table class="table table-bordered table-striped data-table">
				<thead>
					<tr>
						<th>{{ __('user.name') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Sponsor::all() as $sponsor)
					<tr>
						<td><a href="{{ url('sponsor/'.$sponsor->slug) }}">{{ $sponsor->name }}</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

</div>