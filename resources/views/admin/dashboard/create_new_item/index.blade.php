<?php use App\Enumeration\Availability; ?>
@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/ezdz/jquery.ezdz.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <form class="form-horizontal" method="post" action="{{ route('admin_create_new_item_post') }}" id="form" enctype="multipart/form-data">
        @csrf
        <div class="text_right m15">
            <div class="display_inline mr_0">
                <button type="submit" class="ly_btn  btn_blue min_width_100p ">Save</button>
            </div>
        </div>

        <div class="ly_accrodion">
            <div class="ly_accrodion_heading">
                <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#ItemInfo" data-class="accordion">
                    <span>Item Info</span>
                </div>
            </div>
            <div class="accordion_body default_accrodion open" id="ItemInfo">
                <div class="ly-wrap-fluid">
                    <div class="ly-row">
                        <div class="ly-6 pl_0 pr_60">
                            <div class="form_row">
                                <div class="label_inline required width_150p">
                                    Status
                                </div>
                                <div class="form_inline">
                                    <div class="custom_radio">
                                        <input type="radio" id="statusActive" name="status" value="1" {{ old('status') == '1' ? 'checked' : '' }}>
                                        <label for="statusActive">Active</label>
                                    </div>
                                    <div class="custom_radio">
                                        <input type="radio" id="statusInactive" name="status" value="0" {{ (old('status') == '0' || empty(old('status'))) ? 'checked' : '' }}>
                                        <label for="statusInactive">Inactive</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form_row">
                                <div class="label_inline required width_150p">
                                    Style No.
                                </div>
                                <div class="form_inline">
                                    <input type="text" id="style_no" class="form_global{{ $errors->has('style_no') ? ' is-invalid' : '' }}"
                                           name="style_no" value="{{ old('style_no') }}">
                                </div>
                            </div>
                            <div class="form_row">
                                <div class="label_inline required width_150p">
                                    Price
                                </div>
                                <div class="form_inline">
                                    <div class="input_inline">
                                        <div class="display_inline">
                                            <div class="input_number plc_fixed_left">
                                                <input type="text" id="price" class="form_global text_right{{ $errors->has('price') ? ' is-invalid' : '' }}"
                                                       placeholder="$" name="price" value="{{ old('price') }}">
                                            </div>
                                        </div>
                                        <div class="display_inline float_right mr_0">
                                            <span class="mr_8">Orig. Price</span>
                                            <div class="input_number plc_fixed_left">
                                                <input type="text" id="orig_price" class="form_global text_right{{ $errors->has('orig_price') ? ' is-invalid' : '' }}"
                                                       placeholder="$" name="orig_price" value="{{ old('orig_price') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="label_inline width_150p align_top">
                                    Description
                                </div>
                                <div class="form_inline">
                                    <textarea class="form_global{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="description" rows="8"
                                        placeholder="Max. 500 letters allowed. The following special characters are not allowed: <, >, {, }, ^, [, ], =, @, ;">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="ly-6 pr_0 pl_60">
                            <div class="form_row">
                                <div class="label_inline required width_150p">
                                    Item Name
                                </div>
                                <div class="form_inline">
                                    <input type="text" id="item_name" class="form_global{{ $errors->has('item_name') ? ' is-invalid' : '' }}"
                                        name="item_name" value="{{ old('item_name') }}">
                                </div>
                            </div>

                            <div id="category-container">
                                @if (old('d_parent_category') != null)
                                    {{-- <div class="form_row">
                                        @if ($errors->has('d_parent_category'))
                                            <span class="text-danger">Parent Category is required.</span>
                                        @endif
                                    </div> --}}
                                    @foreach(old('d_parent_category') as $cat)
                                        <div class="form_row">
                                            <div class="label_inline @if($loop->index == 0) required @endif width_150p">
                                                @if($loop->index == 0)
                                                    Category
                                                @endif
                                            </div>
                                            <div class="form_inline display_inline pr_8 width_175p">
                                                <div class="select">
                                                    <select class="form_global d_parent_category{{ $errors->has('d_parent_category') ? ' is-invalid' : '' }}" name="d_parent_category[]">
                                                        <option value="">Select Category</option>
                                                        @foreach($defaultCategories as $dc)
                                                            <option value="{{ $dc['id'] }}" data-index="{{ $loop->index }}" {{ $cat == $dc['id'] ? 'selected' : '' }}>{{ $dc['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form_inline display_inline pr_8 width_175p">
                                                <div class="select">
                                                    <select class="form_global d_second_parent_category{{ $errors->has('d_second_parent_category') ? ' is-invalid' : '' }}" name="d_second_parent_category[]">
                                                        <option value="">Sub Category</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form_inline display_inline width_175p">
                                                <div class="select">
                                                    <select class="form_global d_third_parent_category{{ $errors->has('d_third_parent_category') ? ' is-invalid' : '' }}" name="d_third_parent_category[]">
                                                        <option value="">Sub Category</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form_row">
                                        <div class="label_inline required width_150p">
                                            Category
                                        </div>
                                        <div class="form_inline display_inline pr_8 width_176p">
                                            <div class="select">
                                                <select class="form_global d_parent_category{{ $errors->has('d_parent_category') ? ' is-invalid' : '' }}" name="d_parent_category[]">
                                                    <option value="">Select Category</option>
                                                    @foreach($defaultCategories as $dc)
                                                        <option value="{{ $dc['id'] }}" data-index="{{ $loop->index }}">{{ $dc['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form_inline display_inline pr_8 width_176p">
                                            <div class="select">
                                                <select class="form_global d_second_parent_category{{ $errors->has('d_second_parent_category') ? ' is-invalid' : '' }}" name="d_second_parent_category[]">
                                                    <option value="">Sub Category</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form_inline display_inline width_176p">
                                            <div class="select">
                                                <select class="form_global d_third_parent_category{{ $errors->has('d_third_parent_category') ? ' is-invalid' : '' }}" name="d_third_parent_category[]">
                                                    <option value="">Sub Category</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form_row text_right">
                                <button class="ly_btn  btn_blue min_width_100p " id="btnAddCategory">Add Category</button>
                            </div>

                            <div class="form_row">
                                <div class="label_inline width_150p">
                                    Made In Country
                                </div>
                                <div class="form_inline display_inline pr_8">
                                    <div class="select">
                                        <select class="form_global" name="made_in" id="made_in">
                                            <option value="">Select Made In</option>

                                            @foreach($madeInCountries as $country)
                                                <option value="{{ $country->id }}" {{ old('made_in') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form_inline display_inline">
                                    <div class="select">
                                        <select class="form_global" name="labeled" id="labeled">
                                            <option value="">Select Labeled</option>
                                            <option value="labeled" {{ old('labeled') == 'labeled' ? 'selected' : '' }}>Labeled</option>
                                            <option value="printed" {{ old('labeled') == 'printed' ? 'selected' : '' }}>Printed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
{{--                            <div class="form_row">--}}
{{--                                <div class="label_inline required width_150p">--}}
{{--                                    Brand--}}
{{--                                </div>--}}
{{--                                <div class="form_inline display_inline">--}}
{{--                                    <div class="select">--}}
{{--                                        <select class="form_global{{ $errors->has('brand') ? ' is-invalid' : '' }}" name="brand" id="brand">--}}
{{--                                            <option value="">Select Brand</option>--}}
{{--    --}}
{{--                                            @foreach($brands as $brand)--}}
{{--                                                <option value="{{ $brand->id }}" {{ old('brand') == $country->id ? 'selected' : '' }}>{{ $brand->name }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form_row">
                                <div class="label_inline width_150p">Memo</div>
                                <div class="form_inline">
                                    <input type="text" id="memo" class="form_global{{ $errors->has('memo') ? ' is-invalid' : '' }}"
                                        placeholder="Internal use only" name="memo" value="{{ old('memo') }}">
                                </div>
                            </div>

                            <div class="form_row">
                                <div class="label_inline width_150p">Item Value</div>
                                <div class="form_inline">
                                    <?php
                                        $previous_value_ids = [];

                                        $previous_value_ids = old('item_value');
                                    ?>
                                    @if(!empty($itemValues))
                                        @foreach($itemValues as $value)
                                            <div class="custom_checkbox">
                                                <input type="checkbox" id="mics_{{ $value->id }}" data-id="{{ $value->id }}" class="statusValue"
                                                name="item_value[]" value="{{ $value->id }}" @if(!empty(old('item_value'))) {{ in_array($value->id, $previous_value_ids) ? 'checked' : '' }} @endif>
                                                <label for="mics_{{ $value->id }}" class="pr_0"> {{ $value->name }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ly-row">
            <div class="ly-6">
                <div class="ly_accrodion">
                    <div class="ly_accrodion_heading">
                        <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#itemSpecifications" data-class="accordion">
                            <span>Item Specifications</span>
                        </div>
                    </div>
                    <div class="accordion_body default_accrodion open" id="itemSpecifications">
                        <div class="ly-row">
                            <div class="ly-8 pl_20">
                                <div class="form_row">
                                    <div class="label_inline width_150p">
                                        Item Specifications
                                    </div>
                                    <div class="form_inline display_inline pr_8">
                                        <div class="select">
                                            <select class="form_global" name="specification" id="specification">
                                                <option value="1" {{ old('specification') == '1' ? 'selected' : '' }}>Color & Size</option>
                                                <option value="2" {{ old('specification') == '2' ? 'selected' : '' }}>Color</option>
                                                <option value="3" {{ old('specification') == '3' ? 'selected' : '' }}>Size</option>
                                                <option value="4" {{ old('specification') == '4' ? 'selected' : '' }}>None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ly-6">
                <div class="ly_accrodion">
                    <div class="ly_accrodion_heading">
                        <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#Video" class="accordion_heading" data-class="accordion">
                            <span> Video(1:1)</span>
                        </div>
                    </div>
                    <div class="accordion_body default_accrodion open" id="Video">
                        <div class="create_images_area">
                            <br>
                            <div class="ly-row">
                                <div class="ly-6">
                                    <input type="file" class="form_global" name="video" id="input-video">
                                    @if ($errors->has('video'))
                                        <span class="text-danger">{{ $errors->first('video') }}</span>
                                    @endif
                                </div>
                                <div class="ly-6 pl_60">
                                    <div class="form_row">
                                        <div class="label_inline width_150p">Youtube Url</div>
                                        <div class="form_inline">
                                            <input type="text" id="youtube_url" class="form_global{{ $errors->has('youtube_url') ? ' is-invalid' : '' }}"
                                                placeholder="Youtube Url" name="youtube_url" value="{{ old('youtube_url') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ly_accrodion color_accrodion" @if(old('specification') == 1 || old('specification') == 2 || old('specification') == null) style="display:block" @else style="display:none" @endif>
            <div class="ly_accrodion_heading">
                <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#Colors" data-class="accordion">
                    <span>Colors</span>
                </div>
            </div>
            <div class="accordion_body default_accrodion open" id="Colors">
                <div class="create_item_color">
                    <div class="display_inline">
                        <div class="plc_fixed_left_search">
                            <input class="form_global ui-autocomplete-input" type="text" id="color_search" placeholder="Type Color">
                        </div>
                    </div>
                    <div class="display_inline display_flex width_200p d_none">
                        <div class="select">
                            <input type="color" class="form_global" id="color_code" name="color_code" value="">
                        </div>
                        <br>
                        <div class="select">
                            <select class="form_global" id="select_master_color">
                                <option value="">Select Master Color</option>
                                <option value="add">Add Master Color</option>
                                @foreach($masterColors as $mc)
                                    <option value="{{ $mc->id }}">{{ $mc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="display_inline">
                        <a class="ly_btn btn_blue" id="btnAddColor">Add Color</a>
                    </div>
                    <div class="display_inline">
                        @if ($errors->has('colors'))
                            <span class="text_danger" style="color: red;">Color(s) is required.</span>
                        @endif
                    </div>
                    <br>
                    <br>
                    <div class="ly-row">
                        <div class="ly-12 create_color_list" style="height:auto; overflow: unset;">
                            <ul class="colors-ul">
                                @if (old('colors') != null)
                                    @foreach($colors as $color)
                                        @if (in_array($color->id, old('colors')))
                                            <li>
                                                <div class="input-group">
                                                    <div class="form-check custom_checkbox">
                                                        <input class="form-check-input" type="checkbox" value="1" checked="" name="color_available_{{ $color->id }}" id="color_available_{{ $color->id }}">
                                                        <label class="form-check-label color-available" name="color_available_{{ $color->id }}" id="color_available_{{ $color->id }}" for="color_available_{{ $color->id }}">
                                                            <span class="name">{{ $color->name }}</span>
                                                        </label>
                                                    </div>
                                                    <a class="color-remove">X</a>
                                                    <input class="templateColor" type="hidden" name="colors[]" value="{{ $color->id }}">
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ly_accrodion size_accrodion" @if(old('specification') == 3) style="display:block" @else style="display:none" @endif>
            <div class="ly_accrodion_heading">
                <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#Sizes" data-class="accordion">
                    <span>Size</span>
                </div>
            </div>
            <div class="accordion_body default_accrodion open" id="Sizes" style="">
                <div class="create_item_size">
                    <div class="display_inline width_250p">
                        <div class="select">
                            <select class="form_global" name="sizes" id="selectedSize">
                                <option value="">Select Size</option>
                                @if(!empty($sizes))
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ old('sizes') == $size->id ? 'selected' : '' }}>{{ $size->item_size }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="display_inline">
                        <a class="ly_btn btn_blue" id="btnAddSize">Add Size</a>
                    </div>
                    <div class="display_inline">
                        @if ($errors->has('sizes'))
                            <span class="text_danger" style="color: red;">Sizes(s) is required.</span>
                        @endif
                    </div>
                    <br>
                    <br>
                    <div class="ly-row">
                        <div class="ly-12 create_size_list" style="height:auto; overflow: unset;">
                            <ul class="sizes-ul">
                                @if (old('sizes') != null)
                                    @foreach($sizes as $size)
                                        @if (in_array($size->id, old('sizes')))
                                            <li>
                                                <div class="input-group">
                                                    <div class="form-check custom_checkbox">
                                                        <input class="form-check-input" type="checkbox" value="1" checked="" name="size_available_{{ $size->id }}" id="size_available_{{ $size->id }}">
                                                        <label class="form-check-label size-available" name="size_available_{{ $size->id }}" id="size_available_{{ $size->id }}" for="size_available_{{ $size->id }}">
                                                            <span class="name">{{ $size->item_size }}</span>
                                                        </label>
                                                    </div>
                                                    <a class="size-remove">X</a>
                                                    <input class="templateSize" type="hidden" name="sizes[]" value="{{ $size->id }}">
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ly_accrodion">
            <div class="ly_accrodion_heading">
                <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#Inventory" class="accordion_heading" data-class="accordion">
                    <span> Inventory</span>
                </div>
            </div>
            <div class="accordion_body default_accrodion open" id="Inventory">
                <div class="inventory">
                    @php($specification = old('specification') ?? [])
                    <table class="table">
                        <tr>
                            <th class="text-left inv_th_color" @if($specification == 1 || $specification == 2 || $specification == null) style="display:table-cell" @else style="display:none" @endif>Color</th>

                            <th class="text-left inv_th_size" @if($specification == 1 || $specification == 3 || $specification == null) style="display:table-cell" @else style="display:none" @endif>Size</th>

                            <th class="text-center">Quantity</th>
                            <th class="text-center">
                                <div class="select inv_availability_select">
                                    <select class="form_global availability_inv_glob">
                                        <option value="">Availability</option>
                                        <option value="{{ Availability::$IN_STOCK }}">In Stock</option>
                                        <option value="{{ Availability::$OUT_OF_STOCK }}">Out of Stock</option>
                                    </select>
                                </div>
                            </th>

                            <th class="text-center inv_th_action" @if($specification == 1 || $specification == null) style="display:table-cell" @else style="display:none" @endif>
                                Action
                            </th>
                        </tr>
                        <tbody id="itemInv">
                            @php($inventories = old('inv') ?? [])
                            @php($i=0)
                            @foreach($inventories as $inventory)
                            <tr @if($specification == 1 || $specification == 2) class="inv_{{$inventory['color_id']}}" @endif>
                                @if($specification == 1 || $specification == 2)
                                    <td class="text-left">{{$inventory['color_name']}}
                                        <input type="hidden" name="inv[{{$i}}][id]" value="0">
                                        <input type="hidden" name="inv[{{$i}}][color_id]" value="{{$inventory['color_id']}}">
                                        <input type="hidden" name="inv[{{$i}}][color_name]" value="{{$inventory['color_name']}}">
                                    </td>
                                @endif

                                <td class="text-center" @if($specification == 1 || $specification == 3) style="display:table-cell" @else style="display:none" @endif>
                                    <div class="select">
                                        <select class="form_global" @if($specification == 1) name="inv[{{ $i }}][color_size]" @else name="inv[{{ $i }}][size_id]" @endif required="1">
                                            <option value="0">Select Size</option>
                                        </select>
                                    </div>
                                </td>

                                <td class="text-center" style="padding: 0">
                                    <input class="text-center form-control" type="number" name="inv[{{$i}}][qty]" value="{{$inventory['qty']}}" autocomplete="off" placeholder="Qty">
                                </td>
                                <td>
                                    <div class="display_t_cell_50 pr_8">
                                        <div class="select">
                                            <select class="form_global availability_inv" name="inv[{{$i}}][availability_inv]">
                                                <option value="2" {{ $inventory['availability_inv'] == '2' ? 'checked' : '' }}>In Stock</option>
                                                <option value="3" {{ $inventory['availability_inv'] == '3' ? 'checked' : '' }}>Out Of Stock</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center" @if($specification == 1) style="display:table-cell" @else style="display:none" @endif>
                                    <span class="addnewrow"
                                        data-colorid="{{ $inventory['color_id'] }}"
                                        data-rowid="{{ $inventory['color_id']. $i }}"
                                        data-colorname="{{ $inventory['color_name'] }}"
                                    ><i class="fa fa-plus-circle" ></i></span>
                                    <span class="removerow"><i class="fa fa-minus-circle" ></i></span>
                                </td>
                            </tr>
                            @php($i++)
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="ly_accrodion">
            <div class="ly_accrodion_heading">
                <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#Images" class="accordion_heading" data-class="accordion">
                    <span> Images</span>
                </div>
                <span class="float_right display_inline pt_5"><span id="uploadedImagesCount">0</span> of 20 images added to this item.</span>
            </div>
            <div class="accordion_body default_accrodion open" id="Images">
                <div class="create_images_area images">
                    <div class="create_images">
                        <div class="create_images_inner">
                            <label class="ly_btn btn_blue_hover" for="upload_image" id="btnUploadImages">Upload Images</label>
                            <input type="file" class="d-none" multiple id="inputImages">
                        </div>
                        <p class="ml_20">Upload up to 20 images and hit the 'SAVE' button. Max. allowed image file size is 1MB per image. File name should not exceed 50 char. in length, otherwise it will be automatically truncated and/or assigned a unique name.</p>
                    </div>
                    <div class="ly-wrap mb_25">
                        <div class="ly-row" id="images">
                            <ul id="image-container" class="block__list block__list_tags width_full ">
                                @if (old('imagesId') != null && sizeof(old('imagesId')) > 0)
                                    @foreach(old('imagesId') as $img)
                                        <li>
                                            <div class="image-item" >
                                                <div class="custom_list_img">
                                                    <img class="img-thumbnail img" style="margin-bottom: 10px"
                                                        src="{{ Storage::url(old('imagesSrc.'.$loop->index)) }}">
                                                </div>
                                                <br>
                                                <select class="form_global image-color" name="imageColor[]">
                                                    <option value="">Color [Default]</option>
                                                </select>
                                                <br>
                                                <a class="btnRemoveImage"><span class="remove"> <i class="fas fa-times"></i> </span></a>

                                                <input class="inputImageId" type="hidden" name="imagesId[]" value="{{ $img }}">
                                                <input class="inputImageSrc" type="hidden" name="imagesSrc[]" value="{{ old('imagesSrc.'.$loop->index) }}">
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="ly-wrap pt_15">
                        <div class="ly-row">
                            <div class="create_images_drag">
                                <div class="images">
                                    <span>Drag & Drop Images from your computer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text_right m15">
            <div class="display_inline mr_0">
                <button type="submit" class="ly_btn  btn_blue min_width_100p ">Save</button>
            </div>
        </div>
    </form>
    <div class="modal" data-modal="addMasterColor" id="addMasterColor">
        <div class="modal_overlay" data-modal-close="addMasterColor"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Add New Master Colors</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="addMasterColor"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="form_row">
                                        <div class="label_inline required width_150p"> Color Name </div>
                                        <div class="form_inline display_inline">
                                            <input type="text" id="color_name" class="form_global" placeholder="Color Name" name="color_name" value="">
                                        </div>
                                    </div>
                                    <div class="form_row">
                                        <div class="label_inline required width_150p"> Color Code </div>
                                        <div class="form_inline display_inline">
                                            <input type="color" class="form_global" id="master_color_code" name="master_color_code" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_blue width_150p " data-modal-close="addMasterColor">Close</button>
                                            <button class="ly_btn btn_blue width_150p" id="addMasterColorSave">Save</button>
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
    <template id="category-template">
        <div class="form_row">
            <div class="label_inline width_150p">

            </div>
            <div class="form_inline display_inline pr_8 width_175p">
                <div class="select">
                    <select class="form_global d_parent_category" name="d_parent_category[]">
                        <option value="">Select Category</option>
                        @foreach($defaultCategories as $item)
                            <option value="{{ $item['id'] }}" data-index="{{ $loop->index }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form_inline display_inline pr_8 width_175p">
                <div class="select">
                    <select class="form_global d_second_parent_category" name="d_second_parent_category[]">
                        <option value="">Sub Category</option>
                    </select>
                </div>
            </div>
            <div class="form_inline display_inline pr_8 width_175p">
                <div class="select">
                    <select class="form_global d_third_parent_category" name="d_third_parent_category[]">
                        <option value="">Sub Category</option>
                    </select>
                </div>
            </div>
            <div class="form_inline display_inline">
                <button class="btn btn-danger btnRemoveCategory">X</button>
            </div>
        </div>
    </template>
    <template id="imageTemplate">
        <li>
            <div class="image-item">
                <div class="custom_list_img">
                    <img class="img-thumbnail img">
                </div>
                <select class="form_global image-color" name="imageColor[]">
                    <option value="">Color [Default]</option>
                </select><br>
                <a class="btnRemoveImage"><span class="remove"> <i class="fas fa-times"></i> </span></a>

                <input class="inputImageId" type="hidden" name="imagesId[]">
                <input class="inputImageSrc" type="hidden" name="imagesSrc[]">
            </div>
        </li>
    </template>

    <template id="colorItemTemplate">
        <li>
            <div class="input-group">
                <div class="form-check custom_checkbox">
                    <input class="form-check-input"
                           type="checkbox"
                           value="1" checked>
                    <label class="form-check-label color-available">
                        <span class="name"></span>
                    </label>
                </div>
                <a class="color-remove">X</a>
            </div>
            <input class="templateColor" type="hidden" name="colors[]" value="">
        </li>
    </template>

    <template id="templateSize">
        <li>
            <div class="input-group">
                <div class="form-check custom_checkbox">
                    <input class="form-check-input"
                           type="checkbox"
                           value="1" checked>
                    <label class="form-check-label size-available">
                        <span class="name"></span>
                    </label>
                </div>
                <a class="size-remove">X</a>
            </div>
            <input class="templateSize" type="hidden" name="sizes[]" value="">
        </li>
    </template>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/sortable/js/Sortable.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('plugins/ezdz/jquery.ezdz.min.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('plugins/jquery.ezdz.js') }}"></script>
    <script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_basic/ckeditor.js') }}"></script>
    <script>
        $(function () {
            var defaultCategories = <?php echo json_encode($defaultCategories); ?>;
            var colors = <?php echo json_encode($colors->toArray()); ?>;
            var sizes = <?php echo json_encode($sizes); ?>;
            var specification;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            // Category
            var d_parent_index;
            var d_second_id = [null];
            var d_third_id = [null];

            @if (old('d_second_parent_category') != null)
                d_second_id = <?php echo json_encode(old('d_second_parent_category')) ?>;
            @endif

            @if (old('d_third_parent_category') != null)
                d_third_id = <?php echo json_encode(old('d_third_parent_category')) ?>;
            @endif

            $(document).on('change', '.d_second_parent_category', function () {
                var i = $('.d_second_parent_category').index($(this));

                $('.d_third_parent_category:eq('+i+')').html('<option value="">Sub Category</option>');
                var parentIndex = $('.d_parent_category:eq('+i+')').find(':selected').data('index');

                if ($(this).val() != '') {
                    var index = $(this).find(':selected').attr('data-index');

                    var childrens = defaultCategories[parentIndex].subCategories[index].subCategories;

                    $.each(childrens, function (index, value) {
                        if (value.id == d_third_id[i])
                            $('.d_third_parent_category:eq('+i+')').append('<option data-index="' + index + '" value="' + value.id + '" selected>' + value.name + '</option>');
                        else
                            $('.d_third_parent_category:eq('+i+')').append('<option data-index="' + index + '" value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });

            $(document).on('change', '.d_parent_category', function () {
                var i = $('.d_parent_category').index($(this));
                $('.d_second_parent_category:eq('+i+')').html('<option value="">Sub Category</option>');
                $('.d_third_parent_category:eq('+i+')').html('<option value="">Sub Category</option>');
                var parent_id = $(this).val();

                if ($(this).val() != '') {
                    var index = $(this).find(':selected').data('index');
                    d_parent_index = index;

                    var childrens = defaultCategories[index].subCategories;

                    $.each(childrens, function (index, value) {
                        if (value.id == d_second_id[i])
                            $('.d_second_parent_category:eq('+i+')').append('<option data-index="' + index + '" value="' + value.id + '" selected>' + value.name + '</option>');
                        else
                            $('.d_second_parent_category:eq('+i+')').append('<option data-index="' + index + '" value="' + value.id + '">' + value.name + '</option>');
                    });
                }

                $('.d_second_parent_category:eq('+i+')').change();
            });

            $('.d_parent_category').change();

            $('#btnAddCategory').click(function (e) {
                e.preventDefault();

                var html = $('#category-template').html();
                var category = $(html);

                $('#category-container').append(category);

                if ($('.btnRemoveCategory').length > 1) {
                    $('.btnRemoveCategory').show();
                }
            });

            $(document).on('click', '.btnRemoveCategory', function (e) {
                e.preventDefault();

                $(this).closest('.form_row').remove();

                if ($('.btnRemoveCategory').length < 1) {
                    $('.btnRemoveCategory').hide();
                }
            });

            if ($('.btnRemoveCategory').length < 1) {
                $('.btnRemoveCategory').hide();
            } else {
                $('.btnRemoveCategory').show();
            }

            // Video
            $('#input-video').ezdz();

            // Color select
            var availableColors = [];

            $.each(colors, function (i, color) {
                availableColors.push(color.name);
            });

            $('#color_search').autocomplete({
                source: function (request, response) {
                    var results = $.map(availableColors, function (tag) {
                        if (tag.toUpperCase().indexOf(request.term.toUpperCase()) === 0) {
                            return tag;
                        }
                    });
                    response(results);
                },
                response: function(event, ui) {
                    if (ui.content.length === 0) {
                        $('#select_master_color').val('');
                        $('#select_master_color').closest('.display_inline').removeClass('d_none');
                    } else {
                        $('#select_master_color').closest('.display_inline').addClass('d_none');
                    }
                }
            });

            $('#color_search').keydown(function (e){
                if(e.keyCode == 13){
                    e.preventDefault();
                    addColor();
                }
            });

            $('#color_search').keyup(function (e) {
                if ($('#color_search').val().length == 0)
                    $('#select_master_color').closest('.display_inline').addClass('d_none');
            });

            $('#btnAddColor').click(function () {
                allsize = '';
                var selectedsizeid =[];
                var selectedsizeid = $("[availablecolor='textValue']").val();
                $.each(sizes, function(i, e) {
                    allsize+= '<option value="'+e.id+'">'+e.item_size+'</option>';
                });

                if ($('#select_master_color').closest('.display_inline').hasClass('d_none')) {
                    addColor();
                } else {
                    var id = $('#select_master_color').val();
                    var name = $('#color_search').val();
                    var color_code = $('#color_code').val();

                    if (id == '')
                        return alert('Select Master Color.');

                    if (color_code == '')
                        return alert('Enter color Code.');

                    if (name == '')
                        return alert('Enter color name.');

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_item_add_color') }}",
                        data: { id: id, name: name, color_code:color_code }
                    }).done(function( data ) {
                        if (data.success) {
                            availableColors.push(data.color.name);
                            colors.push(data.color);

                            $('#select_master_color').closest('.display_inline').addClass('d_none');
                            $('#color_search').val(data.color.name);
                            addColor();
                        } else {
                            alert(data.message);
                        }
                    });
                }
            });

            function addColor() {
                var text = $('#color_search').val();
                if (text != '') {
                    var color = '';
                    $.each(colors, function (i, c) {
                        if (c.name == text)
                            color = c;
                    });
                    if (color != '') {
                        var found = false;
                        $( "input[name*='colors']" ).each(function () {
                            if ($(this).val() == color.id)
                                found = true;
                        });

                        if (!found) {
                            var html = $('#colorItemTemplate').html();
                            row = $(html);

                            row.find('.name').html(color.name);
                            row.find('.templateColor').val(color.id);
                            row.find('.color-available').attr('name', 'color_available_'+color.id);
                            row.find('.color-available').attr('id', 'color_available_'+color.id);
                            row.find('.color_check_available').attr('data-colorid', +color.id);
                            row.find('.color-available').attr('for', 'color_available_'+color.id);
                            row.find('.custom-checkbox').attr('for', 'color_available_'+color.id);
                            row.find('.form-check-input').attr('name', 'color_available_'+color.id);
                            row.find('.form-check-input').attr('id', 'color_available_'+color.id);
                            var availability_inv_glob = $('.availability_inv_glob').val();

                            $('.colors-ul').append(row);

                            var invCount = $('#itemInv').find('tr').length;

                            if(specification == 2){
                                var inv = `<tr class="inv_`+color.id+`" id="inv_row_`+color.id+invCount+`" data-id="`+color.id+`">
                                    <td class="text-left">`+color.name+`
                                        <input type="hidden" name="inv[`+invCount+`][id]" value="0">
                                        <input type="hidden" name="inv[`+invCount+`][color_id]" value="`+color.id+`">
                                        <input type="hidden" name="inv[`+invCount+`][color_name]" value="`+color.name+`">
                                        <input type="hidden" name="inv[`+invCount+`][sort]" value="`+invCount+`">
                                    </td>
                                    <td class="text-center">
                                        <input class="text-center form_global" type="number" name="inv[`+invCount+`][qty]" value="999" autocomplete="off" placeholder="Qty">
                                    </td>
                                    <td class="text-center">
                                        <div class="select">
                                            <select class="form_global availability_inv" name="inv[`+invCount+`][availability_inv]">
                                                <option value="2">In Stock</option>
                                                <option value="3">Out of Stock</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>`;
                            }else{
                                var inv = `<tr class="inv_`+color.id+`" id="inv_row_`+color.id+invCount+`" data-id="`+color.id+`">
                                    <td class="text-left">`+color.name+`
                                        <input type="hidden" name="inv[`+invCount+`][id]" value="0">
                                        <input type="hidden" name="inv[`+invCount+`][color_id]" value="`+color.id+`">
                                        <input type="hidden" name="inv[`+invCount+`][color_name]" value="`+color.name+`">
                                        <input type="hidden" name="inv[`+invCount+`][sort]" value="`+invCount+`">
                                    </td>
                                    <td class="text-center">
                                        <div class="select">
                                            <select class="form_global" name="inv[`+invCount+`][color_size]" required="1">
                                                `+allsize+`
                                            </select>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input class="text-center form_global" type="number" name="inv[`+invCount+`][qty]" value="999" autocomplete="off" placeholder="Qty">
                                    </td>
                                    <td class="text-center">
                                        <div class="select">
                                            <select class="form_global availability_inv" name="inv[`+invCount+`][availability_inv]">
                                                <option value="2">In Stock</option>
                                                <option value="3">Out of Stock</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="addnewrow"
                                            data-colorid="`+color.id+`"
                                            data-rowid="`+color.id+invCount+`"
                                            data-colorname="`+color.name+`"
                                        ><i class="fa fa-plus-circle" ></i></span>
                                        <span class="removerow"><i class="fa fa-minus-circle" ></i></span>
                                    </td>
                                </tr>`;
                            }

                            var defaultcolor =`<option value="`+color.id+`" id="default_color_`+color.id+`" data-unavailcolor="`+color.id+`">`+color.name+`</option>`;
                            $('#default_color').append(defaultcolor);
                            $('#itemInv').append(inv);

                            updateImageColors();
                        }
                        $('#color_search').val('');
                        $('#btnAddColor').attr('disabled','disabled');
                    } else {
                        $('#select_master_color').closest('.display_inline').removeClass('d_none');
                    }
                }
            }

            $(document).on('click','#btnAddSize',function(){
                allsize = '';
                $.each(sizes, function(i, e) {
                    allsize+= '<option value="'+e.id+'">'+e.item_size+'</option>';
                });

                addSize();
            });

            function addSize() {
                var text = $('#selectedSize').val();

                if (text != '') {
                    var size = '';

                    $.each(sizes, function (i, s) {
                        if (s.id == text)
                            size = s;
                    });
                    if (size != '') {
                        var html = $('#templateSize').html();
                        row = $(html);
                        row.find('.name').html(size.item_size);
                        row.find('.templateSize').val(size.id);
                        row.find('.size-available').attr('name', 'size_available_'+size.id);
                        row.find('.size-available').attr('id', 'size_available_'+size.id);
                        row.find('.size_check_available').attr('data-sizeid', +size.id);
                        row.find('.size-available').attr('for', 'size_available_'+size.id);
                        row.find('.custom-checkbox').attr('for', 'size_available_'+size.id);
                        row.find('.form-check-input').attr('name', 'size_available_'+size.id);
                        row.find('.form-check-input').attr('id', 'size_available_'+size.id);
                        var availability_inv_glob = $('.availability_inv_glob').val();

                        $('.sizes-ul').append(row);

                        var invCount = $('#itemInv').find('tr').length;

                        var inv = `<tr>
                            <td class="text-left">`+size.item_size+`
                                <input type="hidden" name="inv[`+invCount+`][id]" value="0">
                                <input type="hidden" name="inv[`+invCount+`][size_id]" value="`+size.id+`">
                                <input type="hidden" name="inv[`+invCount+`][size_name]" value="`+size.item_size+`">
                                <input type="hidden" name="inv[`+invCount+`][sort]" value="`+invCount+`">
                            </td>
                            <td class="text-center">
                                <input class="text-center form_global" type="number" name="inv[`+invCount+`][qty]" value="999" autocomplete="off" placeholder="Qty">
                            </td>
                            <td class="text-center">
                                <div class="select">
                                    <select class="form_global availability_inv" name="inv[`+invCount+`][availability_inv]">
                                        <option value="2">In Stock</option>
                                        <option value="3">Out of Stock</option>
                                    </select>
                                </div>
                            </td>
                        </tr>`;

                        $('#itemInv').append(inv);
                        $('#selectedSize').val('');
                    }
                }
            }

            function withoutSpecification() {
                var availability_inv_glob = $('.availability_inv_glob').val();

                var invCount = $('#itemInv').find('tr').length;

                var inv = `<tr>
                    <td class="text-center">
                        <input class="text-center form_global" type="number" name="inv[`+invCount+`][qty]" value="999" autocomplete="off" placeholder="Qty">
                    </td>
                    <td class="text-center">
                        <div class="select">
                            <select class="form_global availability_inv" name="inv[`+invCount+`][availability_inv]">
                                <option value="2">In Stock</option>
                                <option value="3">Out of Stock</option>
                            </select>
                        </div>
                    </td>
                </tr>`;

                $('#itemInv').append(inv);
            }

            $('body').on('change','.availability_inv_glob', function(){
                var availability_inv = $(this).val();
                $(this).closest('table').find('.availability_inv').val(availability_inv);
            });

            $('body').on('change','.availability_inv', function(){
                $('.availability_inv_glob').val('');
                var availability_inv = $(this).val();
            });

            $(document).on('click','.addnewrow',function(){
                var allsize = [];
                var inv ='';
                $.each(sizes, function(i, e) {
                    allsize+= '<option value="'+e.id+'">'+e.item_size+'</option>';
                });
                var invCount = $('#itemInv').find('tr').length +1;
                var colorid = $(this).data('colorid')
                var rowid = $(this).data('rowid')
                var colorname = $(this).data('colorname')
                var availability_inv_glob = $('.availability_inv_glob').val();
                var div = $(this).closest("tr");
                inv = `<tr class="inv_`+colorid+`" id="inv_row_`+colorid+invCount+`" data-id="`+colorid+`">
                    <td class="text-left">`+colorname+`
                        <input type="hidden" name="inv[`+invCount+`][id]" value="0">
                        <input type="hidden" name="inv[`+invCount+`][color_id]" value="`+colorid+`">
                        <input type="hidden" name="inv[`+invCount+`][color_name]" value="`+colorname+`">
                        <input type="hidden" name="inv[`+invCount+`][sort]" value="`+invCount+`">
                    </td>
                    <td class="text-center">
                        <div class="select">
                            <select class="form_global" name="inv[`+invCount+`][color_size]" required="1">
                                `+allsize+`
                            </select>
                        </div>
                    </td>
                    <td class="text-center">
                        <input class="text-center form_global" type="number" name="inv[`+invCount+`][qty]" value="999" autocomplete="off" placeholder="Qty">
                    </td>
                    <td class="text-center">
                        <div class="select">
                            <select class="form_global availability_inv" name="inv[`+invCount+`][availability_inv]">
                                <option value="2">In Stock</option>
                                <option value="3">Out of Stock</option>
                            </select>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="addnewrow"
                            data-colorid="`+colorid+`"
                            data-rowid="`+colorid+invCount+`"
                            data-colorname="`+colorname+`"
                        ><i class="fa fa-plus-circle" ></i></span>
                        <span class="removerow"><i class="fa fa-minus-circle" ></i></span>
                    </td>
                </tr>`;
                $( inv).insertAfter( "#itemInv #inv_row_"+rowid+"" )
            });

            $(document).on('click','.removerow',function(){
                $(this).closest("tr").remove();
            });

            var el = document.getElementById('itemInv');
                Sortable.create(el, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    return true;
                },
            });

            function updateImageColors() {
                var ids = [];

                $( "input[name*='colors']" ).each(function () {
                    ids.push($(this).val());
                });

                $('.image-color').each(function () {
                    var selected = $(this).val();

                    $(this).html('<option value="">Color [Default]</option>');
                    $this = $(this);

                    $.each(ids, function (index, id) {
                        var color = colors.filter(function( obj ) {
                            return obj.id == id;
                        });
                        color = color[0];

                        if (color.id == selected)
                            $this.append('<option value="'+color.id+'" selected>'+color.name+'</option>');
                        else
                            $this.append('<option value="'+color.id+'">'+color.name+'</option>');
                    });
                });
            }
            updateImageColors();

            $(document).on('click', '.color-remove', function () {
                var target = $(this).closest('li');
                var color_id = target.find('.templateColor').val();
                $("#itemInv").find("tr.inv_"+color_id).remove();
                $(this).closest('li').remove();
            });

            $(document).on('click', '.size-remove', function () {
                var target = $(this).closest('li');
                var size_id = target.find('.templateSize').val();
                $("#itemInv").find("tr.inv_"+size_id).remove();
                $(this).closest('li').remove();
            });

            //Item Specification Start
            $(document).on('change','#specification', function() {
                specification = $('#specification').val();

                if(specification == 1){
                    $('.color_accrodion').css('display' , 'block');
                    $('.color-remove').click().trigger('change');
                    $('.size-remove').click().trigger('change');
                    $('.size_accrodion').css('display' , 'none');
                    $('.inv_th_color').css('display' , 'table-cell');
                    $('.inv_th_size').css('display' , 'table-cell');
                    $('.inv_th_action').css('display' , 'table-cell');
                    $('.image-color').css('display' , 'block');
                    $('#itemInv').empty();
                }

                if(specification == 2){
                    $('.color_accrodion').css('display' , 'block');
                    $('.size_accrodion').css('display' , 'none');
                    $('.color-remove').click().trigger('change');
                    $('.size-remove').click().trigger('change');
                    $('.inv_th_color').css('display' , 'table-cell');
                    $('.inv_th_size').css('display' , 'none');
                    $('.inv_th_action').css('display' , 'none');
                    $('.image-color').css('display' , 'block');
                    $('#itemInv').empty();
                }

                if(specification == 3){
                    $('.color_accrodion').css('display' , 'none');
                    $('.color-remove').click().trigger('change');
                    $('.size_accrodion').css('display' , 'block');
                    $('.inv_th_color').css('display' , 'none');
                    $('.inv_th_size').css('display' , 'table-cell');
                    $('.inv_th_action').css('display' , 'none');
                    $('.image-color').css('display' , 'none');
                    $('#itemInv').empty();
                }

                if(specification == 4){
                    $('.color_accrodion').css('display' , 'none');
                    $('.size_accrodion').css('display' , 'none');
                    $('.inv_th_color').css('display' , 'none');
                    $('.inv_th_size').css('display' , 'none');
                    $('.inv_th_action').css('display' , 'none');
                    $('.image-color').css('display' , 'none');
                    $('#itemInv').empty();
                    withoutSpecification();
                }
            });

            // Images
            var el = document.getElementById('image-container');
            Sortable.create(el, {
                group: "words",
                animation: 150,
            });

            $('.create_images_drag').on({
                'dragover dragenter': function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                },
                'drop': function(e) {
                    var dataTransfer =  e.originalEvent.dataTransfer;
                    if( dataTransfer && dataTransfer.files.length) {
                        e.preventDefault();
                        e.stopPropagation();
                        $.each( dataTransfer.files, function(i, file) {
                            if (file.size > 3072000) {
                                alert('Max allowed image size is 3MB per image.')
                            } else if (file.type != 'image/jpeg' && file.type != 'image/png') {
                                alert('Only jpg and png file allowed.');
                            } else if ($(".image-container").length > 2) {
                                alert('Maximum 20 photos allows');
                            } else {
                                var xmlHttpRequest = new XMLHttpRequest();
                                xmlHttpRequest.open("POST", '{{ route('admin_item_upload_image') }}', true);
                                var formData = new FormData();
                                formData.append("file", file);
                                xmlHttpRequest.send(formData);

                                xmlHttpRequest.onreadystatechange = function() {
                                    if (xmlHttpRequest.readyState == XMLHttpRequest.DONE) {
                                        var response = JSON.parse(xmlHttpRequest.responseText);

                                        if (response.success) {
                                            var html = $('#imageTemplate').html();
                                            var item = $(html);
                                            item.find('.img').attr('src', response.data.fullPath);
                                            item.find('.inputImageId').val(response.data.id);
                                            item.find('.inputImageSrc').val(response.data.image_path);

                                            $('#image-container').append(item);
                                            $('#uploadedImagesCount').html($('.inputImageId').length);

                                            if(specification == 3 || specification == 4){
                                                $('.image-color').css('display' , 'none');
                                            }else{
                                                $('.image-color').css('display' , 'block');
                                                updateImageColors();
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            });

            $('#select_master_color').change(function(){
                var id = $(this).val()
                if(id == 'add'){
                    $('#addMasterColor').addClass('open_modal')
                }
            })
            $('#addMasterColorSave').click(function(){
                var color_name = $('#color_name').val();
                var master_color_code = $('#master_color_code').val();
                if (color_name == '')
                    return alert('Input Color Name');
                if (master_color_code == '')
                    return alert('Input Color Code');
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_item_add_master_color') }}",
                    data: { name: color_name, color_code:master_color_code }
                }).done(function( data ) {
                    if (data.success) {
                        $('#select_master_color').append(`<option selected='selected' value="${data.color.id}">${data.color.name}</option>`)
                        $('#addMasterColor').removeClass('open_modal')
                    } else {
                        alert(data.message);
                    }
                });

            })

            $('body').on('click', '.btnRemoveImage', function () {
                $(this).closest('li').remove();
                $('#uploadedImagesCount').html($('.inputImageId').length);
                $('#btnUploadImages').prop('disabled', false);
            });

            // Upload images button
            $('#btnUploadImages').click(function (e) {
                e.preventDefault();

                $('#inputImages').click();
            });

            $('#inputImages').change(function (e) {
                $.each(e.target.files, function (index, file) {
                    if (file.size > 2097152) {
                        alert('Max allowed image size is 2MB per image.')
                    } else if (file.type != 'image/jpeg' && file.type != 'image/png' && file.type != 'image/gif') {
                        alert('Only jpg and png file allowed.');
                    } else if ($(".image-container").length > 2) {
                        alert('Maximum 20 photos allows');
                    } else {
                        var xmlHttpRequest = new XMLHttpRequest();
                        xmlHttpRequest.open("POST", '{{ route('admin_item_upload_image') }}', true);
                        var formData = new FormData();
                        formData.append("file", file);
                        xmlHttpRequest.send(formData);

                        xmlHttpRequest.onreadystatechange = function() {
                            if (xmlHttpRequest.readyState == XMLHttpRequest.DONE) {
                                var response = JSON.parse(xmlHttpRequest.responseText);

                                if (response.success) {
                                    var html = $('#imageTemplate').html();
                                    var item = $(html);
                                    item.find('.img').attr('src', response.data.fullPath);
                                    item.find('.inputImageId').val(response.data.id);
                                    item.find('.inputImageSrc').val(response.data.image_path);

                                    $('#image-container').append(item);

                                    if(specification == 3 || specification == 4){
                                        $('.image-color').css('display' , 'none');
                                    }else{
                                        $('.image-color').css('display' , 'block');
                                        updateImageColors();
                                    }
                                }
                            }
                        }
                    }
                });

                $(this).val('');
            });

            window.addEventListener("dragover",function(e){
                e = e || event;
                e.preventDefault();
            },false);
            window.addEventListener("drop",function(e){
                e = e || event;
                e.preventDefault();
            },false);

            $('#form').bind('submit', function () {
                $(this).find(':input').prop('disabled', false);
            });

            $('#btnSave').click(function (e) {
                e.preventDefault();
                $('#form').submit();
            });
        });
        CKEDITOR.replace( 'description' );
    </script>
@stop
