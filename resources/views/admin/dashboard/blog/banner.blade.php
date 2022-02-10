@extends('admin.layouts.main')

<?php
use App\Enumeration\VendorImageType;
?>

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')

    <form class="form-horizontal" id="form" method="post" action="{{ route('blog_banner_update') }}" enctype="multipart/form-data">
        @csrf
        <div class="ly_card banner_item_card">
            <div class="ly_card_heading">
                <h5 class="mb-0"> Blog Banner</h5>
            </div>
        </div>
        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly-3 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline required width_150p">
                                <label for="photo" class="col-form-label">Blog List Page Banner : </label>
                            </div>
                        </div>
                    </div>
                    <div class="ly-5 pl_0 pl_60">
                        <div class="form_row">
                            <div class="form_inline">
                                <input type="file" id="image" class="form_global{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image">
                            </div>
                            @if ($errors->has('image'))
                                <div class="form-control-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="ly-4 pl_0 pl_60">
                        <img width="100px" src="{{ $bloglistbanner ? asset($bloglistbanner->image) : null }}" alt="">
                    </div>
                </div>

                <div class="ly-row">
                    <div class="ly-3 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline required width_150p {{ $errors->has('banner') ? ' has-danger' : '' }}">
                                <label for="photo" class="col-form-label">Blog Single Page Banner : </label>
                            </div>
                        </div>
                    </div>
                    <div class="ly-5 pl_0 pl_60">
                        <div class="form_row">
                            <div class="form_inline">
                                <input type="file" id="banner" class="form_global{{ $errors->has('banner') ? ' is-invalid' : '' }}" name="banner">
                            </div>
                            @if ($errors->has('banner'))
                                <div class="form-control-feedback">{{ $errors->first('banner') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="ly-4 pl_0 pl_60">
                        <img width="100px" src="{{ $blogsinglebanner ? asset($blogsinglebanner->image) : null }}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="text_right m15">
            <div class="display_inline mr_0">
                <button type="submit" class="ly_btn  btn_blue min_width_100p " id="btnSubmit">Save</button>
            </div>
        </div>
    </form>

    <br>


@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/sortable/js/Sortable.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        })
    </script>
@stop
