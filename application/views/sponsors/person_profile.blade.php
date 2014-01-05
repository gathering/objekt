<?php
$email = $person->email;
$default = URL::to_asset("/images/stream/defaultavatar_small.png");
$size = 200;

$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=mm&s=" . $size;
?>
<section id="content" class="content-sidebar bg-white">
	<aside class="sidebar bg-lighter sidebar">
		<div class="text-center clearfix bg-white">
			<img src="{{$grav_url}}">
		</div>
		<div class="bg-light padder padder-v">
			<span class="h4">{{ $person->firstname }} {{ $person->surname }}</span>
			@if ($person->contact_person == '1')
			<span class="block"><i class="icon-user"></i> {{ __('sponsor.contactperson') }}</span><br />
			@endif
		</div>
		<div class="list-group list-normal m-b-none">
			<a href="{{ $person->url('accreditation') }}" class="list-group-item">{{ __('accreditation.accredit') }}</a>
			<!--<a href="#" class="list-group-item">Activity</a>
			<a href="#" class="list-group-item">Group</a>
			<a href="#" class="list-group-item"><span class="badge m-r">3</span> Friends</a>
			<a href="#" class="list-group-item"><span class="badge m-r">4</span> Posts</a>-->
		</div>
	</aside>
	<section class="main padder">
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
					<td class="description"><b>{{ __('sponsor.associated') }}</b></td>
					<td class="value"><span><a href="{{ url('sponsor/'.$person->sponsor()->slug) }}">{{ $person->sponsor()->name }}</a></span></td>
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
			</tbody>
		</table>
		<section class="panel">
			<header class="panel-heading"> Logg </header>
			<ul class="list-group">
				@foreach ($person->logs()->get() as $log)
				<li class="list-group-item" data-toggle="class:active" data-target="#todo-1">
					<div class="media">
						<span class="pull-left thumb-small m-t-mini">
							<i class="icon-bookmark icon-xlarge text-default"></i>
						</span>
						<div id="todo-1" class="pull-right text-primary m-t-small">
							<i class="icon-circle icon-large text text-default"></i>
							<i class="icon-ok-sign icon-large text-active text-primary"></i>
						</div>
						<div class="media-body">
							<div><a href="#" class="h5">{{ $log->message }}</a></div>
							<small class="text-muted">{{ Date::nice($log->created_at) }}</small>
						</div>
					</div>
				</li>
				@endforeach
			</ul>
		</section>
	</section>
	<aside class="sidebar bg text-small">
      <div class="padder padder-v">
      	<h4>{{ __('sponsor.child') }}</h4>
      </div>
      <ul class="list-group list-normal m-b">
      	@foreach ($person->child()->get() as $person)
        <li class="list-group-item">
          <div class="media">
          	<?php
			$email = $person->email;
			$size = 36;
			
			$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "&s=" . $size;
			?>
            <span class="pull-left thumb-small"><img src="<?=$grav_url?>" alt="John said" class="img-circle"></span>
            @if ($person->status == "registred")
            <div class="pull-right text-warning m-t-small">
              <i class="icon-circle"></i>
            </div>
            @endif
            @if ($person->status == "arrived")
            <div class="pull-right text-success m-t-small">
              <i class="icon-circle"></i>
            </div>
            @endif
            @if ($person->status == "departed")
            <div class="pull-right text-danger m-t-small">
              <i class="icon-circle"></i>
            </div>
            @endif
            <div class="media-body">
              <div><a href="#" class="h5"><a href="{{ $person->url() }}">{{ $person->firstname }} {{ $person->surname }}</a></a></div>
              <small class="text-muted">{{ $person->contact_person ? __('sponsor.contactperson') : "" }}</small>
            </div>
          </div>
        </li>
		@endforeach
      </ul>
    </aside>
</section>
