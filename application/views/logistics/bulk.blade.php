@layout('logistics.template')
@section('scripts')
<script>
$(function() {
	$(window).bind('beforeunload', function(){
	  return '{{ __('logistics.beforeunload') }}';
	});

	function saveLine(element, parent, e){
		var $this = element,
			$par = parent;

		var controlled = false;
		var data = {};
		@foreach($staticFields as $name => $value)
data['{{ $name }}'] = {{ json_encode($value) }};
		@endforeach

		$par.find('input').each(function(index){
			if($( this ).val()){
				data[$(this).attr('name')] = $(this).val();
				controlled = true;
			} else {
				controlled = false;
			}
		});

		if(!controlled){
			e.preventDefault();

			Messenger().post({
		      message: '{{ __('logistics.bulk_missing_field') }}',
		      type: 'error'
		    });
		}

		if(controlled){
			$par.find('.status').html('<img src="{{ asset('images/loaders/facebook.gif') }}" alt="" />');

			if($par.attr('data-id') == undefined){
				$.ajax({
					url: '{{ url('logistics/'.$storage->slug.'/add_parcel/bulkline') }}',
					type: "POST",
					data: data
				}).always(function(){

				}).success(function(data){
					var $link = $("<a/>")
								.addClass('btn btn-primary')
								.attr('target', '_blank')
								.attr("href", "{{ url('logistics/'.$storage->slug.'/') }}" + data.id)
								.html('<i class="fa fa-info"></i>');

					$par.find('.status')
						.html('<i class="fa fa-check"></i> ')
						.append($link);
					$par.find('input').on('blur', function(e){
						saveLine($(this), $par, e);
					});

					$par.attr('data-id', data.id);
				}).fail(function(){
					$par.find('.status')
						.html('<i class="fa fa-times-circle"></i>')
						.css('cursor', 'pointer')
						.on('click', function(e){
							saveLine($this, $par, e);
						});
				});
			} else {
				data.id = $par.attr('data-id');
				$.ajax({
					url: '{{ url('logistics/'.$storage->slug.'/edit_bulkline') }}',
					type: "POST",
					data: data
				}).always(function(){

				}).success(function(data){
					var $link = $("<a/>")
								.addClass('btn btn-primary')
								.attr('target', '_blank')
								.attr("href", "{{ url('logistics/'.$storage->slug.'/') }}" + data.id)
								.html('<i class="fa fa-info"></i>');

					$par.find('.status').html('<i class="fa fa-check"></i> ').append($link);
					$par.attr('data-id', data.id);
				}).fail(function(){
					$par.find('.status')
						.html('<i class="fa fa-times-circle"></i>')
						.css('cursor', 'pointer')
						.on('click', function(e){
							saveLine($this, $par, e);
						});
				});
			}

			if($par.is('tr:last')){
				var $newLine = $lineTemplate.clone();
				$newLine.find('input:last').on('keydown', newLine);
				$("#lines").append($newLine);
			}			
		}
	}

	function newLine(e){
		var keyCode = e.keyCode || e.which;
		var $par = $(this).parent().parent();
		if (keyCode == 9 || keyCode == 13) {
			saveLine($(this), $par, e);
		} 
	}

	var $lineTemplate = $("#firstLine").clone();
	$lineTemplate.attr("id", "");

	$("#firstLine input:last").on('keydown', newLine);
});
</script>
<style>
	.status {
		font-size: 22px;
		color: #000;
		width: 120px;
		text-align: center;
	}
</style>
@endsection
@section('content')
<section class="panel">
	<div class="wizard clearfix">
	  <ul class="steps">
	    <li><span class="badge">1</span>{{ __('logistics.parcel_type') }}</li>
	    <li><span class="badge">2</span><span class="name">{{ __('logistics.parcel') }}</span></li>
	    <li class="active"><span class="badge badge-info">3</span>{{ __('logistics.bulking') }}</li>
	  </ul>
	</div>
	<div class="step-content">
		<div class="step-pane active">
			<table class="table">
				<thead>
					<tr>
						@if(in_array("name", $fields))
						<th>{{ __('logistics.parcel_name') }}</th>
						@endif
						@if(in_array("description", $fields))
						<th>{{ __('logistics.description') }}</th>
						@endif
						@if(in_array("comment", $fields))
						<th>{{ __('logistics.comment') }}</th>
						@endif
						@if(in_array("owner", $fields))
						<th>{{ __('logistics.owner') }}</th>
						@endif
						@if(in_array("tags", $fields))
						<th>{{ __('logistics.tags') }}</th>
						@endif
						@if(in_array("serialnumber", $fields))
						<th>{{ __('logistics.serialnumber') }}</th>
						@endif
						<th>{{ __('logistics.bulk_status') }}</th>
					</tr>
				</thead>
				<tbody id="lines">
					<tr id="firstLine" class="line">
						@foreach($fields as $field)
						<td><input type="text" name="{{ $field }}" class="form-control" /></td>
						@endforeach
						<td class="status"><i class="fa fa-times-circle"></i></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>
@endsection