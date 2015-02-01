<section class="panel">
  <header class="panel-heading">
    {{ __('partner.accreditation.heading')}}
    <ul class="nav nav-pills pull-right">
      <li>
        <a href="#" class="panel-toggle text-muted">
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
          <td style="vertical-align: middle;">{{ $person->firstname }} {{ $person->surname }}</td>
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
            <div class="btn-group">
              <a href="#" class="btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-pencil"></i></a>
              <ul class="dropdown-menu pull-right">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
              </ul>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>