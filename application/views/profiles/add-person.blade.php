<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<h3>{{ isset($person) ? __('user.add_child') : __('profile.add_personel') }}</h3>    <br /><br />
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.firstname') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="firstname" tabindex="1" placeholder="{{ __('profile.placeholder.firstname') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.surname') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="surname" tabindex="2" placeholder="{{ __('profile.placeholder.surname') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.phone') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="phone" tabindex="3" placeholder="{{ __('profile.placeholder.phone') }}"  class="form-control" autocomplete="off">
	      </div>
	    </div>
		<div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('user.email') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="email" tabindex="4" placeholder="{{ __('profile.placeholder.email') }}"  class="form-control" autocomplete="off">
	      </div>
	    </div>
	    @if (isset($profile))
	    <div class="form-group">
	      <label class="col-lg-3 control-label" style="margin-right: 15px;">{{ __('profile.attached_to') }}</label>
	      <div class="clearfix m-b" style="border: 0px; margin-top: 7px;">
	          <ul>
	            <li class="label bg-default" style="font-size: 13px;">{{ $profile->name }}</li>
	            {{ isset($person) ? "<li class='label bg-inverse' style='font-size: 13px;'>".$person->firstname." ".$person->surname."</li>" : "" }}
	          </ul>
	      </div>
	    </div>
	    @else
		<div class="field-group">
			<label class="col-lg-3 control-label">{{ __('profile.attached_to') }}</label>
			<div class="field col-lg-8">
				<select name="profile" class="form-control" tabindex="6" id="profile">
					<option>{{ __('profile.choose_profile') }}</option>
					@foreach (Profile::all() as $profile)
					<option value="{{ $profile->id }}">{{ $profile->name }}</option>
					@endforeach
				</select>
			</div>		
		</div> <!-- .field-group -->
		<br /> <br />
		@endif

		<hr />

		@if (!isset($person))
		<div class="form-group">
          <label class="col-lg-3 control-label">{{ __('profile.contactperson') }}</label>
          <div class="col-lg-8">
            <div class="checkbox">
              <label>
                <input name="agree" name="contact_person" tabindex="7" type="checkbox"> {{ __('profile.placeholder.contact_person') }}
              </label>
            </div>
          </div>
        </div>
		@endif

	    <div class="form-group">
	      <div class="col-lg-9 col-lg-offset-3">                      
	        <button type="submit" class="btn btn-primary">Legg til ny bruker</button>
	      </div>
	    </div>
	  </form>
	</div>
</section>