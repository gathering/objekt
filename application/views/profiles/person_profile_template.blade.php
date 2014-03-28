@section('scripts')
<script>
$(function() {
  $("#follow").click(function(){
    if($(this).hasClass('active')){
      var status = 'not_follow';
    } else {
      var status = 'follow';
    }
    $.ajax({
      url: '{{ $person->url() }}/' + status
    });
    
  });
});
</script>
@endsection
<section id="content" class="content-sidebar bg-white">
	<aside class="sidebar bg-lighter sidebar">
		<div class="text-center clearfix bg-white">
			<img src="{{ $person->gravatar(200) }}">
		</div>
		<div class="bg-light padder padder-v">
			<span class="h4">{{ $person->firstname }} {{ $person->surname }}</span>
			@if ($person->contact_person == '1')
			<span class="block"><i class="icon-user"></i> {{ __('profile.contactperson') }}</span><br />
			@endif
      <br /><br />
      <button id="follow" class="btn btn-primary btn-sm {{ User::find(Auth::user()->id)->isFollowing('person', $person->id) ? 'active' : '' }}" data-toggle="button">
        <span class="text">
          <i class="icon-eye-open"></i> {{ __('profile.get_notifications') }}
        </span>
        <span class="text-active">
          <i class="icon-eye-open"></i> {{ __('profile.gets_notifications') }}
        </span>
      </button>
      @if($person->contact_person == '0')
      <br /><a href="{{ $person->url() }}/make_contactperson" class="btn btn-sm">{{ __('profile.make_contactperson') }}</a>
      @endif
		</div>
		<div class="list-group list-normal m-b-none">
			<a href="{{ $person->url() }}" class="list-group-item{{ URI::segment(2) != 'profile' ? ' active' : '' }}"><i class="icon-user"></i> Profil</a>
			<a href="{{ $person->url('accreditation') }}" class="list-group-item{{ URI::segment(2) != 'accreditation' ? ' active' : '' }}"><i class="icon-tag"></i> {{ __('accreditation.accredit') }}</a>
			<a href="{{ $person->url('sms') }}" class="list-group-item{{ URI::segment(2) != 'sms' ? ' active' : '' }}"><i class="icon-mobile-phone"></i> {{ __('sms.send_to_person') }}</a>
			<a href="{{ $person->url('person-edit') }}" class="list-group-item{{ URI::segment(2) != 'person-edit' ? ' active' : '' }}"><i class="icon-pencil"></i> {{ __('profile.edit') }}</a>
			@if ($person->is_child())
			<a href="{{ $person->parent()->url() }}" class="list-group-item{{ URI::segment(5) != 'add-child' ? ' active' : '' }}"><i class="icon-arrow-left"></i> {{ sprintf(__('profile.back_to_parent'), $person->parent()->firstname) }}</a>
			@endif
		</div>
	</aside>
	<section class="main padder">
		@yield('content')
	</section>
	@if (!$person->is_child())
	<aside class="sidebar bg text-small">
      <div class="padder padder-v">
      	<h4>{{ __('profile.child') }}</h4>
      </div>
      <ul class="list-group list-normal m-b">
      	@foreach ($person->child()->get() as $child)
        <li class="list-group-item">
          <div class="media">
            <span class="pull-left thumb-small"><img src="{{ $child->gravatar(36) }}" alt="" class="img-circle"></span>
            @if ($child->status == "registred")
            <div class="pull-right text-warning m-t-small">
              <i class="icon-circle"></i>
            </div>
            @endif
            @if ($child->status == "arrived")
            <div class="pull-right text-success m-t-small">
              <i class="icon-circle"></i>
            </div>
            @endif
            @if ($child->status == "departed")
            <div class="pull-right text-danger m-t-small">
              <i class="icon-circle"></i>
            </div>
            @endif
            <div class="media-body">
              <div><a href="#" class="h5"><a href="{{ $child->url() }}">{{ $child->firstname }} {{ $child->surname }}</a></a></div>
              <small class="text-muted">{{ $child->contact_person ? __('profile.contactperson') : "" }}</small>
            </div>
          </div>
        </li>
		@endforeach
      </ul>
      <a href="{{ $person->url() }}/add-child" class="list-group-item{{ URI::segment(5) == 'add-child' ? ' active' : '' }}"><i class="icon-plus"></i> {{ __('profile.add_child') }}</a>
    </aside>
    @endif
</section>