@layout('logistics.template')
@section('content')
<div style="text-align: center;">
<section class="toolbar clearfix m-t-large m-b">
    @if (Auth::user()->can("add_parcel"))
    <a href="{{ url('logistics/'.$storage->slug.'/add_parcel') }}" class="btn btn-primary btn-circle">
        <i class="fa fa-plus"></i> {{ __('logistics.add_parcel') }}
    </a>
    <a href="{{ url('logistics/'.$storage->slug.'/reports') }}" class="btn btn-info btn-circle">
        <i class="fa fa-list-alt"></i> {{ __('logistics.reports') }}
    </a>
    <a href="{{ url('logistics/'.$storage->slug.'/search/filter') }}" class="btn btn-warning btn-circle">
        <i class="fa fa-search"></i> {{ __('logistics.filter_search') }}
    </a>
    @endif
</section>
</div>
@endsection