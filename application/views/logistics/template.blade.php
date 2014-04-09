@section('custom_scripts')
<script>
$(function() {
    $("#logisticSearch").focus();
});
</script>
@endsection
<section id="content">
    <section class="main">
        <div class="page-title">
            <form method="post" action="{{ url('logistics/'.$storage->slug.'/search') }}">
                {{ sprintf(__('logistics.search'), $storage->name) }}
                <div class="input-group" style="margin-top: 5px;">
                    <input type="text" id="logisticSearch" name="search" placeholder="{{ __('logistics.placeholder.search') }}" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
        <div class="padder">
            @yield('content')
        </div>
    </section>
</section>