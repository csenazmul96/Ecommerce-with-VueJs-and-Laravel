@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.css" />
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
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitlePost">Edit Post</h3>
                <form action="{{ route('admin_blog_update_post', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form_row">
                        <div class="label_inline width_150p fw_500">
                            Status
                        </div>
                        <div class="form_inline">
                            <div class="custom_radio">
                                <input type="radio" id="statusActivePost" name="statusPost" class="custom-control-input"
                                        value="1" {{ empty(old('statusPost')) ? ($post->status == 1 ? 'checked' : '') : (old('statusPost') == 1 ? 'checked' : '') }}>
                                <label class="custom-control-label" for="statusActivePost">Active</label>
                            </div>
                            <div class="custom_radio">
                                <input type="radio" id="statusInactivePost" name="statusPost" class="custom-control-input"
                                        value="0" {{ empty(old('statusPost')) ? ($post->status == 0 ? 'checked' : '') : (old('statusPost') == 0 ? 'checked' : '') }}>
                                <label class="custom-control-label" for="statusInactivePost">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Post Title
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global{{ $errors->has('post_title') ? ' is-invalid' : '' }}" name="post_title" value="{{ empty(old('post_title')) ? ($errors->has('post_title') ? '' : $post->title) : old('post_title') }}">
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Post Slug
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global{{ $errors->has('post_slug') ? ' is-invalid' : '' }}" name="post_slug" value="{{ empty(old('post_slug')) ? ($errors->has('post_slug') ? '' : $post->slug) : old('post_slug') }}">
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
                                        <option value="{{ $category->id }}" {{ empty(old('post_category')) ? ($errors->has('post_category') ? '' : ($post->category_id == $category->id ? 'selected' : '')) :
                                            (old('post_category') == $category->id ? 'selected' : '') }}>{{ $category->name }}</option>
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
                            <textarea class="form_global" name="post_description" id="post_description" rows="10" cols="80">{{ empty(old('post_description')) ? ($errors->has('post_description') ? '' : $post->description) : old('post_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Upload Post Image
                        </div>
                        <div class="form_inline">
                            <input class="form_global{{ $errors->has('post_image') ? ' is-invalid' : '' }} fileUpload" type="file" id="post_image" name="post_image" accept="image/*">
                            <input type="hidden" name="postId" value="{{ $post['id']}}">
                            <div class="upload-demo show_image">
                                <div class="upload-demo-wrap">
                                    @if($post['image'])
                                        <img alt="no image" class="portimg" src="{{ Storage::url($post['thumb'])}}">
                                        <button class="delbtn ly_btn btn_blue removebtn" >Remove</button>
                                    @else
                                        <img alt="no image" class="portimg"  src="{{ asset('images/no-image.png') }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Image Alt Text
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global" name="image_alt" value="{{ empty(old('image_alt')) ? ($errors->has('image_alt') ? '' : $post->image_alt) : old('image_alt') }}">
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Post Tags
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global" name="post_tags" id="tags" data-role="tagsinput" value="{{ empty(old('post_tags')) ? ($errors->has('post_tags') ? '' : $post['tags']) : old('post_tags') }}">
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Update Date
                        </div>
                        <div class="ly-row" style="display: flex;margin: 0;">
                            <div class="ly-2 mr_0 p_0">
                                <div class="form_inline">
                                    <input type="text" class="form_global" id="updateDate" name="update_date" value="{{ empty(old('update_date')) ? ($errors->has('update_date') ? '' : $post->updated_at->format('Y-m-d')) : old('update_date') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500">
                            Update Time
                        </div>
                        <div class="ly-row" style="display: flex;margin: 0;">
                            <div class="ly-2 mr_0 p_0">
                                <div class="form_inline">
                                    <input type="text" class="form_global" id="updateTime" name="update_time" value="{{ empty(old('update_time')) ? ($errors->has('update_time') ? '' : $post->updated_at->format('H:i:s')) : old('update_time') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="mt-3 mb-3">SEO Content</h3>
                    <hr>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500 {{ $errors->has('meta_title') ? 'text-danger' : '' }}">
                            Post Meta Title
                        </div>
                        <div class="form_inline">
                            <input type="text" class="form_global" name="meta_title" value="{{ empty(old('meta_title')) ? ($errors->has('meta_title') ? '' : $post->meta_title) : old('meta_title') }}">
                        </div>
                    </div>

                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500 {{ $errors->has('meta_description') ? 'text-danger' : '' }}">
                            Post Meta Description
                        </div>
                        <div class="form_inline">
                            <textarea class="form_global" name="meta_description" id="meta_description" rows="5" placeholder="Meta Description">{{ empty(old('meta_description')) ? ($errors->has('meta_description') ? '' : $post->meta_description) : old('meta_description') }}</textarea>
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
    <script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
    <script>
        $(function () {
            var message = '{{ session('message') }}';
            var error = '{{ session('error') }}';

            if (message != '')
                toastr.success(message);

            //ckeditor
            var options = {
                filebrowserImageBrowseUrl: '{{ url('laravel-filemanager') }}?type=Images',
                filebrowserImageUploadUrl: '{{ url('laravel-filemanager') }}/upload?type=Images&_token=',
                filebrowserBrowseUrl: '{{ url('laravel-filemanager') }}?type=Files',
                filebrowserUploadUrl: '{{ url('laravel-filemanager') }}?type=Files&_token='
            };

            $('#updateDate').datepicker({
                dateFormat: "yy-mm-dd"
            });

            $('#updateTime').timepicker({
                'showDuration': true,
                'timeFormat': 'h:i:s'
            });

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
                var $newinput =  $(this).parent().parent().parent().find('.portimg ');
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        reset($newinput.next('.delbtn'), true);
                        $newinput.attr('src', e.target.result).show();
                        $newinput.after('<button class="delbtn removebtn ly_btn btn_blue" > Remove</button>');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            }
            function reset(elm, prserveFileName) {
                if (elm && elm.length > 0) {
                    var $input = elm;
                    $input.prev('.portimg').attr('src', defaultUrl);
                    if (!prserveFileName) {
                        $($input).parent().parent().parent().find('input.fileUpload ').val("");
                    }
                    elm.remove();
                }
            }
        })
    </script>
@stop
