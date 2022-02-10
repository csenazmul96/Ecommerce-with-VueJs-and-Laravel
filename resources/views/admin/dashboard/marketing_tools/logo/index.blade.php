<?php use App\Enumeration\VendorImageType; ?>
@extends('admin.layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin_logo_add_post') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="width_450p ">
                    <div class="form_row">
                        <div class="label_inline width_250p">
                            <label for="bigLogo">Front Logo:</label>
                        </div>
                        <div class="form_inline">
                            <input class="form_global{{ $errors->has('bigLogo') ? ' is-invalid' : '' }}"
                                   type="file" id="bigLogo" name="bigLogo" accept="image/*">

                            @if ($errors->has('bigLogo'))
                                <div class="form-control-feedback">{{ $errors->first('bigLogo') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="label_inline width_250p">
                            <label for="smallLogo">Admin Logo:</label>
                        </div>
                        <div class="form_inline">
                            <input class="form_global{{ $errors->has('smallLogo') ? ' is-invalid' : '' }}"
                                   type="file" id="smallLogo" name="smallLogo" accept="image/*">

                            @if ($errors->has('smallLogo'))
                                <div class="form-control-feedback">{{ $errors->first('smallLogo') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="label_inline width_250p">
                            <label for="smallLogo">Default Item Image:</label>
                        </div>
                        <div class="form_inline">
                            <input class="form_global{{ $errors->has('default_image') ? ' is-invalid' : '' }}" type="file" id="default_image" name="default_image" accept="image/*">
                            @if ($errors->has('default_image'))
                                <div class="form-control-feedback">{{ $errors->first('default_image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="create_item_bottom text_right m15">
                        <div class="display_inline  mr_0">
                            <input class="ly_btn  btn_blue min_width_100p" type="submit" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div class="modal" data-modal="modal-1">
        <div class="modal_overlay" data-modal-close="modal-1"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Are you sure want to delete?</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="modal-1"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_danger width_150p" id="modalBtnDelete">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <table class="table logo_preview">
            <thead>
            <tr>
                <th>Type</th>
                <th>Preview</th>
                <th>Upload Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($logoBig))
                <tr>
                    <td>@if($logoBig->name=='logo-big') Front Logo @endif</td>
                    <td style="background:#e3e3e3;"><img src="{{ asset($logoBig->value) }}" alt="" class="img-fluid"></td>
                    <td>@if(!empty($logoBig->created_at)) {{$logoBig->created_at}} @endif</td>
                    <td><a href="#" class="text-danger btnRemove" data-type="1" data-id="{{ $logoBig->id }}">Remove</a></td>
                </tr>
            @endif

            @if(!empty($logoSmalls))
                <tr>
                    <td>@if($logoSmalls->name=='logo-small') Admin Logo @endif</td>
                    <td style="background:#e3e3e3;"><img src="{{ asset($logoSmalls->value) }}" alt="" class="img-fluid"></td>
                    <td>@if(!empty($logoSmalls->created_at)) {{$logoSmalls->created_at}} @endif</td>
                    <td><a href="#" class="text-danger btnRemove" data-type="1" data-id="{{ $logoSmalls->id }}">Remove</a></td>
                </tr>
            @endif

            @if(!empty($defaultImage))
                <tr>
                    <td>@if($defaultImage->name=='default-item-image') Items Default Image @endif</td>
                    <td><img src="{{ asset($defaultImage->value) }}" alt="" class="img-fluid" width="100px"></td>
                    <td>@if(!empty($defaultImage->created_at)) {{$defaultImage->created_at}} @endif</td>
                    <td><a href="#" class="text-danger btnRemove" data-type="1" data-id="{{ $defaultImage->id }}">Remove</a></td>
                </tr>
            @endif
            </tbody>

        </table>

    </div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var type = '';
            var id = '';

            $('.btnRemove').click(function () {
                var targeted_modal_class = 'modal-1';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
                id = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_logo_remove') }}",
                    data: { type: type, id: id }
                }).done(function( msg ) {
                    location.reload();
                });
            });
        });
    </script>
@stop
