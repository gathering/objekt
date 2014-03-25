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
<span class="btn btn-success btn-lg fileinput-button">
    <i class="icon-plus"></i>
    <span>{{ __('mediabank.upload') }}</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload" type="file" name="files[]" multiple="">
</span>
<hr />