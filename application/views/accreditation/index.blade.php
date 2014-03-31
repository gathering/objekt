<section class="toolbar clearfix m-t-large m-b">
	<a href="{{ url('profile/add') }}" class="btn btn-primary btn-circle"><i class="fa fa-group"></i> {{ __('user.add_new_profile') }}</a>
	<a href="{{ url('profile/add-person') }}" class="btn btn-warning btn-circle"><i class="fa fa-user"></i> {{ __('profile.add_personel') }}</a>
	<a href="{{ url('/help') }}" class="btn btn-info btn-circle"><i class="fa fa-question-circle"></i>{{ __('user.help') }}</a>
	<a href="{{ url('/aid') }}" onclick="return confirm('{{ __('user.air_confirm') }}')" class="btn btn-danger btn-circle active"><i class="fa fa-warning"></i>{{ __('user.aid') }}</a>
</section>
<br />
<div class="row">
	<div class="col-sm-6">
		<section class="panel">
			<header class="panel-heading">
              <span>{{ __('accreditation.existing_person') }}</span>
            </header>
			<div class="panel-body">
				<div class="form-group">
					<form method="post" action="{{ url('accreditation') }}">
						<div class="input-group">
					      <input type="text" name="search" placeholder="{{ __('common.search_person') }}" class="accreditationSearch form-control" id="searchField" />
					      <span class="input-group-btn">
					        <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</form>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-6">
		<section class="panel">
			<header class="panel-heading">
              <span>{{ __('accreditation.non_existing_person') }}</span>
            </header>
			<div class="panel-body">
				<div class="form-group">
					<form method="post" action="{{ url('search/profile') }}">
						<div class="input-group">
						  <input type="hidden" name="type" value="accreditiation" />
					      <input type="text" name="search" placeholder="{{ __('common.search_profiles') }}" class="accreditationSearch form-control" id="searchField" />
					      <span class="input-group-btn">
					        <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</form>
				</div>
			</div>
		</section>
	</div>
</div>
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