@layout('profiles.person_profile_template')
@section('scripts')
<script>
$(function() {
	$("input[name=delivery_deadline]").datetimepicker({
		useSeconds: false,
		language: 'no',
		icons: {
			time: "fa fa-clock-o",
	        date: "fa fa-calendar",
	        up: "fa fa-arrow-up",
	        down: "fa fa-arrow-down"
	    }
	});
	$(".time-select").each(function(i){
		$(this).click(function(){
			var $selector = $($(this).attr('data-selector'));
			var $time = $(this).attr('data-time');
			$selector.val($time);
		});
	});
	$('input[name=ident]').focus();
	$( document ).scannerDetection(function(ident){
      $('input[name=ident]').val(ident);
      $("#wristband").submit();
    });
});
</script>
@endsection
@section('content')
<h4>{{ $person->firstname }} {{ $person->surname }}</h4>
@if ($person->status != "arrived")
<div class="fluid-container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel-heading">
				<h1>Bånd / NFC-brikke</h1>
			</div>
			<section class="panel bg-inverse padder">
				<p>{{ __('profile.wristband_header') }}</p>
				<form method="post" id="wristband" action="{{ url($person->url('accreditation/wristband')) }}">
					<input type="text" autocomplete="off" name="ident" class="form-control" /><br />
					<button type="submit" class="btn btn-primary">{{ __('accreditation.accredit') }}</button>
				</form>
			</section>
			<div class="panel bg-info padder">
				<p>{{ __('profile.wristband_howto') }}</p>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel" style="border">
				<div class="panel-heading">
					<h1>Midlertidig pass</h1>
				</div>
				<section class="bg-inverse padder">
					<form class="form-horizontal" action="{{ url($person->url('accreditation/badge')) }}" method="post">
				    @if (Auth::user()->can("accreditation_print_badge"))
			        <input type="hidden" name="automatic" value="1">
				    @endif

			        <button type="button" class="btn btn-white btn-xs time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", (time()+10800)) }}">
			        	{{ __('accreditation.select.3hours') }}<br />
			        	<span class="text-muted" style="color: #333;">{{ Date::nice(date("Y-m-d H:i:s", (time()+10800))) }}</span>
			        </button>
			        <button type="button" class="btn btn-white btn-xs time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", (time()+21600)) }}">
			        	{{ __('accreditation.select.6hours') }}<br />
			        	<span class="text-muted" style="color: #333;">{{ Date::nice(date("Y-m-d H:i:s", (time()+21600))) }}</span>
			        </button>
			        <button type="button" class="btn btn-white btn-xs time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", (time()+86400)) }}">
			        	{{ __('accreditation.select.24hours') }}<br />
			        	<span class="text-muted" style="color: #333;">{{ Date::nice(date("Y-m-d H:i:s", (time()+86400))) }}</span>
			        </button>
			        <? 	$event = Config::get('application.event');
			        	$dt_end = new DateTime($event->to_date);
			           	$diff = $dt_end->diff(new DateTime(date("Y-m-d H:i:s"))); ?>
			        @for ($i = 1; $i <= $diff->d; $i++)
			        <? $orgtime = time()+(86400*$i); $time = mktime(8, 0, 0, date("n", $orgtime), date("j", $orgtime), date("Y", $orgtime)); ?>
			        <button type="button" class="btn btn-white btn-xs time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", $time) }}">
			        	{{ __('accreditation.to') }}<br />
			        	<span class="text-muted" style="color: #333;">{{ Date::nice(date("Y-m-d H:i:s", $time)) }}</span>
			        </button>
			        @endfor
			        <br /><br />
					<div class="input-group date" id="delivery_deadline">
						<input class="form-control" type="text" name="delivery_deadline" tabindex="4" value="{{ __('accreditation.placeholder.delivery_deadline') }}" placeholder="{{ __('accreditation.placeholder.delivery_deadline') }}" autocomplete="off">
						<span class="input-group-addon"><span style="color: black;" class="fa fa-calendar"></span></span>
					</div>
				    <div style="margin-top: 20px;">                    
			            <button type="submit" class="btn btn-primary">{{ __('accreditation.accredit') }}</button>
			        </div>
			      </form>
				</section>
			</div>
		</div>
	</div>
</div>
@elseif ($person->status == "departed")
<i>{{ __('accreditation.has_departed') }}</i>			
@else
<i>{{ __('accreditation.already_done') }}</i><br /><br />
<a href="{{ url($person->url('accreditation/departed')) }}" class="btn btn-danger">
	<span class="icon-remove-sign"></span>
	{{ __('accreditation.departed') }}
</a>
<a href="{{ url($person->url('accreditation/print')) }}" class="btn btn-primary">
	<span class="icon-print"></span>
	{{ __('accreditation.print') }}
</a>
@endif
@endsection