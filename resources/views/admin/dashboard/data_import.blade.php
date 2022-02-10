@extends('admin-v2.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="m15">
        <div class="display_inline mr_0">            
            <button class="ly_btn  btn_blue min_width_100p" id="btnImport">Import from Excel File</button>
            <a class="ly_btn  btn_danger min_width_100p" href="{{ asset('files/sample-file.xlsx') }}" target="_blank">Download Sample File</a>

            <form action="{{ route('admin_data_import_read_file') }}" id="form" method="post" enctype="multipart/form-data">
                <input class="d_none" type="file" id="file" name="file">
            </form>
        </div>
    </div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function () {
            var message = '{{ session('message') }}';
            var error = '{{ session('error') }}';

            if (message != '')
                toastr.success(message);

            if (error != '')
                toastr.error(error);

            $('#btnImport').click(function () {
                $('#file').click();
            });

            $('#file').change(function () {
                file = $(this).prop('files')[0];

                if (typeof file !== "undefined") {
                    $('#form').submit();
                }
            });
        });
    </script>
@stop