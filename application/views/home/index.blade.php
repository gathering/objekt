@section('special_scripts')

var countOnline = 0;
presenceChannel.bind('pusher:subscription_succeeded', function() {
	countOnline = presenceChannel.members.count;
	updatePieChart(countOnline);
	
});
presenceChannel.bind('pusher:member_added', function(member) {
	console.log('member come');
	console.log(member);
	countOnline = presenceChannel.members.count;
	updatePieChart(countOnline);
});
presenceChannel.bind('pusher:member_removed', function(member) {
	console.log('member gone');
	console.log(member);
	countOnline = presenceChannel.members.count;
	updatePieChart(countOnline);
});

var pieChart;

var updatePieChart = function($newValue) {
	var $this = $('.membersOnline'), 
	$text = $('span', $this), 
	$oldValue = $text.html();

	$newPieValue = $newValue / {{ $current_event->users()->count() }} * 100;
    
    $this.data('easyPieChart').update($newPieValue);

    $({v: $oldValue}).animate({v: $newValue}, {
		duration: 1000,
		easing:'swing',
		step: function() {
			$text.text(Math.ceil(this.v));
		}
	});
};

$('.membersOnline').each(function(){
	var $barColor = $(this).data("barColor") || function($percent) {
        $percent /= 100;
        return "rgb(" + Math.round(255 * (1-$percent)) + ", " + Math.round(255 * $percent) + ", 125)";
    },
	$trackColor = $(this).data("trackColor") || "#c8d2db",
	$scaleColor = $(this).data("scaleColor"),
	$lineWidth = $(this).data("lineWidth") || 12,
	$size = $(this).data("size") || 130,
	$animate = $(this).data("animate") || 1000;

	pieChart = $(this).easyPieChart({
        barColor: $barColor,
        trackColor: $trackColor,
        scaleColor: $scaleColor,
        lineCap: 'butt',
        lineWidth: $lineWidth,
        size: $size,
        animate: $animate,
        onStop: function(){
        	var $this = this.$el;
        	$this.data("loop") && setTimeout(function(){ $this.data("loop") && updatePieChart($this) }, 2000);        	
        }
    });
});
@endsection
@section('scripts')
<script src="{{ asset('js/charts/flot/jquery.flot.min.js') }}"></script>
<script src="{{ asset('js/charts/flot/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ asset('js/charts/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('js/charts/flot/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('js/charts/flot/jquery.flot.pie.min.js') }}"></script>
<script>
$(function(){
	var data = [];
	data[0] = {
		label: '{{ __('profile.registred') }}',
		data: {{ Events::current()->people()->where("status", "=", "registred")->count() }}
	};
	data[1] = {
		label: '{{ __('profile.arrived') }}',
		data: {{ Events::current()->entries()->where("entries.status", "=", "valid")->count() }}
	};
	data[2] = {
		label: '{{ __('profile.departed') }}',
		data: {{ Events::current()->people()->where("status", "=", "departed")->count() }}
	};

	$.plot($("#flot-pie"), data, {
	    series: {
	      pie: {
	        combine: {
	              color: "#999",
	              threshold: 0.05
	            },
	        show: true
	      }
	    },    
	    colors: ["#f4c414","#5191d1","#ff5f5f"],
	    legend: {
	      show: false
	    },
	    grid: {
	        hoverable: true,
	        clickable: false
	    },
	    tooltip: true,
	    tooltipOpts: {
	      content: "%s: %p.0%"
	    }
	});

	$('.countdownDone').hide();

	$("#toggleHiddenBadge").click(function(){
		$('.countdownDone').toggle();
	});
});
</script>
@endsection
<div class="welcome">
	<div class="labelDisplay">{{ __('dashboard.welcome') }}</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<section class="panel">
		<header class="panel-heading bg bg-inverse">
		{{ __('dashboard.profiles') }}
		</header>
		<div class="list-group">
			@foreach(Auth::user()->profiles()->get() as $profile)
			<a href="{{ $profile->url() }}" class="list-group-item bg-lighter">
				<i class="fa fa-chevron-right"></i>
				{{ $profile->name }}
			</a>
			@endforeach
		</section>
		<div class="row">
			<div class="col-lg-6">
				<section class="panel">
					<header class="panel-heading bg-inverse">
					  <div class="text-center h5">{{ __('dashboard.visiting') }}</div>
					</header>
					<div class="panel-body pull-in text-center">
					  	<div id="flot-pie" style="height:200px"></div>
					  	<div style="display: inline-block; padding-bottom: 5px;">
			  			    <span class="badge bg-success">{{ Events::current()->people()->count() }}</span>
							<span class="m-r-small">{{ __('profile.total') }}</span>
						</div>
						<div style="display: inline-block; padding-bottom: 5px;">
							<span class="badge bg-warning">{{ Events::current()->people()->where("status", "=", "registred")->count() }}</span>
							<span class="m-r-small">{{ __('profile.registred') }}</span>
						</div>
						<div style="display: inline-block; padding-bottom: 5px;">
							<span class="badge bg-info">{{ Events::current()->entries()->where("entries.status", "=", "valid")->count() }}</span>
							<span class="m-r-small">{{ __('profile.arrived') }}</span>
						</div>
						<div style="display: inline-block; padding-bottom: 5px;">
							<span class="badge bg-danger">{{ Events::current()->people()->where("status", "=", "departed")->count() }}</span>
							{{ __('profile.departed') }}
						</div>
					</div>
				</section>
			</div>
			<div class="col-lg-6">
				<section class="panel">
					<header class="panel-heading bg-inverse">
					  <div class="text-center h5">{{ __('dashboard.users_online') }}</div>
					</header>
					<div class="panel-body pull-in text-center">
					  <div class="inline">
					    <div class="membersOnline easyPieChart" data-percent="0" data-bar-color="#5191d1" style="width: 130px; height: 130px; line-height: 130px;">
					      <span class="h2" style="margin-left:10px;margin-top:10px;display:inline-block">0</span> stk
					      <div class="easypie-text text-muted">{{ __('dashboard.online') }}</div>
					    <canvas width="130" height="130"></canvas></div>
					  </div>
					</div>
				</section>
				@if (Auth::user()->is('superAdmin'))
				<section class="panel">
					<header class="panel-heading bg-inverse">
					  <div class="text-center h5">{{ __('dashboard.sms') }}</div>
					</header>
					<div class="panel-body text-center">
					  <span class="thumb-small avatar inline" style="background: black; color: white; line-height: 32px;">
					  	<? $clockwork = new Clockwork; ?>
					  	<?=$clockwork->checkCredit()?>
					  </span>
					  {{ __('dashboard.sms_remaining') }}
					</div>
				</section>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading bg-inverse">
						<div class="h5 pull-left">{{ __('dashboard.badge_manager') }}</div>
						<button id="toggleHiddenBadge" class="btn btn-primary btn-xs pull-right" data-toggle="button">
							<span class="text">
					          {{ __('dashboard.togglehidden') }}
					        </span>
					        <span class="text-active">
					          {{ __('dashboard.togglehidden2') }}
					        </span>
							
						</button>
					</header>
					<div class="list-group m-b-small badgeManager">
						@foreach ($current_event->entries()->where("entries.type", "=", "badge")->where("entries.status", "=", "valid")->order_by("entries.delivery_date", "asc")->get() as $entry)
						<a href="{{ $entry->person()->url() }}" class="list-group-item {{ Date::raw_countdown($entry->delivery_date)->invert > 0 ? 'countdownDone' : '' }}">
							<span class="badge bg-danger" title="{{ sprintf(__('dashboard.must_be_delivered'), Date::regular($entry->delivery_date)) }}">{{ Date::countdown($entry->delivery_date) }}</span>
							{{ $entry->person()->firstname }} {{ $entry->person()->surname }}
						</a>
						@endforeach
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<section class="panel">
			<header class="panel-heading bg-inverse">
			  <div class="text-center h5">{{ __('dashboard.latest') }}</div>
			</header>
			<ul class="list-group">
			@foreach($feed->get_items(0, 3) as $item)
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body">
                  	@if($enclosure = $item->get_enclosure())
                  	<div class="media-image" style="background-image: url('{{ $enclosure->get_link() }}')"></div>
                  	@endif
                    <div><a href="{{ $item->get_permalink() }}" target="_blank" class="h3">{{ $item->get_title() }}</a></div>
                    <small class="text-muted">{{ Date::nice($item->get_date()) }}</small><br /><br />
                    <p>
                    	{{ $item->get_description() }}
                    </p>
                  </div>
                </div>
              </li>
            @endforeach
            </ul>
		</section>
	</div>
</div>
<?
/*
********************************************
THIS FUNCTION IS MOVING IN AT THE DASHBOARD.
********************************************

Awaiting moving trucks...

<div class="grid-24">
	<div class="widget">			
		
		<div class="widget-header">
			<h3>{{ __('accreditation.undelivered_badges') }}</h3>
		</div>

		<div class="widget-content">
			<div class="field-group">
				<table class="table">
					<thead>
						<tr>
							<th style="width: 15%;">{{ __('accreditation.delivery_deadline') }}</th>
							<th style="width: 10%;">{{ __('accreditation.badge_id') }}</th>
							<th>{{ __('user.name') }}</th>
							
					</thead>				
					<tbody>
						@foreach (Entry::where("type", "=", "badge")->where("status", "=", "valid")->order_by("created_at", "asc")->get() as $entry)
						<tr>
							<td>
								{{ Date::regular($entry->delivery_date) }}<br />
								<b>{{ Date::countdown($entry->delivery_date) }}</b>
							</td>
							<td>
								{{ $entry->badge_id }}
							</td>
							<td class="description">
								<a href="">{{ $entry->person()->firstname }} {{ $entry->person()->surname }}</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

*/