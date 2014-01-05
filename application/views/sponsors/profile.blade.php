<section id="content" class="content-sidebar bg-white">
 <!-- .sidebar -->
    <aside class="sidebar bg-lighter sidebar">
      
      @if ($sponsor->logo())
      <div class="text-center clearfix bg-white" style="padding-bottom: 15px; margin-top: 5px; padding-top: 15px;">
      @else
      <div class="text-center clearfix bg-white">
      @endif
      
        <img src="{{ $sponsor->logo() }}" />
      </div>
      <div class="bg-light padder padder-v"
      @if ($sponsor->color())
        style="color: {{ colorPalette::getContrast($sponsor->color()) }}; background: #{{ $sponsor->color() }};"
      @endif
      >
        <span class="h4">{{__('sponsor.profile_name', array('name' => $sponsor->name))}}</span>
        <small class="block m-t-mini">{{__('sponsor.registred_at', array('at' => Date::nice($sponsor->created_at)))}}</small>
      </div>
      <div class="list-group list-normal m-b-none">
      	<a href="{{ $sponsor->url() }}" class="list-group-item active"><i class="icon-calendar"></i> {{ __('sponsor.activity') }}</a>
        <a href="#" class="list-group-item"><i class="icon-user"></i> {{ __('sponsor.profile') }}</a>
        @if (!empty($sponsor->website))
        <a href="{{ $sponsor->website }}" class="list-group-item"><i class="icon-chevron-right"></i> Gå til nettsiden</a>
        @endif
      </div>
    </aside>
    <!-- /.sidebar -->

    <!-- .sidebar -->
    <section class="main">
      <div class="padder">
        <div class="row">
          <div class="col-xs-3 bg-primary padder-v">
            <div class="h2">{{ $sponsor->person_x()->count() }}</div>
            {{ __('sponsor.total') }}
          </div>
          <div class="col-xs-3 bg-warning padder-v">
            <div class="h2">{{ $sponsor->person_x()->where("status", "=", "registred")->count() }}</div>
            {{ __('sponsor.registred') }}
          </div>
          <div class="col-xs-3 bg-info padder-v">
            <div class="h2">{{ $sponsor->person_x()->where("status", "=", "arrived")->count() }}</div>
            {{ __('sponsor.arrived') }}
          </div>
          <div class="col-xs-3 bg-danger padder-v">
            <div class="h2">{{ $sponsor->person_x()->where("status", "=", "departed")->count() }}</div>
            {{ __('sponsor.departed') }}
          </div>
        </div>
      </div>
      <ul class="nav nav-tabs m-b-none no-radius">
        <li class="active"><a href="#timeline" data-toggle="tab">{{ _('sponsor.timeline') }}</a></li>
        <li><a href="#events" data-toggle="tab">Events</a></li>
        <li><a href="#feed" data-toggle="tab">Feed</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="timeline">
          <div class="timeline">
            <article class="timeline-item">
              <div class="timeline-caption">
                <div class="panel arrow arrow-left">
                  <span class="timeline-icon"><i class="icon-mobile-phone time-icon bg-primary"></i></span>
                  <span class="timeline-date">12:25 am</span>
                  <h5>
                    <span>Call to</span> Jason Cokde (021-254-3523)
                  </h5>
                </div>
              </div>
            </article>
            <article class="timeline-item alt">
              <div class="timeline-caption">
                <div class="panel arrow arrow-right">
                  <span class="timeline-icon"><i class="icon-male time-icon bg-success"></i></span>
                  <span class="timeline-date">10:00 am</span>
                  <h5>
                    <span>Appointment</span> Carmark Sook (.inc company)
                  </h5>
                  <p>Morbi nec nunc condimentum, egestas dui nec, </p>
                </div>
              </div>
            </article>
            <article class="timeline-item">
              <div class="timeline-caption">
                <div class="panel arrow arrow-left">
                  <span class="timeline-icon"><i class="icon-plane time-icon"></i></span>
                  <span class="timeline-date">8:00 am</span>
                  <h5>
                    <span>Fly to</span> Newyork City
                  </h5>
                  <p>82°, Very hot with some sun</p>
                </div>
              </div>
            </article>
            <article class="timeline-item alt">
              <div class="timeline-caption">
                <div class="panel arrow arrow-right">
                  <span class="timeline-icon"><i class="icon-file-text time-icon bg-info"></i></span>
                  <span class="timeline-date">9:30 am</span>
                  <h5>
                    <span>Meeting</span> Office A - 2 floor
                  </h5>
                  <p>Iaculis lorem justo porttitor orci. Vivamus vestibulum tortor augue. Donec elementum mollis velit.</p>
                </div>
              </div>
            </article>
            <article class="timeline-item">
              <div class="timeline-caption">
                <div class="panel arrow arrow-left">
                  <span class="timeline-icon"><i class="icon-code time-icon"></i></span>
                  <span class="timeline-date">9:00 am</span>
                  <h5>
                    <span>Work on</span> Web application project
                  </h5>
                  <p>Iaculis lorem justo porttitor orci. Donec elementum mollis velit.</p>
                </div>
              </div>
            </article>
            <div class="timeline-footer"><a href="#"><i class="icon-plus time-icon inline-block bg-default"></i></a></div>
          </div>
        </div>
        <div class="tab-pane" id="events">
          <ul class="list-group list-normal">
            <li class="list-group-item">
              <a href="#" class="thumb-small pull-left m-r-small"><img src="images/avatar.jpg" class="img-circle"></a>
              <div>
                <small class="pull-right text-muted">3 minuts ago</small>
                <a href="#"><strong>Foxe Ohil</strong></a> commented 
                <a href="#">Vestibulum ullamcorper sodales nisi nec...</a>
                <div class="text-small">
                  <a href="#">Like</a> <a href="#">Comment</a>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="tab-pane" id="feed">
          <ul class="list-group list-normal">
            <li class="list-group-item">
              <a href="#" class="thumb-small pull-left m-r-small"><img src="images/avatar.jpg" class="img-circle"></a>
              <div>
                <small class="pull-right text-muted">1 hour ago</small>
                <a href="#"><strong>Mogen Osker</strong></a> posted a video 
                <a href="#">Aamcorper sodales nisi nec</a>
                <div class="text-small">
                  <a href="#">Like</a> <a href="#">Comment</a>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </section>
    <!-- /.sidebar -->
    <!-- .sidebar -->
    <aside class="sidebar bg text-small">
      <!--<div class="padder padder-v">
        <div class="input-group">
          <input type="text" class="input-sm form-control" placeholder="Search friends">
          <span class="input-group-btn">
            <button class="btn btn-sm btn-white" type="button"><i class="icon-search"></i></button>
          </span>                
        </div>
      </div>-->
      <div class="padder padder-v">
      	{{ __('sponsor.contactpersons') }}
      </div>
      <ul class="list-group list-normal m-b">
		@foreach ($sponsor->contactpersons() as $person)
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
    <!-- /.sidebar -->
</section>