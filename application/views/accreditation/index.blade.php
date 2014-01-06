<div class="row">
	<div class="col-sm-6">
		<section class="panel">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-3 control-label">{{ __('accreditation.existing_person') }}</label>
					<div class="col-lg-8">
						<form method="post" action="{{ url('accreditation') }}">
							<div class="input-group">
						      <input type="text" name="search" placeholder="{{ __('common.search_person') }}" class="accreditationSearch form-control" id="searchField" />
						      <span class="input-group-btn">
						        <button class="btn btn-default" type="button"><i class="icon-search"></i></button>
						      </span>
						    </div><!-- /input-group -->
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-6">
		<section class="panel">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-3 control-label">{{ __('accreditation.non_existing_person') }}</label>
					<div class="col-lg-8">
						<form method="post" action="{{ url('search/sponsor') }}">
							<div class="input-group">
							  <input type="hidden" name="type" value="accreditiation" />
						      <input type="text" name="search" placeholder="{{ __('common.search_sponsor') }}" class="accreditationSearch form-control" id="searchField" />
						      <span class="input-group-btn">
						        <button class="btn btn-default" type="button"><i class="icon-search"></i></button>
						      </span>
						    </div><!-- /input-group -->
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@if (isset($results))
<section class="panel">
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
</section>
@endif
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