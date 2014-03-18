<div style="padding: 20px;">Her kjem det eit sabla dæsjbord..</div>
<?
/*
********************************************
THIS FUNCTION IS MOVING IN AT THE DASHBOARD.
********************************************

Awaiting moving trucks...

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
*/ ?>