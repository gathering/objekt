<section class="panel">
	<div class="panel-body">
		<img src="{{ asset('images/clockwork.png') }}" alt="" /><br /><br />
		SMS-kontoen har <? $clockwork = new Clockwork; $balance = $clockwork->checkBalance(); echo $balance['balance']." ".$balance['code']; ?> igjen. Det tilsvarer <?=$clockwork->checkCredit()?> meldinger.
	</div>
</section>
<section class="panel">
	<div class="panel-body text-muted l-h-2x" style="text-align: center;">
		<span class="badge bg-success">{{ Events::current()->people()->count() }}</span>
		<span class="m-r-small">{{ __('profile.total') }}</span>
		<span class="badge bg-warning">{{ Events::current()->people()->where("status", "=", "registred")->count() }}</span>
		<span class="m-r-small">{{ __('profile.registred') }}</span>
		<span class="badge bg-info">{{ Events::current()->entries()->where("entries.status", "=", "valid")->count() }}</span>
		<span class="m-r-small">{{ __('profile.arrived') }}</span>
		<span class="badge bg-danger">{{ Events::current()->people()->where("status", "=", "departed")->count() }}</span> {{ __('profile.departed') }}
	</div>
</section>
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