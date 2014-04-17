@layout('pdf.layouts.default')
@section('content')
	<br /><br /><br />
	<table>
		<tr border-color="#FFFFFF">
			<td border-color="#FFFFFF" text-align="right" width="60px" font-style="bold" color="#FFFFFF" background.color="#13c4a5">{{ __('memo.to') }}</td>
			<td border-color="#FFFFFF">{{ __('memo.to_default') }}</td>
		</tr>
		<tr>
			<td border-color="#FFFFFF" text-align="right" font-style="bold" color="#FFFFFF" background.color="#13c4a5">{{ __('memo.from') }}</td>
			<td border-color="#FFFFFF">{{ Auth::user()->display_name() }}</td>
		</tr>
		<tr>
			<td border-color="#FFFFFF" text-align="right" font-style="bold" color="#FFFFFF" background.color="#13c4a5">{{ __('memo.date') }}</td>
			<td border-color="#FFFFFF">{{ Date::regular(date("Y-m-d H:i:s")) }}</td>
		</tr>
	</table>
	<h1>{{ isset($title) ? $title : __('memo.title_default') }}</h1>
	<p>
		<? $i=0; ?>
		@foreach(explode("\n", wordwrap(htmlspecialchars($content), 100)) as $line)
		<span color="#CCCCCC">L{{ sprintf("%02s", $i) }} ...</span> {{ $line }}<br />
		<? $i++; ?>
		@endforeach
	</p>
@endsection