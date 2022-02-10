@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="ly_page_wrapper{{ ($errors && sizeof($errors) > 0) ? '' : ' d-none' }}" id="addEditRow">
        <form class="form-horizontal" enctype="multipart/form-data" id="form"
                method="post" action="{{ (old('inputAdd') == '1') ? route('admin_item_values_add') : route('admin_item_values_update') }}">
            @csrf
            <input type="hidden" name="inputAdd" id="inputAdd" value="{{ old('inputAdd') }}">
            <input type="hidden" name="valueId" id="valueId" value="{{ old('valueId') }}">
            <div class="add_new_cat_color">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitle">{{ old('inputAdd') == '0' ? 'Edit Item Value' : 'Add Item Value' }}</h3>

                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                        Status
                    </div>
                    <div class="form_inline">
                        <div class="custom_radio">
                            <input type="radio" id="statusActiveValue" name="statusValue" class="custom-control-input"
                                    value="1" {{ (old('statusValue') == '1' || empty(old('statusValue'))) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusActiveValue">Active</label>
                        </div>
                        <div class="custom_radio">
                            <input type="radio" id="statusInactiveValue" name="statusValue" class="custom-control-input"
                                    value="0" {{ old('statusValue') == '0' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusInactiveValue">Inactive</label>
                        </div>
                    </div>
                </div>

                <div class="form_row">
                    <div class="label_inline required width_150p">
                            Value Name
                    </div>
                    <div class="form_inline display_inline">
                        <input type="text" id="value_name" class="form_global{{ $errors->has('value_name') ? ' is-invalid' : '' }}"
                        placeholder="Value Name" name="value_name" value="{{ old('value_name') }}">
                        <span class="font_italic color_grey_type2 font_12p"> NB: (Maximum 30 Character Supported Here)</span>
                    </div>
                </div>

                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                        Item Value Link
                    </div>
                    <div class="form_inline">
                        <input type="text" id="value_link" class="form_global{{ $errors->has('value_link') ? ' is-invalid' : '' }}"
                        placeholder="Value Link" name="value_link" value="{{ old('value_link') }}">
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                        Item Value Description
                    </div>
                    <div class="form_inline">
                        <textarea class="form_global{{ $errors->has('value_description') ? ' is-invalid' : '' }}" name="value_description" id="value_description" rows="8" 
                            placeholder="Item Value Description">{{ old('value_description') }}</textarea>
                        <span class="font_italic color_grey_type2 font_12p"> NB: (Maximum 350 Character Supported Here)</span>
                    </div>
                </div>
                
                <div class="form_row{{ $errors->has('icon') ? ' has-danger' : '' }}">
                    <div class="label_inline required width_150p fw_500">
                        Upload Value Icon
                    </div>
                    <div class="form_inline align_middle">
                        <input type="text" class="form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}" name="icon" value="{{ old('icon') }}" id="itemIcon">
                        <span class="font_italic color_grey_type2 font_12p"> NB: (only fontawesome 5.1.0 icon support here)</span>
                    </div>

                    <div class="display_inline pl_5">
                        <a class="ly_btn btn_grey min_width_100p" id="btnSelectIcon">Select Icon</a>
                    </div>
                </div>
                <div class="form_row{{ $errors->has('photo') ? ' has-danger' : '' }}">
                    <div class="label_inline width_150p align_middle fw_500">
                        Upload Value Image
                    </div>
                    <div class="form_inline align_middle">
                        <input type="file" class="form-control{{ $errors->has('photo') ? ' is-invalid' : '' }}" name="photo" accept="image/*">
                        <span class="font_italic color_grey_type2 ml_20 font_12p"> Required Size: 650x650px .jpg, .gif or .png Accepted.</span>
                    </div>
                    <div class="form_inline">
                        <div class="text_right">
                            <div class="display_inline">
                                <button class="ly_btn btn_grey min_width_100p close_item_color" id="btnCancel">Cancel</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button type="submit" class="ly_btn btn_blue min_width_100p" id="btnSubmit">{{ old('inputAdd') == '0' ? 'Update' : 'Add' }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="item_color_heading m15">
        <div class="ly-wrap">
            <div class="ly-row">
                <div class="ly-6 pl_0">
                    <div class="item_color_heading_left">
                        <span class="{{ ($errors && sizeof($errors) > 0) ? 'd-none' : '' }}" id="addBtnRow">
                            <span class="link mr_20 item_color_btn" id="btnAddNew">+ Add New Item Value</span>
                        </span>
                        <span class="font_italic color_grey_type2">You currently have {{ $itemValues->total() }} Item Values.</span>
                    </div>
                </div>
                <div class="ly-6 pr_0">
                    <div class="item_color_heading_right text_right">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="item_size_content">
        <table class="table header-border">
            <thead>
                <tr>
                    <th># <span class="data_table_arrow"></span> </th>
                    <th>value Icon <span class="data_table_arrow"></span> </th>
                    <th>value Image <span class="data_table_arrow"></span> </th>
                    <th>Value Name  <span class="data_table_arrow"></span> </th>
                    <th class="text_center width_150p">Active  <span class="data_table_arrow"></span> </th>
                    <th class="width_150p">Action <span class="data_table_arrow"></span> </th>
                </tr>
            </thead>
            <tbody id="ValueTbody">
                @foreach($itemValues as $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="item_selected_icon">
                        <i class="{{ $value->icon }}"></i>
                    </td>
                    <td>
                        <img src="{{ ($value->image_path) ? asset($value->image_path) : asset('images/no-image.png') }}" height="50px" width="50px">
                    </td>
                    <td>{{ $value->name }}</td>
                    <td class="text_center">
                        <div class="custom_checkbox">
                            <input type="checkbox" id="mics_{{ $value->id }}" data-id="{{ $value->id }}" class="statusValue"
                            value="1" {{ $value->status == 1 ? 'checked' : '' }}>
                            <label for="mics_{{ $value->id }}" class="pr_0"></label>
                        </div>
                    </td>
                    <td>
                        <a class="btnEdit" data-id="{{ $value->id }}" data-index="{{ $loop->index }}" role="button"><span class="color_blue">Edit</span></a> |
                        <a class="btnDelete" data-id="{{ $value->id }}" role="button"><span class="color_red">Delete</span></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $itemValues->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $itemValues->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $itemValues->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $itemValues->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $itemValues->lastPage() }}" class="form_global p1" value="{{ $itemValues->currentPage() }}"> of {{ $itemValues->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $itemValues->currentPage() < $itemValues->lastPage() ?  ' btn_paginate' : ''}}"{{ $itemValues->currentPage() == $itemValues->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $itemValues->currentPage() < $itemValues->lastPage() ?  ' btn_paginate' : ''}}"{{ $itemValues->currentPage() == $itemValues->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>

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

    <div id="iconModal" class="modal" data-modal="iconModal">
        <div class="modal_overlay" data-modal-close="iconModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_380p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Item Value Icons</span>
                    </div>
                    <div class="modal_content pa15">
                        <div class="form_row mb_0 pt_15 item_value_icon">
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fas fa-hand-spock"></i>
                                </span>
                            </div>
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fab fa-pagelines"></i>
                                </span>
                            </div>
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fab fa-bandcamp"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form_row mb_0 pt_15 item_value_icon">
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fas fa-american-sign-language-interpreting"></i>
                                </span>
                            </div>
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fab fa-envira"></i>
                                </span>
                            </div>
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fas fa-broom"></i>
                                </span>
                            </div>
                            <div class="form_inline display_inline">
                                <span>
                                    <i class="fas fa-diagnoses"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form_row mb_0 pt_15">
                            <div class="form_inline">
                                <div class="text_right">
                                    <div class="display_inline mr_0">
                                        <button data-modal-close="iconModal" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
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
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var pageCount = <?php echo $itemValues->lastPage(); ?>;

            $('.p1').keyup(function() {

                if($(this).val() > pageCount) {

                    $('.p1').val(pageCount);

                } else {

                    $('.p1').val($(this).val());

                }
                
            });

            $('.switch_page').click(function() {

                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;

                    }
                }

                window.location = switchPageUrl;

            });

            $('.p1_first').click(function() {

                var p1 = 1;
                var currentLocation = String(window.location);
                
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;

                    }
                    

                }
                
                window.location = switchPageUrl;

            });

            $('.p1_prev').click(function() {

                var p1 = <?php echo $itemValues->currentPage(); ?> - 1;
                var currentLocation = String(window.location);
                
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;

                    }
                }
                
                window.location = switchPageUrl;

            });

            $('.p1_next').click(function() {

                var p1 = <?php echo $itemValues->currentPage(); ?> + 1;
                var currentLocation = String(window.location);
                
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;

                    }
                }
                
                window.location = switchPageUrl;

            });

            $('.p1_last').click(function() {

                var p1 = <?php echo $itemValues->lastPage(); ?>;
                var currentLocation = String(window.location);
                
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;

                    }
                }
                
                window.location = switchPageUrl;

            });

            var itemValues = <?php echo json_encode($itemValues->toArray()); ?>;
            itemValues = itemValues.data;
            var selectedId;
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('#btnAddNew').click(function () {
                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Add Item Value');
                $('#btnSubmit').html('Add');
                $('#inputAdd').val('1');
                $('#form').attr('action', '{{ route('admin_item_values_add') }}');
            });

            $('#btnCancel').click(function (e) {
                e.preventDefault();

                $('#addEditRow').addClass('d-none');
                $('#addBtnRow').removeClass('d-none');

                // Clear form
                $('#value_name').val('');
                $('#value_link').val('');
                $('#value_description').val('');
                $('#itemIcon').val('');
                $('input').removeClass('is-invalid');
            });

            $('.btnEdit').click(function () {
                var id = $(this).data('id');
                var index = $(this).data('index');

                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Edit Item Value');
                $('#btnSubmit').html('Update');
                $('#inputAdd').val('0');
                $('#form').attr('action', '{{ route('admin_item_values_update') }}');
                $('#valueId').val(id);

                var value = itemValues[index];

                if (value.status == 1)
                    $('#statusActiveValue').prop('checked', true);
                else
                    $('#statusInactiveValue').prop('checked', true);

                $('#value_name').val(value.name);
                $('#value_link').val(value.link);
                $('#value_description').val(value.description);
                $('#itemIcon').val(value.icon);
                
            });

            $('#btnSelectIcon').click(function () {
                $('#iconModal').addClass('open_modal');
            });

            $(".item_value_icon .form_inline span i").on("click", function() {
                var iconValue = $(this).attr('class');
                $('#itemIcon').val('');
                var elem = $('#itemIcon');
                elem.val(elem.val() + iconValue);
                $('#iconModal').removeClass('open_modal');
            });

            $('.btnDelete').click(function () {
                $('#deleteModal').addClass('open_modal');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_item_values_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });
        })
    </script>
@stop