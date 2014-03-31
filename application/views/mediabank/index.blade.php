@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.2/css/jquery.fileupload-ui.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.2/css/jquery.fileupload.css">
@endsection
@section('scripts')
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.2/jquery.fileupload.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.2/jquery.fileupload-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.2/jquery.fileupload-process.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.2/jquery.fileupload-validate.min.js"></script>
<script>
$(function () {
    'use strict';
    var url = "";
    var message;

    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(jpeg|jpg)$/i,
        maxFileSize: 35000000, // 5 MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent)
    }).on('fileuploadadd', function (e, data) {
        $.each(data.files, function (index, file) {
		    data.messagebox = Messenger().post({
		      message: '{{ __('mediabank.fileuploading') }} ' + file.name + '.<br /><div id="progress" class="progress"><div class="progress-bar progress-bar-success"></div></div>',
		      type: 'info',
		      hideAfter: 999999,
		    });
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            message = data.messagebox;

        if (file.error) {
            message.update({
			  type: "error",
			  hideAfter: 999999,
		  	  showCloseButton: true,
			  message: '{{ __('mediabank.not_supported') }}'
			});
			return false;
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
    	var message = data.messagebox;
        message.update({
		  type: "success",
		  hideAfter: 10,
		  message: '{{ __('mediabank.fileuploaddone') }}'
		});
    }).on('fileuploadfail', function (e, data) {
    	var message = data.messagebox;
    	message.update({
		  type: "error",
		  hideAfter: 999999,
		  showCloseButton: true,
		  message: '{{ __('mediabank.fileuploadfail') }}'
		});
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

});
</script>
@endsection
<section id="content">
    <section class="main">
        <div class="page-title">
            <form method="post" action="{{ url('mediabank/search') }}">
                {{ __('mediabank.search') }}
                <div class="input-group" style="margin-top: 5px;">
                    <input type="text" name="search" placeholder="{{ __('mediabank.placeholder.search') }}" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
        <div class="padder">
            <div class="row">
                <div class="col-lg-2">
                    <span class="btn btn-primary btn-lg fileinput-button" style="width: 100%">
                        <i class="icon-plus"></i>
                        <span>{{ __('mediabank.upload') }}</span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input id="fileupload" type="file" name="files[]" multiple="">
                    </span><br /><br />
                    <div class="alert alert-success">
                        {{ sprintf(__('mediabank.description.amount_files'), Fil3::where("type", "=", "mediabank")->count()) }}
                    </div>
                    <br />
                    @foreach($event->tags() as $tag)
                    @if(!empty($tag))
                    <span class="badge"><a href="{{ url('mediabank/tag/'.strtolower($tag)) }}" style="color: inherit">{{ strtolower($tag) }}</a></span>
                    @endif
                    @endforeach
                </div>
                <div class="col-lg-10">
                    <section class="panel">
                        <header class="panel-heading bg bg-inverse">
                          {{ __('mediabank.profile_tags') }}
                        </header>
                        <div class="list-group">
                            @foreach(Profile::all() as $profile)
                            <span class="list-group-item bg-lighter">
                                @foreach($profile->tags() as $tag)
                                <span class="badge"><a href="{{ url('mediabank/tag/'.$tag) }}" style="color: inherit">{{ $tag }}</a></span>
                                @endforeach
                                {{ $profile->name }}
                            </span>
                            @endforeach
                        </div>
                  </section>
                  <div class="alert alert-info">
                    <i class="icon-info-sign icon-large"></i>
                    {{ __('mediabank.description.profile_tags') }}
                </div>
                <div class="alert alert-info">
                    <i class="icon-info-sign icon-large"></i>
                    {{ sprintf(__('mediabank.description.user_tag'), Auth::user()->username) }}
                </div>
                </div>
            </div>
        </div>
    </section>
</section>