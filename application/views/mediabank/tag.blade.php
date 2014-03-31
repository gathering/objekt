@section('scripts')
<script src="{{ asset('js/prettyphoto/jquery.prettyPhoto.js') }}"></script>  
<script src="{{ asset('js/grid/jquery.grid-a-licious.min.js') }}"></script>
<script src="{{ asset('js/grid/gallery.js') }}"></script>
<script>
$(function() {
    $(".item").each(function(index){
        var $item = $(this);
        var $modal = $(this).find('.modal');
        var $pillbox = $(this).find('.pillbox');
        var $pillbox_content = $(this).find('.pillbox_content');
        var $infobox_tags = $(this).find('.infobox_tags');
        var $filename = $(this).find('.filename');
        var $overview_title = $(this).find('.overview_title');
        var $file_id = $(this).attr('id');
        var $delete_button = $(this).find('.delete');

        $delete_button.click(function(){
            var confirmDelete = confirm('{{ __('mediabank.delete_confirm') }}');
            if(confirmDelete){
                $.ajax({
                    type: "POST",
                    url: "{{ url('mediabank/delete_file/') }}" + $file_id
                }).done(function(data){
                    $modal.hide();
                    $item.remove();
                    Messenger().post({
                      message: '{{ __('mediabank.file_delete') }}',
                      type: 'success'
                    });
                }).fail(function() {
                    Messenger().post({
                      message: '{{ __('mediabank.file_failed_delete') }}',
                      type: 'error'
                    });
                });
            }           
        });

        $pillbox.on( "change click removed added", function($item) {
            var items = $(this).pillbox('items');
            var items_formated = new Array();

            $infobox_tags.html('');
            $.each(items, function( index, data ) {
                $tag_link = $('<a style="color: inherit"/>');
                $tag_link.attr('href', '{{ url('mediabank/tag') }}/' + data.text);
                $tag_link.html(data.text);
                $infobox_tags.append($('<div class="badge"/>').append($tag_link));
                items_formated.push(data.text);
            });

            items_formated.join(",");
            $pillbox_content.val(items_formated);
        });

        $filename.keyup(function(){
            $overview_title.html($(this).val());
        });

        $(this).on('hidden.bs.modal', function(e){
            if($item){
                $.ajax({
                    type: "POST",
                    url: "{{ url('mediabank/update_file/') }}" + $file_id,
                    data: { filename: $filename.val(), tags: $pillbox_content.val() }
                }).done(function(data){
                    Messenger().post({
                      message: '{{ __('mediabank.data_saved') }}',
                      type: 'success'
                    });
                }).fail(function() {
                    Messenger().post({
                      message: '{{ __('mediabank.data_failed_saved') }}',
                      type: 'error'
                    });
                });
            }
        });
    });
});
</script>
@endsection
<section id="content">
    <section class="main">
        <div class="page-title">
            <form method="post" action="{{ url('mediabank/search') }}">
                {{ __('mediabank.search') }}
                <div class="input-group" style="margin-top: 5px;">
                    <input type="text" name="search" {{ isset($term) ? "value=\"".$term."\"" : "" }}placeholder="{{ __('mediabank.placeholder.search') }}" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
        <div class="m-t">
            <div id="gallery" class="gallery hide">
                @foreach($results as $result)
                @if($result->thumbnail())
                <div class="item" id="{{ $result->id }}">
                    <a data-toggle="modal" data-target=".image-box-{{ $result->id }}" href="#" class="item-media"><img src="{{ $result->thumbnail()->url }}"></a>
                    <div class="desc">
                        <h4 class="overview_title" title="{{ $result->filename }}">{{ Str::limit($result->filename, 15) }}</h4>
                        <p class="text-muted">
                            <div class="infobox_tags">
                                @foreach($result->tags() as $tag)
                                <div class="badge"><a href="{{ url('mediabank/tag/'.strtolower($tag)) }}" style="color: inherit">{{ strtolower($tag) }}</a></div>
                                @endforeach
                            </div>
                        </p>
                    </div>
                    <div class="modal fade image-box-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $result->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                            <input type="text" class="modal-title filename" value="{{ $result->filename }}" />
                          </div>
                          <div class="modal-body">
                            <div class="preview" style="background-image: url('{{ $result->thumbnail()->url }}');">
                                <a href="{{ $result->url }}" target="_blank" class="btn btn-default"><i class="fa fa-arrows-alt"></i> {{ __('mediabank.show_large') }}</a>
                                <? $exif = $result->camera_exif(); ?>
                                @if($exif)
                                <div class="infobox">
                                    @if(isset($exif['Model']))
                                    <div><i class="fa fa-camera"></i> {{ $exif['Model'] }}</div>
                                    @endif
                                    @if(isset($exif['Software']))
                                    <div><i class="fa fa-magic"></i> {{ $exif['Software'] }}</div>
                                    @endif
                                    @if(isset($exif['DateTime']))
                                    <div><i class="fa fa-clock-o"></i> {{ Date::nice($exif['DateTime']) }}</div>
                                    @endif                                    
                                </div>
                                @endif
                            </div>
                            <div class="pillbox clearfix m-b">
                              <ul>
                                @foreach($result->tags() as $tag)
                                <li class="label bg-default">{{ $tag }}</li>
                                @endforeach<input type="text" placeholder="{{ __('mediabank.add_tag') }}">
                              </ul>
                            </div>
                            <input type="hidden" class="pillbox_content" value="{{ implode(",", $result->tags()) }}" />
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="delete btn btn-danger">{{ __('mediabank.delete') }}</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('mediabank.close') }}</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
</section>