@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin_top_notification_save') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label for=""> Top Notification abckground color</label>
                        <input value="@if($setting->desc) {!! $setting->desc !!} @endif" name="notification_bg" class="jscolor form-control">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <textarea class="d-none" name="data" id="buyer_home" rows="2">@if($setting->value) {!! $setting->value !!} @endif</textarea>
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


            var options = {
                filebrowserImageBrowseUrl: '{{ url('laravel-filemanager') }}?type=Images',
                filebrowserImageUploadUrl: '{{ route('unisharp.lfm.upload') }}?type=Images&_token=',
                filebrowserBrowseUrl: '{{ url('laravel-filemanager') }}?type=Files',
                filebrowserUploadUrl: '{{ url('laravel-filemanager') }}?type=Files&_token='
            };
            CKEDITOR.replace('buyer_home', options);
        });
    </script>
@stop
