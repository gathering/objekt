@section('scripts')
<script src="{{ asset('js/prettyphoto/jquery.prettyPhoto.js') }}"></script>  
<script src="{{ asset('js/grid/jquery.grid-a-licious.min.js') }}"></script>
<script src="{{ asset('js/grid/gallery.js') }}"></script>
@endsection
<section id="content">
    <section class="main">
        <div class="page-title">
            <form method="post">
                {{ __('mediabank.search') }}
                <div class="input-group" style="margin-top: 5px;">
                    <input type="text" name="comment" placeholder="{{ __('mediabank.placeholder.search') }}" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-primary" onclick="alert('Søk i topbaren så lenge. Fungerer ikke enda.'); return false;" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
        <div class="m-t">
            <div id="gallery" class="gallery hide">
                @foreach($results as $result)
                @if($result->thumbnail())
                <div class="item">
                    <a href="{{ $result->url }}" target="_blank" class="item-media"><img src="{{ $result->thumbnail()->url }}"></a>
                    <div class="desc">
                        <h4 title="{{ $result->filename }}">{{ Str::limit($result->filename, 15) }}</h4>
                        <p class="text-muted">
                            {{ $result->camera() ? $result->camera()."<br />" : "" }}
                            @foreach($result->tags() as $tag)
                            <div class="badge"><a href="{{ url('mediabank/tag/'.strtolower($tag)) }}" style="color: inherit">{{ strtolower($tag) }}</a></div>
                            @endforeach
                        </p>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
</section>