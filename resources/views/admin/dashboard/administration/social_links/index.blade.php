@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <form class="form-horizontal" id="form" method="post" action="{{ route('admin_social_links_add_post') }}">
        @csrf
        <div class="ly_card social_card">
            <div class="ly_card_heading">
                <h5 class="mb-0"> Add / Update Social links </h5>
            </div>
        </div>

        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly-12 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('facebook') ? ' has-danger' : '' }}">
                                <label for="facebook" class="col-form-label">Facebook Link</label>
                            </div>
                            <div class="form_inline">
                                <input type="text" id="facebook" class="form_global{{ $errors->has('facebook') ? ' is-invalid' : '' }}"
                                    placeholder="https://facebook.com/YOUR_PROFILE_ID" name="facebook" value="{{ old('facebook') == '' ? isset($socialLinks[0]->facebook) ? $socialLinks[0]->facebook : '' : '' }}">
                            </div>
                        </div>

                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('instagram') ? ' has-danger' : '' }}">
                                <label for="instagram" class="col-form-label">Instagram Link</label>
                            </div>
                            <div class="form_inline">
                                <input type="text" id="instagram" class="form_global{{ $errors->has('instagram') ? ' is-invalid' : '' }}"
                                    placeholder="https://www.instagram.com/YOUR_PROFILE_ID" name="instagram" value="{{ old('instagram') == '' ? isset($socialLinks[0]->instagram) ? $socialLinks[0]->instagram : '' : '' }}">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('pinterest') ? ' has-danger' : '' }}">
                                <label for="pinterest" class="col-form-label">Pinterest Link</label>
                            </div>
                            <div class="form_inline">
                                <input type="text" id="pinterest" class="form_global{{ $errors->has('pinterest') ? ' is-invalid' : '' }}"
                                    placeholder="https://www.pinterest.com/YOUR_PROFILE_ID" name="pinterest" value="{{ old('pinterest') == '' ? isset($socialLinks[0]->pinterest) ? $socialLinks[0]->pinterest : '' : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text_right m15">
            <div class="display_inline mr_0">
                <button type="submit" class="ly_btn  btn_blue min_width_100p " id="btnSubmit">Submit</button>
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
