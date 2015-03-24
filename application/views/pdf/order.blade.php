@section('header')
<h1>Ordrebekreftelse #{{ $order->id() }}</h1>
@endsection
@section('content')
<table>
	<tr class="header">
		<td width="5%">Artikkelnr</td>
		<td>Beskrivelse</td>
		<td width="10%">Antall</td>
		<td width="17%">Enhetspris</td>
		<td width="17%">Totalt</td>
	</tr>
	@foreach($order->cart()->all() as $item)
	<tr>
		<td>{{ $item->sku }}</td>
		<td><b>{{ $item->name }}</b></td>
		<td>{{ $item->quantity }}</td>
		<td>{{ Format::money($item->getSinglePrice()) }}</td>
		<td>{{ Format::money($item->getTotalPrice()) }}</td>
	</tr>
	@endforeach
</table>
<?php $primaryContact = $order->event()->primary_contact(); ?>
<p>Det er gjort avtale mellom arrangør av <b>{{ $order->event()->name }}</b>
	@if($primaryContact)
	 ved <b>{{ $primaryContact->name }}</b> (telefon: {{ Format::phone($primaryContact->phone) }})
	@endif
	 i dag, {{ Date::regular($order->created_at) }}, og <b>{{ $order->profile()->name }}</b> om leveranse av de overstående ordrelinjer. Dette er bekreftet elektronisk av <b>{{ $order->person()->firstname }} {{ $order->person()->surname }}</b>. En elektronisk faktura kan bli sendt etter arrangementet med minimum ti dagers forfall.</p>
@endsection