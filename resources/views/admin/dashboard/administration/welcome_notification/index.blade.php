@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin_welcome_notification_save') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <textarea class="ckeditor" name="data" id="buyer_home" rows="10" cols="80"> @if(!empty($setting->value)) {{ $setting->value }} @endif </textarea>
                    </div>
                </div>

                <br>

                <div class="text_right m15">
                    <div class="display_inline mr_0">
                        <button type="submit" class="ly_btn  btn_blue min_width_100p " id="btnOrderNoticeSubmit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    <script>
        $(function () {
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);
        });

        var options = {
            filebrowserImageBrowseUrl: '{{ url('laravel-filemanager') }}?type=Images',
            filebrowserImageUploadUrl: '{{ url('laravel-filemanager') }}/upload?type=Images&_token=',
            filebrowserBrowseUrl: '{{ url('laravel-filemanager') }}?type=Files',
            filebrowserUploadUrl: '{{ url('laravel-filemanager') }}?type=Files&_token='
        };

        CKEDITOR.config.allowedContent = true;
        CKEDITOR.replace('buyer_home', options);
    </script>
@stop
