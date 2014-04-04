@layout('profiles.person_profile_template')
@section('content')
<h4>{{ $person->firstname }} {{ $person->surname }}</h4>
@if ($person->status != "arrived")
<a href="{{ $person->url('accreditation/wristband') }}" onclick="return confirm('{{ __('accreditation.confirm') }}')" class="btn btn-pink">
	<span class="icon-file-text"></span>
	{{ __('accreditation.wristband') }}
</a>
@if (Auth::user()->can("accreditation_badge"))
<a href="{{ $person->url('accreditation/badge') }}" class="btn btn-green">
	<span class="icon-tag"></span>
	{{ __('accreditation.badge') }}
</a>
@endif
@elseif ($person->status == "departed")
<i>{{ __('accreditation.has_departed') }}</i>			
@else
<i>{{ __('accreditation.already_done') }}</i><br /><br />
<a href="{{ $person->url('accreditation/departed') }}" class="btn btn-danger">
	<span class="icon-remove-sign"></span>
	{{ __('accreditation.departed') }}
</a>
<a href="{{ $person->url('accreditation/print') }}" class="btn btn-primary">
	<span class="icon-print"></span>
	{{ __('accreditation.print') }}
</a>
@endif
@endsection