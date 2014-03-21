<section id="content" class="content-sidebar bg-white">
 <!-- .sidebar -->
    <aside class="sidebar bg-lighter sidebar">
      
      @if ($profile->logo())
      <div class="text-center clearfix bg-white" style="padding-bottom: 15px; margin-top: 5px; padding-top: 15px;">
      @else
      <div class="text-center clearfix bg-white">
      @endif
      
        <img src="{{ $profile->logo() }}" />
      </div>
      <div class="bg-light padder padder-v"
      @if ($profile->color())
        style="color: {{ colorPalette::getContrast($profile->color()) }}; background: {{ $profile->color() }};"
      @endif
      >
        <span class="h4">{{__('profile.profile_name', array('name' => $profile->name))}}</span>
        <small class="block m-t-mini">{{__('profile.registred_at', array('at' => Date::nice($profile->created_at)))}}</small>
      </div>
      <div class="list-group list-normal m-b-none">
      	<a href="{{ $profile->url() }}" class="list-group-item active"><i class="icon-user"></i> {{ __('profile.profile') }}</a>
        <a href="{{ url('profile/'.$profile->slug.'/add-person') }}" class="list-group-item active"><i class="icon-plus"></i> {{ __('profile.add_personel') }}</a>
        @if (!empty($profile->website))
        <a href="{{ $profile->website }}" class="list-group-item active"><i class="icon-chevron-right"></i> Gå til nettsiden</a>
        @endif
        <a href="{{ url('profile/'.$profile->slug.'/edit') }}" class="list-group-item active"><i class="icon-pencil"></i> {{ __('profile.edit') }}</a>
      </div>
    </aside>
    <!-- /.sidebar -->

    <!-- .sidebar -->
    <section class="main">
      <div class="padder">
        <div class="row">
          <div class="col-xs-3 bg-primary padder-v">
            <div class="h2">{{ $profile->person_x()->count() }}</div>
            {{ __('profile.total') }}
          </div>
          <div class="col-xs-3 bg-warning padder-v">
            <div class="h2">{{ $profile->person_x()->where("status", "=", "registred")->count() }}</div>
            {{ __('profile.registred') }}
          </div>
          <div class="col-xs-3 bg-info padder-v">
            <div class="h2">{{ $profile->person_x()->where("status", "=", "arrived")->count() }}</div>
            {{ __('profile.arrived') }}
          </div>
          <div class="col-xs-3 bg-danger padder-v">
            <div class="h2">{{ $profile->person_x()->where("status", "=", "departed")->count() }}</div>
            {{ __('profile.departed') }}
          </div>
        </div>
      </div>
      <!--<ul class="nav nav-tabs m-b-none no-radius">
        <li class="active"><a href="#timeline" data-toggle="tab">{{ _('profile.timeline') }}</a></li>
      </ul>-->
      <div class="tab-content">
        <div class="tab-pane active padder" id="timeline">
          <section class="panel">
            <ul class="list-group">
              @foreach ($profile->person()->get() as $person)
              <li class="list-group-item">
                <div class="media">
                  <span class="pull-left thumb-small"><img src="<?=$person->gravatar()?>" class="img-circle"></span>
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
                    <div><a href="{{ $person->url() }}" class="h5">{{ $person->firstname }} {{ $person->surname }}</a></div>
                    <small class="text-muted">
                      @if (!empty($person->email))
                      <i class="icon-envelope"></i> {{ $person->email }} 
                      @endif
                      @if (!empty($person->phone))
                      <i class="icon-mobile-phone"></i> {{ $person->phone }}
                      @endif
                    </small>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </section>
        </div>
      </div>
    </section>
    <!-- /.sidebar -->
    <!-- .sidebar -->
    <aside class="sidebar bg text-small">
      <div class="padder padder-v">
      	{{ __('profile.contactpersons') }}
      </div>
      <ul class="list-group list-normal m-b">
		@foreach ($profile->contactpersons() as $person)
        <li class="list-group-item">
          <div class="media">
            <span class="pull-left thumb-small"><img src="{{ $person->gravatar() }}" alt="John said" class="img-circle"></span>
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
              <small class="text-muted">{{ $person->contact_person ? __('profile.contactperson') : "" }}</small>
            </div>
          </div>
        </li>
		@endforeach
      </ul>
    </aside>
    <!-- /.sidebar -->
</section>