@extends('admin.layouts.main')

<?php
use App\Enumeration\BannerType;
?>

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')

    <div class="text_right m15">
        <div class="display_inline mr_0">
            <button type="submit" class="ly_btn  btn_blue min_width_100p " id="add_new_banner">Add New Banner</button>
        </div>
    </div>


    <form class="form-horizontal  {{ $errors && count($errors) > 0 ? null : 'd-none' }}" id="form" method="post" action="{{ route('add_feature_widget') }}" enctype="multipart/form-data">
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
                            <div class="label_inline width_150p {{ $errors->has('description') ? ' has-danger' : '' }}">
                                <label for="description" class="col-form-label">Description : </label>
                            </div>
                            <div class="form_inline">
                                <textarea id="description" class="form_global{{ $errors->has('description') ? ' is-invalid' : '' }}"  name="description"></textarea>
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="label_inline required width_150p ">
                                Use For?
                            </div>
                            <div class="form_inline">
                                <div class="custom_radio">
                                    <input type="radio" id="large" class="type" name="type" value="{{BannerType::$FEATURE_WIDGET}}" {{ old('status') == BannerType::$FEATURE_WIDGET ? 'checked' : '' }}>
                                    <label for="large">Feature Banner For Home Page Top Section</label>
                                </div>
                                <div class="custom_radio">
                                    <input type="radio" id="small" name="type" class="type" value="{{BannerType::$FEATURE_WIDGET_BOTTOM}}" {{ (old('status') == BannerType::$FEATURE_WIDGET_BOTTOM || empty(old('status'))) ? 'checked' : '' }}>
                                    <label for="small">Feature Banner For Home Page Bottom Section</label>
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

    <div class="vendor_info_content">
        <div class="tab_wrapper pa_0">
            <div class="ly_tab">
                <nav class="tabs">
                    <ul class="tab_four">
                        <li id="top_Feature_WidgetTab" href="#top_Feature_Widget"  class="tab_link {{ request()->query("type") && request()->query("type")  == 'top' ? 'active' : '' }}"><a style="display: block;" href="{{ route('feature_widget') }}?type=top">Widget Feature Banner For Home Page Top Section</a></li>
                        <li id="bottomFeatureTab" href="#bottomFeature" class="tab_link {{ request()->query("type") && request()->query("type")  == 'bottom' ? 'active' : '' }}"><a style="display: block;" href="{{ route('feature_widget') }}?type=bottom">Widget Feature Banner For Home Page Bottom Section</a></li>
                    </ul>
                </nav>
                <div class="tab_content_wrapper">
                    <div id="top_Feature_Widget" class="tab_content  {{ request()->query("type") && request()->query("type")  == 'top' ? 'show' : '' }}">
                        <div class="fadein">
                            <div class="ly-row">
                                <form class="form-horizontal" method="post" action="{{ route('section_heading_post') }}" >
                                    @csrf
                                    <input type="hidden" id="id" value="{{ $section_heading1->id }}" name="id">
                                    <div class="ly-row">
                                        <div class="ly-10">
                                            <div class="form_row">
                                                <div class="label_inline required width_150p">Section Heading</div>
                                                <div class="form_inline">
                                                    <input type="text" id="style_no" class="form_global{{ $errors->has('heading') ? ' is-invalid' : '' }}" name="heading" value="{{ $section_heading1->heading }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-2">
                                            <div class="text_right">
                                                <div class="display_inline">
                                                    <button type="submit" class="ly_btn  btn_blue min_width_100p ">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="ly-row">
                                <table class="table" id="customer_off">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>link</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="bannerSort" data-type="{{BannerType::$FEATURE_WIDGET}}">
                                    @foreach($banners as $banner)
                                        <tr data-id="{{ $banner->id }}">
                                            <td><img src="{{ asset($banner->image) }}" width="100px" alt=""></td>
                                            <td>{{ $banner->name }}</td>
                                            <td>{{ $banner->link }}</td>
                                            <td>
                                                <a class="link btnEdit" href="#"  data-type="{{ $banner->type }}"  data-id="{{ $banner->id }}" style="color: blue">Edit</a> |
                                                <a class="link btnDelete" data-id="{{ $banner->id }}" role="button" style="color: red">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="bottomFeature" class="tab_content {{ request()->query("type") && request()->query("type")  == 'bottom' ? 'show' : '' }}">
                        <div class="fadein">
                            <div class="ly-row">
                                <form class="form-horizontal" method="post" action="{{ route('section_heading_post') }}" >
                                    @csrf
                                    <input type="hidden" id="id" value="{{ $section_heading2->id }}" name="id">
                                    <div class="ly-row">
                                        <div class="ly-10">
                                            <div class="form_row">
                                                <div class="label_inline required width_150p">Section Heading</div>
                                                <div class="form_inline">
                                                    <input type="text" id="style_no" class="form_global{{ $errors->has('heading') ? ' is-invalid' : '' }}" name="heading" value="{{ $section_heading2->heading }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-2">
                                            <div class="text_right">
                                                <div class="display_inline">
                                                    <button type="submit" class="ly_btn  btn_blue min_width_100p ">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="ly-row">
                                <table class="table" id="customer_off">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>link</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="bannerSortBottom" data-type="{{BannerType::$FEATURE_WIDGET_BOTTOM}}">
                                    @foreach($bottom_banners as $banner)
                                        <tr data-id="{{ $banner->id }}">
                                            <td><img src="{{ asset($banner->image) }}" width="100px" alt=""></td>
                                            <td>{{ $banner->name }}</td>
                                            <td>{{ $banner->link }}</td>
                                            <td>
                                                <a class="link btnEdit" href="#" data-type="{{ $banner->type }}"  data-id="{{ $banner->id }}" style="color: blue">Edit</a> |
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
            var topbanners = <?php echo json_encode($banners); ?>;
            var bottom_banners = <?php echo json_encode($bottom_banners); ?>;

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
                let banners = [];
                var id = $(this).data('id');
                var type = parseInt($(this).data('type'));
                if(type == "{{BannerType::$FEATURE_WIDGET}}")
                    banners = topbanners
                else
                    banners = bottom_banners

                $.each(banners, function (index, value) {
                    if(value.id === id){
                        $("#bannerid").val(value.id);
                        $("#link").val(value.link);
                        $("#name").val(value.name);
                        $('#category option[value="'+value.category+'"]').attr("selected", "selected");
                        $("#oldimage").val(value.image);
                        $("#description").val(value.description);
                        $('input:radio[name="type"][value="'+value.type+'"]').attr('checked',true);
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
            let sortType = null;

            var elbottom = document.getElementById('bannerSortBottom');
            Sortable.create(elbottom, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    sortType = $(elbottom).data('type')
                    updateSort(this.toArray());
                },
            });

            var el = document.getElementById('bannerSort');
            Sortable.create(el, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    sortType = $(el).data('type')
                    updateSort(this.toArray());
                },
            });

            function updateSort(ids) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_banner_sort') }}",
                    data: { ids: ids, type:sortType}
                }).done(function( msg ) {
                    toastr.success('Items sort updated!');
                });
            }


        })
    </script>
@stop
