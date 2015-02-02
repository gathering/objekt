@layout('partner.layouts.profile')
@section('navigation')
<a href="{{ url('partner/accreditation/add') }}" data-toggle="modal" data-target="#modal" class="list-group-item">
  <i class="fa fa-plus"></i> {{ __('partner.accreditation.add') }}
</a>
@endsection
@section('content')
<!-- /.modal -->
<div class="tab-pane active padder">
<section class="panel">
  <header class="panel-heading">
    {{ __('partner.accreditation.heading')}}
    <ul class="nav nav-pills pull-right">
      <li>
        <a href="{{ url('partner/accreditation/add') }}" data-toggle="modal" data-target="#modal" class="panel-toggle text-muted">
          <i class="fa fa-plus fa-lg"></i> {{ __('partner.accreditation.add') }}
        </a>
      </li>
    </ul>
  </header>
  <div>
    <table class="table table-striped m-b-none text-small">
      <thead>
        <tr>
          <th style="width:90%">Navn</th>
          <th>Status</th>
          <th></th>         
        </tr>
      </thead>
      <tbody>
        @foreach(partnerAuth::user()->profile()->person()->get() as $person)
        <tr>                    
          <td style="vertical-align: middle;">
            @if($person->contact_person == 1)
            <span class="label label-success" title="Kontaktperson"><i class="fa fa-star"></i></span>
            @endif
            {{ $person->firstname }} {{ $person->surname }}</td>
          <td style="text-align: center;">
            @if ($person->status == "registred")
            <div class="text-warning m-t-small">
              <i class="fa fa-circle"></i>
            </div>
            @endif
            @if ($person->status == "arrived")
            <div class="text-success m-t-small">
              <i class="fa fa-circle"></i>
            </div>
            @endif
            @if ($person->status == "departed")
            <div class="text-danger m-t-small">
              <i class="fa fa-circle"></i>
            </div>
            @endif
          </td>
          <td class="text-right">
            <div class="btn-group pull-right">
              <a href="#" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-pencil"></i></a>
              <ul class="dropdown-menu" style="text-align: left;">
                <li><a href="{{ url('partner/accreditation/edit/'.$person->id) }}" data-toggle="modal" data-target="#modal"><i class="fa fa-pencil"></i> Rediger</a></li>
                <li><a href="{{ url('partner/accreditation/delete/'.$person->id) }}" data-toggle="modal" data-target="#modal"><i class="fa fa-remove"></i> Fjern</a></li>
                @if(partnerAuth::user()->id !== $person->id)
                <li class="divider"></li>
                @if($person->contact_person == 1)
                <li><a href="{{ url('partner/accreditation/demote/'.$person->id) }}"><i class="fa fa-star-o"></i> Nedgrader til vanlig personell</a></li>
                @else
                <li><a href="{{ url('partner/accreditation/promote/'.$person->id) }}" data-toggle="modal" data-target="#modal"><i class="fa fa-star"></i> Utnevn til kontaktperson</a></li>
                @endif
                @endif
              </ul>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>
</div>
@endsection