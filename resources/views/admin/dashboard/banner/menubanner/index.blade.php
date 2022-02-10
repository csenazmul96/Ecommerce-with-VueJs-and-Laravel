@extends('admin.layouts.main')

<?php
use App\Enumeration\VendorImageType;
?>

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <style>
        .table_fixed{
            table-layout: fixed;
        }
    </style>
@stop

@section('content')

            <div class="text_right m15">
                <div class="display_inline mr_0">
                    <button type="submit" class="ly_btn  btn_blue min_width_100p " id="add_new_banner">Add New Banner</button>
                </div>
            </div>


    <form class="form-horizontal  {{ $errors && count($errors) > 0 ? null : 'd-none' }}" id="form" method="post" action="{{ route('add_menu_banners') }}" enctype="multipart/form-data">
        @csrf
        <div class="ly_card banner_item_card">
            <div class="ly_card_heading">
                <h5 class="mb-0"> Add New Banner</h5>
            </div>
        </div>

        <input type="hidden" name="bannerid" id="bannerid" value="">
        <input type="hidden" name="oldimage" id="oldimage" value="">

        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly-12 pl_0 pl_60">
                        <div class="form_row">
                            <div class="label_inline required width_150p {{ $errors->has('image') ? ' has-danger' : '' }}">
                                <label for="photo" class="col-form-label">Upload Image: </label>
                            </div>
                            <div class="form_inline">
                                <input type="file" id="photo" class="form_global{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image">
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
                            <div class="label_inline width_150p {{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label for="title" class="col-form-label">Name : </label>
                            </div>
                            <div class="form_inline">
                                <input type="text" id="name" class="form_global{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline width_150p {{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label for="title" class="col-form-label">Category : </label>
                            </div>
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global d_parent_category{{ $errors->has('category') ? ' is-invalid' : '' }}" name="category" id="category">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" data-index="{{ $loop->index }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
@foreach($categories as $cat)
    @if($cat->banners && count($cat->banners) > 0)
    <div class="ly_page_wrapper">
        <div class="ly-wrap-fluid">
            <div class="ly-row">
                <div class="ly-12"><h2>Cateogyr: {{ $cat->name }} ({{count($cat->banners)}})</h2> </div>
                <div class="ly-12">
                    <table class="table table_fixed" id="customer_off">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>link</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="menuBannerSort_{{$cat->id}}">
                            @foreach($cat->banners as $banner)
                                <tr data-id="{{ $banner->id }}">
                                    <td><img src="{{ asset($banner->image) }}" width="100px" alt=""></td>
                                    <td>{{ $banner->name }}</td>
                                    <td>{{ $banner->link }}</td>
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

    <br>
    @endif
@endforeach
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
                $("#form").removeClass('d-none');
                $("#bannerid").val('');
                $("#link").val('');
                $("#name").val('');
                $("#description").val('');
                $("#oldimage").val('');
                $('#category option[value=""]').attr("selected", "selected");
            });
            $(".btnEdit").click(function(){
                var id = $(this).data('id');
                $.each(banners, function (index, value) {
                     if(value.id === id){
                        $("#bannerid").val(value.id);
                        $("#link").val(value.link);
                        $("#name").val(value.name);
                        $('#category option[value="'+value.category+'"]').attr("selected", "selected");
                        $("#oldimage").val(value.image);
                        $("#description").val(value.description);
                     }
                });
                $("#form").removeClass('d-none');
            });
            $(".btnDelete").click(function(){
                var id = $(this).data('id')
                $.ajax({
                    method: "POST",
                    url: "{{ route('menu_banner_delete') }}",
                    data: { id: id }
                }).done(function( msg ) {
                    location.reload();
                });
            });

            $.each(categories, function (index, value) {
                if(value.banners.length) {
                    var el = document.getElementById('menuBannerSort_' + value.id);
                    Sortable.create(el, {
                        animation: 150,
                        dataIdAttr: 'data-id',
                        onEnd: function () {
                            updateSort(this.toArray(), value.id);
                        },
                    });
                }
            });


            function updateSort(ids, cat) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('menu_banner_sort') }}",
                    data: { ids: ids, cat:cat}
                }).done(function( msg ) {
                    toastr.success('Items sort updated!');
                });
            }


        })
    </script>
@stop
