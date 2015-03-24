<table class="table">
  <thead>
    <tr>
      <th width="5%" class="hidden-xs">ARTIKKELNR</th>
      <th>BESKRIVELSE</th>
      <th width="10%">ANTALL</th>
      <th width="10%" class="hidden-xs">ENHETSPRIS</th>
      <th width="10%" style="min-width: 80px;" class="visible-xs">ENH.</th>
      <th width="10%" style="min-width: 80px;">TOTAL</th>
    </tr>
  </thead>
  <tbody>
    @foreach($cart->all() as $item)
    <tr>
      <td class="hidden-xs">{{ $item->sku }}</td>
      <td>
        <b>{{ $item->name }}</b>
      </td>
      <td>
        <form method="post" action="{{ url('partner/shop/edit_cart_item/'.$item->id) }}">
          <div class="hidden-xs">
            <input type="text" class="form-control" style="width: 50px; float: left;" name="quantity" maxlength="3" value="{{ $item->quantity }}" />
            <div class="btn-group" style="margin-top: 4px; margin-left: 10px; float: left;">
              <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-refresh"></i></button>
              <a href="{{ url('partner/shop/delete_cart_item/'.$item->id) }}" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>
            </div>
          </div>
          <div class="visible-xs">
            <div class="input-group">
              <input type="text" class="form-control" style="width: 50px; float: left;" name="quantity" maxlength="3" value="{{ $item->quantity }}" />
              <span class="input-group-btn">
                <button type="submit" class="visible-xs btn btn-info" style="float: left"><i class="fa fa-refresh"></i></button>
              </span>
            </div>
          </div>
        </form>
      </td>
      <td>{{ Format::money($item->getSinglePrice()) }}</td>
      <td>{{ Format::money($item->getTotalPrice()) }}</td>
    </tr>
    @endforeach
    <tr class="hidden-xs">
      <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
      <td>{{ Format::money($cart->totalExcludingTax()) }}</td>
    </tr>
    <tr class="hidden-xs">
      <td colspan="4" class="text-right no-border"><strong>Herav MVA</strong></td>
      <td>{{ Format::money($cart->tax()) }}</td>
    </tr>
    <tr class="hidden-xs">
      <td colspan="4" class="text-right no-border"><strong>Totalt</strong></td>
      <td><strong>{{ Format::money($cart->total()) }}</strong></td>
    </tr>
  </tbody>
</table>
<div class="visible-xs" style="text-align: center;">
  <div class="col-xs-4" style="padding-top: 20px; padding-bottom: 20px;">
    <b>Subtotal</b><br />
    <big>{{ Format::money($cart->totalExcludingTax()) }}</big>
  </div>
  <div class="col-xs-4" style="padding-top: 20px; padding-bottom: 20px;">
    <b>Herav MVA</b><br />
    <big>{{ Format::money($cart->tax()) }}</big>
  </div>
  <div class="col-xs-4" style="padding-top: 20px; padding-bottom: 20px; border-radius: 3px; background: #333; color: white;">
    <b>Totalt</b><br />
    {{ Format::money($cart->total()) }}
  </div>
</div>
<div class="col-md-4 pull-right">
  <br /><br />
  <div class="alert alert-info">
    <i class="fa fa-info-circle pull-left" style="font-size: 38px;"></i>
    Jeg, <b>{{ PartnerAuth::user()->firstname }} {{ PartnerAuth::user()->surname }}</b>, bekrefter herved ved å klikke «Jeg godtar» at jeg kan signere for overstående avtale for <b>{{ PartnerAuth::user()->profile()->name }}</b>. Jeg godtar også at Kreativ Aktiv Norsk DataUngdom (org.nr.: 988 148 245) kan sende faktura på dette elektronisk med ti dagers forfall.
  </div>
  <div style="text-align: center;" class="visible-xs">
    <a href="{{ url('partner/shop/finish') }}" class="btn btn-lg btn-primary">Jeg godtar</a><br /><br />
  </div>
  <i>Etter at du har bekreftet dette ved å klikke på «Jeg godtar», vil du vil motta en kvittering per e-post. Vi vil deretter varsle våre sponsorverter i <b>sanntid</b> at dette er ønsket.</i><br /><br />
  <a href="{{ url('partner/shop/finish') }}" class="btn btn-lg btn-primary pull-right hidden-xs">Jeg godtar</a>
</div>