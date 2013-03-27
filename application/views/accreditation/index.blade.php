<div class="row">
	<div class="grid-12">
		<div class="widget">
			<div class="widget-header">
				<span class="icon-check"></span>
				<h3>{{ __('accreditation.existing_person') }}</h3>
			</div>
			<div class="widget-content">
				<form method="post" action="{{ url('accreditation') }}">
					<input type="text" name="search" placeholder="{{ __('common.search_person') }}" class="accreditationSearch" id="searchField" />
				</form>		
			</div>
		</div>
	</div>
	<div class="grid-12">
		<div class="widget">
			<div class="widget-header">
				<span class="icon-x"></span>
				<h3>{{ __('accreditation.non_existing_person') }}</h3>
			</div>
			<div class="widget-content">
				<form method="post" action="{{ url('search/sponsor') }}">
					<input type="text" name="search" placeholder="{{ __('common.search_sponsor') }}" class="accreditationSearch" id="searchField" />
				</form>	
			</div>
		</div>
	</div>	
</div>
<div class="grid-24">
	<div class="widget">			
		
		<div class="widget-header">
			<h3>{{ __('accreditation.undelivered_badges') }}</h3>
		</div>

		<div class="widget-content">
			<div class="field-group">
				<table class="table">
					<thead>
						<tr>
							<th style="width: 15%;">{{ __('accreditation.delivery_deadline') }}</th>
							<th style="width: 10%;">{{ __('accreditation.badge_id') }}</th>
							<th>{{ __('user.name') }}</th>
							
					</thead>				
					<tbody>
						@foreach (Entry::where("type", "=", "badge")->where("status", "=", "valid")->order_by("created_at", "asc")->get() as $entry)
						<tr>
							<td>
								{{ Date::regular($entry->delivery_date) }}<br />
								<b>{{ Date::countdown($entry->delivery_date) }}</b>
							</td>
							<td>
								{{ $entry->badge_id }}
							</td>
							<td class="description">
								<a href="{{ $entry->person()->url() }}">{{ $entry->person()->firstname }} {{ $entry->person()->surname }}</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>