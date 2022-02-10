@extends('admin.layouts.main')

<?php
    use App\Enumeration\VendorImageType;
?>

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <form class="form-horizontal" id="form" method="post" action="{{ route('admin_section_two_banner_add') }}" enctype="multipart/form-data">
        @csrf
        <div class="ly_card banner_item_card">
            <div class="ly_card_heading">
                <h5 class="mb-0"> Add Section Two Banner</h5>
            </div>
        </div>

        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly-12 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline required width_150p {{ $errors->has('photo') ? ' has-danger' : '' }}">
                                <label for="photo" class="col-form-label">Upload Image/Video : </label>
                            </div>
                            <div class="form_inline">
                                <input type="file" id="photo" class="form_global{{ $errors->has('photo') ? ' is-invalid' : '' }}" name="photo">
                            </div>
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
                            <div class="label_inline width_150p {{ $errors->has('title') ? ' has-danger' : '' }}">
                                <label for="title" class="col-form-label">Title : </label>
                            </div>
                            <div class="form_inline">
                                <input type="text" id="title" class="form_global{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('description') ? ' has-danger' : '' }}">
                                <label for="description" class="col-form-label">Description : </label>
                            </div>
                            <div class="form_inline">
                                <textarea id="description" class="form_global{{ $errors->has('description') ? ' is-invalid' : '' }}"  name="description">

                                </textarea>
                            </div>
                        </div>
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

    <div class="ly_page_wrapper">
        <div class="ly-wrap-fluid">
            <div class="ly-row">
                <div class="ly-12">
                    <ul id="sectionTwoBanner">
                        @if(!empty($sectionTwoBanners))
                            @foreach($sectionTwoBanners as $image)
                                <li data-id="{{ $image->id }}">
                                    @if(strpos($image->image_path, '.mp4') !== false)
                                        <video id='video' loop muted preload="metadata" width="100%" height="100%" class="embed-responsive-item banner_item" autoplay playsinline>
                                            <source id='mp4' src="{{ asset($image->image_path) }}" type='video/mp4'/>
                                        </video>
                                    @else
                                        <img src="{{ asset($image->image_path) }}" alt="" class="banner_item">
                                    @endif
                                    <div class="banner_edit">
                                        <span class="color_blue btnEdit" data-id="{{ $image->id }}" data-type="1" data-title="{{ $image->title }}" data-description="{{ $image->details }}">Edit</span> |
                                        <span class="color_red btnRemove" data-type="1" data-id="{{ $image->id }}">Remove</span>
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

    <div class="modal" data-modal="deleteModal">
        <div class="modal_overlay" data-modal-close="deleteModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Are you sure want to delete?</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="deleteModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_blue width_150p " data-modal-close="deleteModal">Close</button>
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

    <div class="modal" data-modal="editModal">
        <div class="modal_overlay" data-modal-close="editModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Edit Link URL</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="editModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">

                            <div id="htmlMsg"></div>
                            <div class="form_row">
                                <div class="label_inline required width_150p">
                                    <label for="link" class="col-form-label">Link</label>
                                </div>
                                <div class="form_inline">
                                    <input class="form_global" type="text" id="modal_url" required="">
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="label_inline width_150p">
                                    <label for="title" class="col-form-label">Title</label>
                                </div>
                                <div class="form_inline">
                                    <input type="text" class="form_global" id="modal_title">
                                </div>
                            </div>
                            <div class="form_row">
                                <div class="label_inline width_150p">
                                    <label for="description" class="col-form-label">Description</label>
                                </div>
                                <div class="form_inline">
                                    <textarea class="form_global" id="modal_description">

                                    </textarea>
                                </div>
                            </div>
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_danger width_150p " data-modal-close="editModal">Close</button>
                                            <button class="ly_btn btn_blue width_150p" id="modalBtnEdit">Save</button>
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

            var selectedId;
            var type = '';
            var bannerId = '';
            var message = '{{ session('message') }}';
            var images = <?php echo json_encode($sectionTwoBanners); ?>;

            if (message != '')
                toastr.success(message);

            var el = document.getElementById('sectionTwoBanner');
            Sortable.create(el, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    updateSort(this.toArray());
                },
            });

            function updateSort(ids) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_section_two_banner_sort') }}",
                    data: { ids: ids }
                }).done(function( msg ) {
                    toastr.success('Items sort updated!');
                });
            }

            $('.btnRemove').click(function () {
                var targeted_modal_class = 'deleteModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_section_two_banner_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });

            $('.btnEdit').click(function () {
                var type = 0;
                var id = parseInt($(this).data('id'));
                var title = $(this).data('title');
                var description = $(this).data('description');
                    type = $(this).data('type');
                selectedId = id;

                $.each(images, function (i, img) {
                    if (img.id == id)
                        image = img;
                });
                
                $('#modal_url').val(image.url);
                $('#modal_title').val(title);
                $('#modal_description').val(description);
                var targeted_modal_class = 'editModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnEdit').click(function () {
                var url = $('#modal_url').val();
                var title = $('#modal_title').val();
                var description = $('#modal_description').val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_section_two_banner_update') }}",
                    data: { id: selectedId, url: url, title: title, description:description }
                }).done(function( msg ) {
                    location.reload();
                });
            });
        })
    </script>
@stop