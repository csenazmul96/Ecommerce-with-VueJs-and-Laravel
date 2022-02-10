@extends('admin.layouts.main')

<?php
use App\Enumeration\VendorImageType;
?>

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')

    <div class="text_right m15">
        <div class="display_inline mr_0">
            <button type="submit" class="ly_btn  btn_blue min_width_100p " id="add_new_banner">Add New Color</button>
        </div>
    </div>

    <form class="form-horizontal  {{ $errors && count($errors) > 0 ? null : 'd-none' }}" id="form" method="post" action="{{ route('category_color_add') }}" enctype="multipart/form-data">
        @csrf
        <div class="ly_card banner_item_card">
            <div class="ly_card_heading">
                <h5 class="mb-0"> Add New Color</h5>
            </div>
        </div>
        <input type="hidden" name="bannerid" id="bannerid" value="">
        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly-12 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label for="title" class="col-form-label">Name : </label>
                            </div>
                            <div class="form_inline">
                                <input type="text" id="name" class="form_global{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                <label for="title" class="col-form-label">Category : </label>
                            </div>
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global d_parent_category{{ $errors->has('category_id') ? ' is-invalid' : '' }}" name="category_id" id="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" data-index="{{ $loop->index }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category_id'))
                                        <span class="text-danger">{{ $errors->first('category_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('color_id') ? ' has-danger' : '' }}">
                                <label for="title" class="col-form-label">Color : </label>
                            </div>
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global d_parent_category{{ $errors->has('color_id') ? ' is-invalid' : '' }}" name="color_id" id="color_id">
                                        <option value="">Select Category</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" data-index="{{ $loop->index }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('color_id'))
                                        <span class="text-danger">{{ $errors->first('color_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline required width_150p {{ $errors->has('image') ? ' has-danger' : '' }}">
                                <label for="photo" class="col-form-label">Image: </label>
                            </div>
                            <div class="form_inline">
                                <input type="file" id="image" class="form_global{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image">
                                @if ($errors->has('image'))
                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p fw_500"> Status </div>
                            <div class="form_inline">
                                <div class="custom_radio">
                                    <input type="radio" id="statusActiveCategory" name="status" class="custom-control-input"
                                           value="1" {{ (old('status') == '1' || empty(old('status'))) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="statusActiveCategory">Active</label>
                                </div>
                                <div class="custom_radio">
                                    <input type="radio" id="statusInactiveCategory" name="status" class="custom-control-input"
                                           value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="statusInactiveCategory">Inactive</label>
                                </div>
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
                    <table class="table" id="customer_off">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="menuBannerSort_{{$cat->id}}">
                        @foreach($banners as $banner)
                            <tr data-id="{{ $banner->id }}">
                                <td><img src="{{ asset($banner->image) }}" width="100px" alt=""></td>
                                <td>{{ $banner->name }}</td>
                                <td>{{ $banner->color ? $banner->color->name : null }}</td>
                                <td>{{ $banner->category ? $banner->category->name : null }}</td>
                                <td>
                                    <a class="link btnEdit" href="#"  data-id="{{ $banner->id }}" style="color: blue">Edit</a> |
                                    <a class="link btnDelete" data-id="{{ $banner->id }}" role="button" style="color: red">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
            var banners = <?php echo json_encode($banners); ?>;
            var categories = <?php echo json_encode($categories); ?>;

            $("#add_new_banner").click(function(){
                $(".ly_card_heading h5").html('Add New Color');
                $("#form").removeClass('d-none');
                $('#form').attr('action', '{{ route('category_color_add') }}');
                $("#bannerid").val('');
                $("#name").val('');
                $('#category_id option[value=""]').attr("selected", "selected");
                $('#color_id option[value=""]').attr("selected", "selected");
                $('input[name=status][value="1"]').attr('checked', 'checked');
            });

            $(".btnEdit").click(function(){
                var id = $(this).data('id');
                $.each(banners, function (index, value) {
                    if(value.id === id){
                        $("#bannerid").val(value.id);
                        $("#name").val(value.name);
                        $('#category_id option[value="'+value.category_id+'"]').attr("selected", "selected");
                        $('#color_id option[value="'+value.color_id+'"]').attr("selected", "selected");
                        $("input[name=status][value=" + value.status+ "]").attr('checked', 'checked');
                    }
                });
                $(".ly_card_heading h5").html('Edit Color');
                $('#form').attr('action', '{{ route('category_color_update') }}');
                $("#form").removeClass('d-none');
            });

            $(".btnDelete").click(function(){
                var id = $(this).data('id')
                $.ajax({
                    method: "POST",
                    url: "{{ route('category_color_delete') }}",
                    data: { id: id }
                }).done(function( msg ) {
                    location.reload();
                });
            });

        })
    </script>
@stop
