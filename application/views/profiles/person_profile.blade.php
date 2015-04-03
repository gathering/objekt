@layout('profiles.person_profile_template')
@section('content')
<h4>{{ $person->firstname }} {{ $person->surname }}</h4>
<table class="table">					
	<tbody>
		<tr>
			<td class="description" style="border:0px;"><b>{{ __('user.phone') }}</b></td>
			<td class="value" style="border:0px;"><span><a href="skype:{{ $person->phone }}?call">{{ Format::phone($person->phone) }}</a></span></td>
		</tr>
		<tr>
			<td class="description"><b>{{ __('user.email') }}</b></td>
			<td class="value"><span><a href="mailto:{{ $person->email }}">{{ $person->email }}</a></span></td>
		</tr>
		<tr>
			<td class="description"><b>{{ __('profile.associated') }}</b></td>
			<td class="value"><span><a href="{{ url('profile/'.$person->profile()->slug) }}">{{ $person->profile()->name }}</a></span></td>
		</tr>
		<tr>
			<td class="description"><b>{{ __('user.created_at') }}</b></td>
			<td class="value"><span>{{ Date::nice($person->created_at) }}</span></td>
		</tr>
		<tr>
			<td class="description"><b>{{ __('user.updated_at') }}</b></td>
			<td class="value"><span>{{ Date::nice($person->updated_at) }}</span></td>
		</tr>
		<tr>
			<td class="description"><b>{{ __('user.notes') }}</b></td>
			<td class="value"><span>{{ empty($person->note) ? "<i>".__('user.missing_string')."</i>" : $person->note }}</span></td>
		</tr>
		<tr>
			<td class="description"><b>{{ __('user.status') }}</b></td>
			<td class="value"><span>
				@if ($person->status == "registred")
				<span class="ticket ticket-info">{{ __('profile.status.registred') }}</span><br />
				@endif
				@if ($person->status == "arrived")
				<span class="ticket ticket-success">{{ __('profile.status.arrived') }}</span><br />
				@endif
				@if ($person->status == "departed")
				<span class="ticket ticket-important">{{ __('profile.status.departed') }}</span><br />
				@endif
			</span></td>
		</tr>
		@if($entry = $person->validEntries()->where('type', '=', 'wristband')->first())
		<tr>
			<td class="description"><b>{{ __('user.accreditation_ident') }}</b></td>
			<td class="value"><span>{{ $entry->ident }}</span></td>
		</tr>
		@endif
	</tbody>
</table>
<section class="panel">
	<header class="panel-heading"> Logg </header>
	<ul class="list-group">
		@foreach ($person->logs()->get() as $log)
		<li class="list-group-item">
			<div class="media">
				<span class="pull-left thumb-small m-t-mini">
					<i class="icon-bookmark icon-xlarge text-default"></i>
				</span>
				<div class="media-body">
					<div>{{ $log->message }}</div>
					<small class="text-muted">{{ Date::nice($log->created_at) }}</small>
				</div>
			</div>
		</li>
		@endforeach
	</ul>
</section>
@endsection