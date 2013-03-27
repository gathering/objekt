<div class="grid-16">	
		
	<div class="widget widget-plain">
		
		<div class="widget-content">
			
			<div class="department">
				
				<h2>{{ __('sponsor.contactpersons') }}</h2>
				
				@foreach ($sponsor->contactpersons() as $person)
				<div class="user-card">
					
					<div class="avatar">
						<?php
						$email = $person->email;
						$size = 32;
						
						$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "&s=" . $size;
						?>
						<img src="{{$grav_url}}" title="User" alt="">
					</div> <!-- .user-card-avatar -->
					
					<div class="details">
						<p><strong><a href="{{ $person->url() }}">{{ $person->firstname }} {{ $person->surname }}</a></strong><br>
							{{ Format::phone($person->phone) }}<br><br>
							@if ($person->status == "registred")
							<span class="ticket ticket-info">{{ __('sponsor.status.registred') }}</span><br />
							@endif
							@if ($person->status == "arrived")
							<span class="ticket ticket-success">{{ __('sponsor.status.arrived') }}</span><br />
							@endif
							@if ($person->status == "departed")
							<span class="ticket ticket-important">{{ __('sponsor.status.departed') }}</span><br />
							@endif<br />
					</div> <!-- .user-card-content -->
					
				</div> <!-- .user-card -->
				@endforeach

			</div> <!-- .department -->
			<div class="department">
				
				<h2>{{ __('sponsor.otherpersons') }}</h2>

				@foreach ($sponsor->person()->get() as $person)
				<div class="user-card">
					
					<div class="avatar">
						<?php
						$email = $person->email;
						$size = 32;
						
						$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "&s=" . $size;
						?>
						<img src="{{$grav_url}}" title="User" alt="">
					</div> <!-- .user-card-avatar -->
					
					<div class="details">
						<p><strong><a href="{{ $person->url() }}">{{ $person->firstname }} {{ $person->surname }}</a></strong><br>
							{{ Format::phone($person->phone) }}<br><br>
							@if ($person->status == "registred")
							<span class="ticket ticket-info">{{ __('sponsor.status.registred') }}</span><br />
							@endif
							@if ($person->status == "arrived")
							<span class="ticket ticket-success">{{ __('sponsor.status.arrived') }}</span><br />
							@endif
							@if ($person->status == "departed")
							<span class="ticket ticket-important">{{ __('sponsor.status.departed') }}</span><br />
							@endif<br />
					</div> <!-- .user-card-content -->
					
				</div> <!-- .user-card -->
				@endforeach
				
			</div> <!-- .department -->

		</div> <!-- .widget-content -->

	</div> <!-- .widget -->
	
</div> <!-- .grid -->
								
		
	<div class="grid-8">
		
		<div class="box">
			<h3>{{__('sponsor.profile_name', array('name' => $sponsor->name))}}</h3>
			<p>
				{{__('sponsor.registred_at', array('at' => Date::nice($sponsor->created_at)))}}<br />
				@if (!empty($sponsor->website))
				{{ __('sponsor.website_url', array('website' => $sponsor->website )) }}
				@endif
			</p>
			<p>
				<a href="{{ url('sponsor/'.$sponsor->slug.'/add-person') }}" class="btn-large btn btn-primary dashboard_add">{{ __('sponsor.add_personel') }}</a>
			</p>
		</div>			
		
	</div> <!-- .grid -->