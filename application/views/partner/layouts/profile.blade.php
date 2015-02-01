<? $profile = partnerAuth::user()->profile(); ?>
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
        <small class="block m-t-mini">{{__('profile.registred_at', array('at' => Date::snice($profile->created_at)))}}</small>
      </div>
      <div class="list-group list-normal m-b-none">
      	@yieldSection('navigation')
      </div>
    </aside>
    <!-- /.sidebar -->

    <!-- .sidebar -->
    <section class="main">
      <div class="padder" style="padding-top: 0px;">
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
        <li class="active"><a href="#timeline" data-toggle="tab">{{ __('profile.timeline') }}</a></li>
      </ul>-->
      <div class="tab-content">
          @yieldSection('content')
      </div>
    </section>
</section>