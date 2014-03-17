@layout('profiles.person_profile_template')
@section('content')
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
@endsection