@extends('admin.layouts.main')

<?php
use App\Enumeration\VendorImageType;
?>

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <style>
        #smallBanner li{
            margin-right: 15px;
            float: left;
        }
    </style>
@stop

@section('content')
    <div class="m15">
        <div class="display_inline mr_0">
            <div class="item_color_heading_left">
                <span class="link mr_20 item_change_title" id="add_new_banner">+ Add New Banner</span>
            </div>
        </div>
    </div>
    <form class="form-horizontal {{ $errors && count($errors) > 0 ? null : 'd-none' }}" id="form" method="post" action="{{ route('admin_main_banner_add') }}" enctype="multipart/form-data">
        @csrf
        <div class="ly_card banner_item_card">
            <div class="ly_card_heading">
                <h5 class="mb-0" id="addEditTitle"> Add Main Banner</h5>
            </div>
        </div>
        <input type="hidden" name="bannerid" id="bannerid" value="">
        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly-12 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline required width_150p {{ $errors->has('image') ? ' has-danger' : '' }}">
                                <label for="photo" class="col-form-label">Upload Image : </label>
                            </div>
                            <div class="form_inline">
                                <input type="file" id="image" class="form_global{{ $errors->has('photo') ? ' is-invalid' : '' }}" name="image">
                            </div>
                            @if ($errors->has('image'))
                                <div class="form-control-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('link') ? ' has-danger' : '' }}">
                                <label for="link" class="col-form-label">Link : </label>
                            </div>
                            <div class="form_inline">
                                <input type="text" id="link" class="form_global{{ $errors->has('link') ? ' is-invalid' : '' }}" name="link">
                            </div>
                        </div>

                        <div class="form_row">
                            <div class="label_inline required width_150p ">
                                Type
                            </div>
                            <div class="form_inline {{ $errors->has('type') ? ' has-danger' : '' }}">
                                <div class="custom_radio">
                                    <input type="radio" id="large" class="type" name="type" value="2" {{ old('status') == '2' ? 'checked' : '' }}>
                                    <label for="large">For Large Device</label>
                                </div>
                                <div class="custom_radio">
                                    <input type="radio" id="small" name="type" class="type" value="3" {{ (old('status') == '3' || empty(old('status'))) ? 'checked' : '' }}>
                                    <label for="small">For Small Device</label>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('type'))
                            <div class="form-control-feedback">{{ $errors->first('type') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="text_right m15">
            <div class="display_inline mr_0">
                <button type="button" class="ly_btn  btn_blue min_width_100p " id="btnCancel">Cancel</button>
                <button type="submit" class="ly_btn  btn_blue min_width_100p " id="btnSubmit">Save</button>
            </div>
        </div>
    </form>

    <br>

    <div class="ly_page_wrapper">
        <div class="ly-wrap-fluid">
            <div class="ly-row">
                <div class="ly-12"><h4>Desktop Banners</h4></div>
                <hr class="border-bottom">
                <div class="ly-12">
                    <ul id="mainBanner" data-id="2">
                        @if(!empty($bannerImages))
                            @foreach($bannerImages as $image)
                                <li data-id="{{ $image->id }}">
                                    <img src="{{ asset($image->image) }}" alt="" class="banner_item">
                                    <div class="banner_edit">
                                        <span class="color_blue btnEdit" data-id="{{ $image->id }}"  data-type="2">Edit</span> |
                                        <span class="color_red btnRemove" data-id="{{ $image->id }}">Remove</span>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="ly_page_wrapper">
        <div class="ly-wrap-fluid">
            <div class="ly-row">
                <div class="ly-12"><h4>Mobile Banners</h4></div>
                <hr class="border-bottom">
                <div class="ly-12">
                    <ul id="smallBanner" data-id="3">
                        @if(!empty($images_mob))
                            @foreach($images_mob as $image)
                                <li data-id="{{ $image->id }}">
                                    <img src="{{ asset($image->image) }}" alt="" class="banner_item">
                                    <div class="banner_edit">
                                        <span class="color_blue btnEdit" data-id="{{ $image->id }}" data-type="3">Edit</span> |
                                        <span class="color_red btnRemove"  data-id="{{ $image->id }}">Remove</span>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div id="deleteModal" class="modal" data-modal="deleteModal">
        <div class="modal_overlay" data-modal-close="deleteModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_380p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Delete Confirmation</span>
                    </div>
                    <div class="modal_content pa15">
                        <p class="fw_500 ">Are you sure that you want to delete?</p>
                        <div class="form_row mb_0 pt_15">
                            <div class="form_inline">
                                <div class="text_right">
                                    <div class="display_inline mr_0">
                                        <button data-modal-close="deleteModal" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDelete">Yes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

            var bigBanners = <?php echo json_encode($bannerImages); ?>;
            var smallBanners = <?php echo json_encode($images_mob); ?>;

            $("#add_new_banner").click(function(){
                $('#addEditTitle').html('Add Main Banner');
                $('#btnSubmit').html('Save');
                $("#form").removeClass('d-none');
                $("#add_new_banner").addClass('d-none');
                $("#bannerid").val('');
                $("#link").val('');
                $('input:radio[name="type"][value="2"]').attr('checked',true);
            });

            $(document).on("click","#btnCancel",function() {
                $("#bannerid").val('');
                $("#link").val('');
                $('#form').addClass('d-none');
                $('#add_new_banner').removeClass('d-none');
            });

            var selectedId;
            var message = '{{ session('message') }}';
            if (message != '')
                toastr.success(message);
            let sortType = null;

            var el = document.getElementById('mainBanner');
            Sortable.create(el, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    sortType = $(el).data('id')
                    updateSort(this.toArray());
                },
            });

            var elsmll = document.getElementById('smallBanner');
            Sortable.create(elsmll, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    sortType = $(elsmll).data('id')
                    updateSort(this.toArray());
                },
            });

            function updateSort(ids) {
                console.log(sortType)
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_banner_sort') }}",
                    data: { ids: ids, type: sortType}
                }).done(function( msg ) {
                    toastr.success('Items sort updated!');
                });
            }

            $('.btnRemove').click(function () {
                $('#deleteModal').addClass('open_modal');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_main_banner_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });

            $('.btnEdit').click(function () {
                $('#addEditTitle').html('Edit Main Banner');
                $("#add_new_banner").addClass('d-none');
                $('#btnSubmit').html('Update');
                var type = parseInt($(this).data('type'));
                var id = parseInt($(this).data('id'));
                let banners = [];
                if(type === 2)
                    banners = bigBanners
                else
                    banners = smallBanners
                $.each(banners, function (index, value) {
                    if(value.id === id){
                        $("#bannerid").val(value.id);
                        $("#link").val(value.link);
                        $("#type").val(value.name);
                        $('input:radio[name="type"][value="'+value.type+'"]').attr('checked',true);
                    }
                });
                $("#form").removeClass('d-none');
            });

        })
    </script>
@stop
