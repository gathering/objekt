<?php
$email = $person->email;
$default = URL::to_asset("/images/stream/defaultavatar_small.png");
$size = 150;

$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=mm&s=" . $size;
?>
<div class="grid-24">				
	<div class="widget">			
		
		<div class="widget-header">
			<h3>{{ $person->is_child() ? "<a href='".url('sponsor/'.$person->sponsor()->slug.'/'.$person->parent()->slug)."'>".$person->parent()->firstname." ".$person->parent()->surname."</a> > " : "" }}{{ $person->firstname }} {{ $person->surname }}</h3>
		</div>
		
		<div class="widget-content">
			<div class="grid-4">
				<img src="{{$grav_url}}" title="User" alt=""><br /><br />
				@if ($person->contact_person == '1')
				<span class="ticket"><i class="icon-user"></i> {{ __('sponsor.contactperson') }}</span><br />
				@endif
				<br />
			</div>
			<div class="grid-16">
				<div class="field-group">
					<table class="table">					
						<tbody>
							<tr>
								<td class="description"><b>{{ __('user.name') }}</b></td>
								<td class="value"><span>{{ $person->firstname }} {{ $person->surname }}</span></td>
							</tr>
							<tr>
								<td class="description"><b>{{ __('user.phone') }}</b></td>
								<td class="value"><span><a href="skype:{{ $person->phone }}?call">{{ empty($person->phone) ? "<i>".__('user.missing_string')."</i>" : Format::phone($person->phone) }}</a></span></td>
							</tr>
							<tr>
								<td class="description"><b>{{ __('user.email') }}</b></td>
								<td class="value"><span><a href="mailto:{{ $person->email }}">{{ empty($person->email) ? "<i>".__('user.missing_string')."</i>" : $person->email }}</a></span></td>
							</tr>
							<tr>
								<td class="description"><b>{{ __('sponsor.associated') }}</b></td>
								<td class="value"><span><a href="{{ url('sponsor/'.$person->sponsor()->slug) }}">{{ $person->sponsor()->name }}</a></span></td>
							</tr>
							<tr>
								<td class="description"><b>{{ __('user.notes') }}</b></td>
								<td class="value"><span>{{ empty($person->note) ? "<i>".__('user.missing_string')."</i>" : $person->note }}</span></td>
							</tr>
							<tr>
								<td class="description"><b>{{ __('user.status') }}</b></td>
								<td class="value"><span>
									@if ($person->status == "registred")
									<span class="ticket ticket-info">{{ __('sponsor.status.registred') }}</span><br />
									@endif
									@if ($person->status == "arrived")
									<span class="ticket ticket-success">{{ __('sponsor.status.arrived') }}</span><br />
									@endif
									@if ($person->status == "departed")
									<span class="ticket ticket-important">{{ __('sponsor.status.departed') }}</span><br />
									@endif
								</span></td>
							</tr>
							<tr>
								<td class="value" colspan="2"><a href="{{ $person->url() }}">{{ __('user.readmore') }}…</a></td>
							</tr>
						</tbody>
					</table>
					@if ($person->status != "arrived")
					<a href="{{ $person->url('accreditation/wristband') }}" onclick="return confirm('{{ __('accreditation.confirm') }}')" class="btn btn-pink">
						<span class="icon-document-alt-fill"></span>
						{{ __('accreditation.wristband') }}
					</a>

					<a href="{{ $person->url('accreditation/badge') }}" class="btn btn-green">
						<span class="icon-award-fill"></span>
						{{ __('accreditation.badge') }}
					</a>
					@elseif ($person->status == "departed")
					<i>{{ __('accreditation.has_departed') }}</i>			
					@else
					<i>{{ __('accreditation.already_done') }}</i><br /><br />
					<a href="{{ $person->url('accreditation/departed') }}" class="btn btn-red">
						<span class="icon-arrow-right-alt1"></span>
						{{ __('accreditation.departed') }}
					</a>
					@endif
				</div>
			</div>
		</div>
	</div>					
</div>
<div class="grid-24">
	<div class="widget">			
		
		<div class="widget-header">
			<h3>{{ __('sponsor.log') }}</h3>
		</div>

		<div class="widget-content">
			<div class="field-group">
				<table class="table">					
					<tbody>
						@foreach ($person->logs()->get() as $log)
						<tr>
							<td class="description">
								<b>{{ Date::nice($log->created_at) }}:</b> {{ $log->message }}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@if ($person->num_child() > 0)
<div class="grid-24">
	<div class="widget">			
		
		<div class="widget-header">
			<h3>{{ __('sponsor.child') }}</h3>
		</div>

		<div class="widget-content">
			<div class="field-group">
				<table class="table">					
					<thead>
						<tr>
							<th class="description"><b>{{ __('user.name') }}</b></td>
						</tr>
					</thead>
					<tbody>
						@foreach ($person->child()->get() as $child)
						<tr>
							<td class="description"><a href="{{ url('sponsor/'.$person->sponsor()->slug.'/'.$person->slug.'/'.$child->slug) }}">{{ $child->firstname }} {{ $child->surname }}</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
