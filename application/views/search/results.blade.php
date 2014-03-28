<div class="grid-24">
	<div class="widget widget-table">
						
		<div class="widget-header">
			<span class="icon-list"></span>
			<h3 class="icon chart">{{ __('common.index.search') }}</h3>		
		</div>

		<div class="widget-content">
			
			<table class="table table-bordered table-striped data-table">
				<thead>
					<tr>
						<th>{{ __('user.name') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($results as $result)
					<tr>
						<td><a href="{{ $result->url }}">{{ $result->name }}</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>