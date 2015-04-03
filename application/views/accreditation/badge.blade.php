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
});
</script>
@endsection

<section class="panel">
	<div class="panel-body">
	  <form class="form-horizontal" method="post">  
	  	<h3>{{ __('accreditation.badge_details') }}</h3>    <br /><br />
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('accreditation.badge_id') }}</label>
	      <div class="col-lg-8">
	        <input type="text" name="badge_id"  tabindex="1" placeholder="{{ __('accreditation.badge_id') }}" class="form-control" autocomplete="off">
	      </div>
	    </div>
	    @if (Auth::user()->can("accreditation_print_badge"))
	    <div class="form-group">
	      <div class="col-lg-8 col-lg-offset-3">
	        <div class="checkbox">
				<label class="checkbox-custom">
					<input type="checkbox" name="automatic" checked="checked" value="1">
					<i class="fa fa-check-square-o checked"></i>
					{{ __('accreditation.automatic') }}
				</label>
			</div>
	      </div>
	    </div>
	    @endif
	    <div class="form-group">
	      <label class="col-lg-3 control-label">{{ __('accreditation.delivery_deadline') }}</label>
	      <div class="col-lg-8">
	        <button type="button" class="btn btn-info time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", (time()+10800)) }}">
	        	{{ __('accreditation.select.3hours') }}<br />
	        	<span class="text-muted" style="color: #ccc;">{{ Date::nice(date("Y-m-d H:i:s", (time()+10800))) }}</span>
	        </button>
	        <button type="button" class="btn btn-info time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", (time()+21600)) }}">
	        	{{ __('accreditation.select.6hours') }}<br />
	        	<span class="text-muted" style="color: #ccc;">{{ Date::nice(date("Y-m-d H:i:s", (time()+21600))) }}</span>
	        </button>
	        <button type="button" class="btn btn-info time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", (time()+86400)) }}">
	        	{{ __('accreditation.select.24hours') }}<br />
	        	<span class="text-muted" style="color: #ccc;">{{ Date::nice(date("Y-m-d H:i:s", (time()+86400))) }}</span>
	        </button>
	        <? 	$event = Config::get('application.event');
	        	$dt_end = new DateTime($event->to_date);
	           	$diff = $dt_end->diff(new DateTime(date("Y-m-d H:i:s"))); ?>
	        @for ($i = 1; $i <= $diff->d; $i++)
	        <? $orgtime = time()+(86400*$i); $time = mktime(8, 0, 0, date("n", $orgtime), date("j", $orgtime), date("Y", $orgtime)); ?>
	        <button type="button" class="btn btn-info time-select" data-selector="input[name=delivery_deadline]" data-time="{{ date("d.m.Y H:i", $time) }}">
	        	{{ __('accreditation.to') }}<br />
	        	<span class="text-muted" style="color: #ccc;">{{ Date::nice(date("Y-m-d H:i:s", $time)) }}</span>
	        </button>
	        @endfor
	      </div>
	    </div>
	    <div class="form-group">
			<div class="col-lg-8 col-lg-offset-3 input-group date" id="delivery_deadline">
				<input class="form-control" type="text" name="delivery_deadline" tabindex="4" value="{{ __('accreditation.placeholder.delivery_deadline') }}" placeholder="{{ __('accreditation.placeholder.delivery_deadline') }}" autocomplete="off">
				<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
			</div>
	    </div>
	    <div class="form-group" style="margin-bottom: 40px;">
          <div class="col-lg-9 col-lg-offset-3">                      
            <button type="submit" class="btn btn-primary">{{ __('accreditation.accredit') }}</button>
          </div>
        </div>
      </form>
    </div>
</section>
