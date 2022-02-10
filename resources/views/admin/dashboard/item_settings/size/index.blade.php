@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="fadein">
        <div class="ly_page_wrapper mb_15 d-none" id="addEditRowSize">
            <div class="add_new_cat_color">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitleSize">Add New Size</h3>
                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                        Status
                    </div>
                    <div class="form_inline">
                        <div class="custom_radio">
                            <input type="radio" id="statusActiveSize" name="statusSize" class="custom-control-input"
                                    value="1" {{ (old('statusSize') == '1' || empty(old('statusSize'))) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusActiveSize">Active</label>
                        </div>
                        <div class="custom_radio">
                            <input type="radio" id="statusInactiveSize" name="statusSize" class="custom-control-input"
                                    value="0" {{ old('statusSize') == '0' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusInactiveSize">Inactive</label>
                        </div>
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline required width_150p fw_500">
                        Size Name
                    </div>
                    <div class="form_inline">
                        <input type="text" class="form_global" id="SizeName">
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline required width_150p fw_500">
                        Size Symbol
                    </div>
                    <div class="form_inline">
                        <input type="text" class="form_global" id="SizeSymbol">
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                        Description
                    </div>
                    <div class="form_inline">
                        <input type="text" class="form_global" id="SizeDescription">
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_inline">
                        <div class="text_right">
                            <div class="display_inline">
                                <button class="ly_btn btn_grey min_width_100p" id="btnCancelSize">Cancel</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnAddSize">Add</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnUpdateSize">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="item_color_heading m15 mt_0">
            <div class="ly-wrap">
                <div class="ly-row">
                    <div class="ly-6 pl_0">
                        <div class="item_color_heading_left">
                            <span class="link mr_20 item_color_btn" id="btnAddNewSize">+ Add New Size</span>
                        </div>
                    </div>
                    <div class="ly-6 pr_0">
                        <div class="text_right">
                            <span class="font_italic color_grey_type2 font_12p">You currently have {{ count($sizes) }} Sizes.</span>
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
                        <th>Size Name  <span class="data_table_arrow"></span> </th>
                        <th>Size Symbol  <span class="data_table_arrow"></span> </th>
                        <th>Size Description  <span class="data_table_arrow"></span> </th>
                        <th class="text_center width_150p">Active  <span class="data_table_arrow"></span> </th>
                        <th class="width_150p">Action <span class="data_table_arrow"></span> </th>
                    </tr>
                </thead>
                <tbody id="SizeTbody">
                    @foreach($sizes as $size)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $size->name }}</td>
                        <td>{{ $size->item_size }}</td>
                        <td>{{ $size->desc }}</td>
                        <td class="text_center">
                            <div class="custom_checkbox">
                                <input type="checkbox" id="mics_{{ $size->id }}" data-id="{{ $size->id }}" class="statusSize"
                                value="1" {{ $size->status == 1 ? 'checked' : '' }}>
                                <label for="mics_{{ $size->id }}" class="pr_0"></label>
                            </div>
                        </td>
                        <td>
                            <a class="btnEditSize" data-id="{{ $size->id }}" data-index="{{ $loop->index }}" role="button"><span class="color_blue">Edit</span></a> |
                            <a class="btnDeleteSize" data-id="{{ $size->id }}" role="button"><span class="color_red">Delete</span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $sizes->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $sizes->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $sizes->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $sizes->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $sizes->lastPage() }}" class="form_global p1" value="{{ $sizes->currentPage() }}"> of {{ $sizes->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $sizes->currentPage() < $sizes->lastPage() ?  ' btn_paginate' : ''}}"{{ $sizes->currentPage() == $sizes->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $sizes->currentPage() < $sizes->lastPage() ?  ' btn_paginate' : ''}}"{{ $sizes->currentPage() == $sizes->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>

    <div id="deleteModalSize" class="modal" data-modal="deleteModalSize">
        <div class="modal_overlay" data-modal-close="deleteModalSize"></div>
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
                                        <button data-modal-close="deleteModalSize" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDeleteSize">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="SizeTrTemplate">
        <tr>
            <td><span class="SizeIndex"></span></td>
            <td><span class="SizeName"></span></td>
            <td><span class="SizeSymbol"></span></td>
            <td><span class="SizeDescription"></span></td>
            <td>
                <div class="custom_checkbox">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input statusSize"
                                value="1">
                        <span class="custom-control-indicator"></span>
                    </label>
                </div>
            </td>
            <td>
                <a class="btnEditSize" role="button" style="color: blue">Edit</a> |
                <a class="btnDeleteSize" role="button" style="color: red">Delete</a>
            </td>
        </tr>
    </template>
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

            // Brand
            var sizes = <?php echo json_encode($sizes->toArray()); ?>;
            var sizes = sizes.data;
            var selectedSizeId;
            var selectedSizeIndex;

            $('#btnAddNewSize').click(function () {
                $('#addEditRowSize').removeClass('d-none');
                $('#btnAddNewSize').addClass('d-none');
                $('#addEditTitleSize').html('Add a New Size');

                $('#btnAddSize').show();
                $('#btnUpdateSize').hide();
            });

            $('#btnCancelSize').click(function () {
                $('#addEditRowSize').addClass('d-none');
                $('#btnAddNewSize').removeClass('d-none');
                $('#btnAddNewSize').css('display' , 'block');
                // Clear form
                $('#statusActiveSize').prop('checked', true);
                $('#SizeName').val('');
                $('#SizeSymbol').val('');
                $('#SizeDescription').val('');

                $('#SizeName').removeClass('is-invalid');
                $('#SizeSymbol').removeClass('is-invalid');
                $('#SizeDescription').removeClass('is-invalid');
            });
            
            $('#btnAddSize').click(function () {
                var name = $('#SizeName').val();
                var symbol = $('#SizeSymbol').val();
                var description = $('#SizeDescription').val();
                var status = 0;

                if (name == '') {
                    $('#SizeName').addClass('is-invalid');
                    $('#SizeSymbol').addClass('is-invalid');
                } else {
                    if ($('#statusActiveSize').is(':checked'))
                        status = 1;

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_size_add') }}",
                        data: { name: name, symbol: symbol, description: description ,status: status }
                    }).done(function( size ) {
                        sizes.push(size);
                        var index = sizes.length-1;

                        var html = $('#SizeTrTemplate').html();
                        var row = $(html);
                        row.find('.SizeIndex').html(index+1);
                        row.find('.SizeName').html(name);
                        row.find('.SizeSymbol').html(symbol);
                        row.find('.SizeDescription').html(description);
                        if (status == 1)
                            row.find('.statusSize').prop('checked', true);

                        row.find('.statusSize').attr("data-id", size.id);
                        row.find('.btnEditSize').attr("data-id", size.id);
                        row.find('.btnEditSize').attr("data-index", index);
                        row.find('.btnDeleteSize').attr("data-index", index);
                        row.find('.btnDeleteSize').attr("data-id", size.id);

                        $('#SizeTbody').append(row);

                        toastr.success('Size Added!');
                        $('#btnCancelSize').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnEditSize', function () {
                var id = $(this).data('id');
                var index = $(this).data('index');
                var size = sizes[index];
                selectedSizeId = id;
                selectedSizeIndex = index;

                $('#addEditRowSize').removeClass('d-none');
                $('#btnAddNewSize').addClass('d-none');
                $('#addEditTitleSize').html('Edit Size');

                if (size.status == 1)
                    $('#statusActiveSize').prop('checked', true);
                else
                    $('#statusInactiveSize').prop('checked', true);

                $('#SizeName').val(size.name);
                $('#SizeSymbol').val(size.item_size);
                $('#SizeDescription').val(size.desc);

                $('#btnAddSize').hide();
                $('#btnUpdateSize').show();
            });
            
            $('#btnUpdateSize').click(function () {
                var name = $('#SizeName').val();
                var symbol = $('#SizeSymbol').val();
                var description = $('#SizeDescription').val();
                var status = 0;

                if (name == '') {
                    $('#SizeName').addClass('is-invalid');
                }if(symbol == ''){
                    $('#SizeSymbol').addClass('is-invalid');
                } else {
                    if ($('#statusActiveSize').is(':checked'))
                        status = 1;

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_size_update') }}",
                        data: { id: selectedSizeId, name: name, symbol:symbol, description:description, status: status }
                    }).done(function( size ) {
                        size[selectedSizeIndex] = size;

                        $('.SizeName:eq('+selectedSizeIndex+')').html(name);
                        $('.SizeSymbol:eq('+selectedSizeIndex+')').html(symbol);
                        $('.SizeDescription:eq('+selectedSizeIndex+')').html(description);

                        if (status == 1)
                            $('.statusSize:eq('+selectedSizeIndex+')').prop('checked', true);
                        else
                            $('.statusSize:eq('+selectedSizeIndex+')').prop('checked', false);

                        toastr.success('Size Updated!');
                        $('#btnCancelSize').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnDeleteSize', function () {
                var id = $(this).data('id');
                var index = $(".btnDeleteSize").index(this);
                selectedSizeId = id;
                selectedSizeIndex = index;

                $('#deleteModalSize').addClass('open_modal');
            });
            
            $('#modalBtnDeleteSize').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_size_delete') }}",
                    data: { id: selectedSizeId }
                }).done(function( size ) {
                    $('#SizeTbody tr:eq('+selectedSizeIndex+')').remove();
                    $('#deleteModalSize').removeClass('open_modal');
                    toastr.success('Size Deleted!');
                });

            });
            
            $('body').on('change', '.statusSize', function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_size_change_status') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
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
                var p1 = <?php echo $sizes->currentPage(); ?> - 1;
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
                var p1 = <?php echo $sizes->currentPage(); ?> + 1;
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
                var p1 = <?php echo $sizes->lastPage(); ?>;
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
        })
    </script>
@stop