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
      <div class="text-muted">{{ sprintf(__('profile.followers'), $person->followers()->count()) }}</div>
			@if ($person->contact_person == '1')
			<span class="block"><i class="icon-user"></i> {{ __('profile.contactperson') }}</span><br />
			@endif
      <br />
      <button id="follow" class="btn btn-primary btn-sm {{ User::find(Auth::user()->id)->isFollowing('person', $person->id) ? 'active' : '' }}" data-toggle="button">
        <span class="text">
          <i class="icon-eye-open"></i> {{ __('profile.get_notifications') }}
        </span>
        <span class="text-active">
          <i class="icon-eye-open"></i> {{ __('profile.gets_notifications') }}
        </span>
      </button>
      @if($person->contact_person == '0' && Auth::user()->can("edit_profile"))
      <br /><a href="{{ $person->url() }}/make_contactperson" class="btn btn-sm">{{ __('profile.make_contactperson') }}</a>
      @endif
		</div>
		<div class="list-group list-normal m-b-none">
			<a href="{{ url($person->url()) }}" class="list-group-item{{ URI::segment(2) != 'profile' ? ' active' : '' }}"><i class="fa fa-user"></i> Profil</a>
			@if (Auth::user()->can("accreditation"))
      <a href="{{ url($person->url('accreditation')) }}" class="list-group-item{{ URI::segment(2) != 'accreditation' ? ' active' : '' }}"><i class="fa fa-tag"></i> {{ __('accreditation.accredit') }}</a>
			@endif
      @if (Auth::user()->can("sms"))
      <a href="{{ url($person->url('sms')) }}" class="list-group-item{{ URI::segment(2) != 'person-edit' ? ' active' : '' }}"><i class="fa fa-mobile-phone"></i> {{ __('profile.sms_inbox') }}</a>
      <a href="{{ url($person->url('sms/send')) }}" class="list-group-item{{ URI::segment(2) != 'sms' ? ' active' : '' }}"><i class="fa fa-mobile-phone"></i> {{ __('sms.send_to_person') }}</a>
			@endif
      @if (Auth::user()->can("edit_personell"))
      <a href="{{ url($person->url('person-edit')) }}" class="list-group-item{{ URI::segment(2) != 'person-edit' ? ' active' : '' }}"><i class="fa fa-pencil"></i> {{ __('profile.edit') }}</a>
			@endif
      @if ($person->is_child())
			<a href="{{ url($person->parent()->url()) }}" class="list-group-item{{ URI::segment(5) != 'add-child' ? ' active' : '' }}"><i class="fa fa-arrow-left"></i> {{ sprintf(__('profile.back_to_parent'), $person->parent()->firstname) }}</a>
			@endif
		</div>
	</aside>
	<section class="main padder">
		@yieldSection('content')
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
              <div><a href="{{ url($child->url()) }}">{{ $child->firstname }} {{ $child->surname }}</a></div>
              <small class="text-muted">{{ $child->contact_person ? __('profile.contactperson') : "" }}</small>
            </div>
          </div>
        </li>
		@endforeach
      </ul>
      @if (Auth::user()->can("add_personell"))
      <a href="{{ url($person->url()) }}/add-child" class="list-group-item{{ URI::segment(5) == 'add-child' ? ' active' : '' }}"><i class="icon-plus"></i> {{ __('profile.add_child') }}</a>
      @endif
    </aside>
    @endif
</section>