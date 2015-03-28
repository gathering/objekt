@layout('profiles.person_profile_template')
@section('content')
<form class="form-horizontal" method="post">  
	<h3>{{ __('profile.edit_person') }}: {{ $person->firstname." ".$person->surname }}</h3>    <br /><br />
<div class="form-group">
  <label class="col-lg-3 control-label">{{ __('user.firstname') }}</label>
  <div class="col-lg-8">
    <input type="text" name="firstname" value="{{ $person->firstname }}" tabindex="1" placeholder="{{ __('profile.placeholder.firstname') }}" class="form-control" autocomplete="off">
  </div>
</div>
<div class="form-group">
  <label class="col-lg-3 control-label">{{ __('user.surname') }}</label>
  <div class="col-lg-8">
    <input type="text" name="surname" value="{{ $person->surname }}" tabindex="2" placeholder="{{ __('profile.placeholder.surname') }}" class="form-control" autocomplete="off">
  </div>
</div>
<div class="form-group">
  <label class="col-lg-3 control-label">{{ __('user.phone') }}</label>
  <div class="col-lg-8">
    <input type="text" name="phone" value="{{ $person->phone }}" tabindex="3" placeholder="{{ __('profile.placeholder.phone') }}"  class="form-control" autocomplete="off">
    <br />
    <div class="alert alert-info">
      {{ __('profile.phone_news') }}
    </div>
  </div>
</div>
<div class="form-group">
  <label class="col-lg-3 control-label">{{ __('user.email') }}</label>
  <div class="col-lg-8">
    <input type="text" name="email" value="{{ $person->email }}" tabindex="4" placeholder="{{ __('profile.placeholder.email') }}"  class="form-control" autocomplete="off">
  </div>
</div>

<hr />

@if ($person->parent_id == 0)
<div class="form-group">
  <label class="col-lg-3 control-label">{{ __('profile.contactperson') }}</label>
  <div class="col-lg-8">
    <div class="checkbox">
      <label>
        <input name="contact_person" tabindex="7" {{ $person->contact_person == "1" ? " checked='checked'" : "" }} type="checkbox"> {{ __('profile.placeholder.contact_person') }}
      </label>
    </div>
  </div>
</div>
@endif

<div class="form-group">
  <div class="col-lg-9 col-lg-offset-3">                     
    <button type="submit" class="btn btn-primary">{{ __('profile.edit_person') }}</button>
    <a href="{{ $person->url('delete-person') }}" class="btn btn-danger">{{ __('user.delete') }}</a> 
  </div>
</div>
</form>
@endsection