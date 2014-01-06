<section id="content" class="content-sidebar bg-white">
	<aside class="sidebar bg-lighter sidebar">
		<div class="text-center clearfix bg-white">
			<img src="{{ $person->gravatar(200) }}">
		</div>
		<div class="bg-light padder padder-v">
			<span class="h4">{{ $person->firstname }} {{ $person->surname }}</span>
			@if ($person->contact_person == '1')
			<span class="block"><i class="icon-user"></i> {{ __('sponsor.contactperson') }}</span><br />
			@endif
		</div>
		<div class="list-group list-normal m-b-none">
			<a href="{{ $person->url() }}" class="list-group-item">Profil</a>
			<a href="{{ $person->url('accreditation') }}" class="list-group-item active">{{ __('accreditation.accredit') }}</a>
			<!--<a href="#" class="list-group-item">Activity</a>
			<a href="#" class="list-group-item">Group</a>
			<a href="#" class="list-group-item"><span class="badge m-r">3</span> Friends</a>
			<a href="#" class="list-group-item"><span class="badge m-r">4</span> Posts</a>-->
		</div>
	</aside>
	<section class="main padder">
		<h4>{{ $person->firstname }} {{ $person->surname }}</h4>
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
		<i>{{ __('accreditation.already_done') }}</i><br />
		<a href="{{ $person->url('accreditation/departed') }}" class="btn btn-red">
			<span class="icon-arrow-right-alt1"></span>
			{{ __('accreditation.departed') }}
		</a>
		@endif
	</section>
	<aside class="sidebar bg text-small">
      <div class="padder padder-v">
      	<h4>{{ __('sponsor.child') }}</h4>
      </div>
      <ul class="list-group list-normal m-b">
      	@foreach ($person->child()->get() as $person)
        <li class="list-group-item">
          <div class="media">
            <span class="pull-left thumb-small"><img src="{{ $person->gravatar() }}" class="img-circle"></span>
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
