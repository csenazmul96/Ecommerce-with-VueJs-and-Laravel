@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet">
   <style>
        .label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }
        .label-info {
            background-color: #5bc0de;
        }
        .show_image{
            border: 1px solid #ddd;
            width: 153px;
            margin-top: 5px;

        }
        .show_image img{
            height: 150px;
            width: 150px;
        }
        .show_image img:hover {
            box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
        }
        .delbtn{
            width: 100%;
            margin-top: 2px;
        }
    </style>
@stop

@section('content')
    <div class="fadein">
        <div class="ly_page_wrapper mb_15" id="addEditRowPost">
            <div class="add_new_cat_color">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitlePost">Add New Post</h3>
                <form action="{{ route('admin_blog_add_post_save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form_row">
                        <div class="label_inline width_150p fw_500">
                            Status
                        </div>
                        <div class="form_inline">
                            <div class="custom_radio">
                                <input type="radio" id="statusActiveCategory" name="statusCategory" class="custom-control-input"
                                        value="1" {{ (old('statusCategory') == '1' || empty(old('statusCategory'))) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="statusActiveCategory">Active</label>
                            </div>
                            <div class="custom_radio">
                                <input type="radio" id="statusInactiveCategory" name="statusCategory" class="custom-control-input"
                                        value="0" {{ old('statusCategory') == '0' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="statusInactiveCategory">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Post Title
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global" name="post_title">
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Post Category
                        </div>
                        <div class="form_inline">
                            <div class="select">
                                <select class="form_global{{ $errors->has('post_category') ? ' is-invalid' : '' }}" name="post_category">
                                    <option value="">Select Category</option>

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('post_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500 align_top {{ $errors->has('post_description') ? 'text-danger' : '' }}">
                            Post Description
                        </div>
                        <div class="form_inline">
                            <textarea class="form_global" name="post_description" id="post_description" rows="10" cols="80"></textarea>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Upload Post Image
                        </div>
                        <div class="form_inline">
                            <input class="form_global{{ $errors->has('post_image') ? ' is-invalid' : '' }} fileUpload" type="file" id="post_image" name="post_image" accept="image/*">
                            <div class="upload-demo show_image">
                                <div class="upload-demo-wrap">
                                    <img alt="no image" class="postimg" src="{{ asset('images/no-image.png') }}">
                                </div>
                            </div> 
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Image Alt Text
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global" name="image_alt" value="{{ old('image_alt') }}">
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Post Tags
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global" name="post_tags" id="tags" data-role="tagsinput">
                        </div>
                    </div>

                    <h3 class="mt-3 mb-3">SEO Content</h3>
                    <hr>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500 {{ $errors->has('meta_title') ? 'text-danger' : '' }}">
                            Post Meta Title
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global" name="meta_title" value="{{ old('meta_title') }}">
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500 {{ $errors->has('meta_description') ? 'text-danger' : '' }}">
                            Post Meta Description
                        </div>
                        <div class="form_inline">
                            <textarea class="form_global" name="meta_description" id="meta_description" rows="5" placeholder="Meta Description"></textarea>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="form_inline">
                            <div class="text_right">
                                <div class="display_inline">
                                    <a class="ly_btn btn_grey min_width_100p text_center" href="{{ route('admin_blog') }}">Back</a>
                                </div>
                                <div class="display_inline mr_0">
                                    <button class="ly_btn btn_blue min_width_100p" id="btnAddBrand">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="//cdn.ckeditor.com/4.14.1/full/ckeditor.js"></script>
    <script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script>
        $(function () {
            //ckeditor
            var options = {
                filebrowserImageBrowseUrl: '{{ url('laravel-filemanager') }}?type=Images',
                filebrowserImageUploadUrl: '{{ url('laravel-filemanager') }}/upload?type=Images&_token=',
                filebrowserBrowseUrl: '{{ url('laravel-filemanager') }}?type=Files',
                filebrowserUploadUrl: '{{ url('laravel-filemanager') }}?type=Files&_token='
            };

            CKEDITOR.config.allowedContent = true;
            CKEDITOR.replace('post_description', options);

            //image preview
            var defaultUrl = '{{ asset('images/no-image.png') }}';
            $(".fileUpload").change(readURL);

            $(".upload-demo-wrap").on('click', '.delbtn', function (e) {
                reset($(this));
            });

            function readURL() {
                var $input = $(this);
                var $newinput =  $(this).parent().parent().parent().find('.postimg ');
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        reset($newinput.next('.delbtn'), true);
                        $newinput.attr('src', e.target.result).show();
                        $newinput.after('<input type="button" class="delbtn removebtn" value="Remove">');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            }
            function reset(elm, prserveFileName) {
                if (elm && elm.length > 0) {
                    var $input = elm;
                    $input.prev('.postimg').attr('src', defaultUrl);
                    if (!prserveFileName) {
                        $($input).parent().parent().parent().find('input.fileUpload ').val("");
                    }
                    elm.remove();
                }
            }
        })
    </script>
@stop