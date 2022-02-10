@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <form class="form-horizontal" id="form" method="post" action="{{ route('admin_social_feed_add_post') }}">
        @csrf
        <div class="ly_card social_card">
            <div class="ly_card_heading">
                <h5 class="mb-0"> Add / Update Social Feeds </h5>
            </div>
        </div>

        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly-12 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label for="instagram" class="col-form-label">Instagram Feeds : </label>
                            </div>
                            <div class="form_inline">
                                <input type="hidden" name="instagram" value="instagram">
                                <input type="text" id="" class="form_global" placeholder="Access Token" name="instagram_access_token" value="<?php echo $data_array['instagram']['access_token']?>">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('heading') ? ' has-danger' : '' }}">
                                <div class="label_inline required width_150p">Section Heading</div>
                            </div>
                            <div class="form_inline">
                                <input type="text" class="form_global {{ $errors->has('heading') ? ' is-invalid' : '' }}" name="heading" value="{{ $section_heading->heading }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text_right m15">
            <div class="display_inline mr_0">
                <button type="submit" class="ly_btn  btn_blue min_width_100p " id="btnSubmit">Save or update</button>
            </div>
        </div>
    </form>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>

        var message = '{{ session('message') }}';
        if (message != '')
            toastr.success(message);

    </script>
@stop
