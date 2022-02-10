@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="fadein">
        <div class="ly_page_wrapper mb_15 d-none" id="addEditRowBrand">
            <div class="add_new_cat_color">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitleBrand">Add New Brand</h3>
                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                        Status
                    </div>
                    <div class="form_inline">
                        <div class="custom_radio">
                            <input type="radio" id="statusActiveBrand" name="statusBrand" class="custom-control-input"
                                    value="1" {{ (old('statusBrand') == '1' || empty(old('statusBrand'))) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusActiveBrand">Active</label>
                        </div>
                        <div class="custom_radio">
                            <input type="radio" id="statusInactiveBrand" name="statusBrand" class="custom-control-input"
                                    value="0" {{ old('statusBrand') == '0' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusInactiveBrand">Inactive</label>
                        </div>
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline required width_150p fw_500">
                        Brand Name
                    </div>
                    <div class="form_inline">
                        <input type="text" class="form_global" id="BrandName">
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_inline">
                        <div class="text_right">
                            <div class="display_inline">
                                <button class="ly_btn btn_grey min_width_100p" id="btnCancelBrand">Cancel</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnAddBrand">Add</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnUpdateBrand">Update</button>
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
                            <span class="link mr_20 item_color_btn" id="btnAddNewBrand">+ Add New Brand</span>
                        </div>
                    </div>
                    <div class="ly-6 pr_0">
                        <div class="text_right">
                            <span class="font_italic color_grey_type2 font_12p">You currently have {{ count($brands) }} Brands.</span>
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
                        <th>Brand Name  <span class="data_table_arrow"></span> </th>
                        <th class="text_center width_150p">Active  <span class="data_table_arrow"></span> </th>
                        <th class="width_150p">Action <span class="data_table_arrow"></span> </th>
                    </tr>
                </thead>
                <tbody id="BrandTbody">
                    @foreach($brands as $brand)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $brand->name }}</td>
                        <td class="text_center">
                            <div class="custom_checkbox">
                                <input type="checkbox" id="mics_{{ $brand->id }}" data-id="{{ $brand->id }}" class="statusBrand"
                                value="1" {{ $brand->status == 1 ? 'checked' : '' }}>
                                <label for="mics_{{ $brand->id }}" class="pr_0"></label>
                            </div>
                        </td>
                        <td>
                            <a class="btnEditBrand" data-id="{{ $brand->id }}" data-index="{{ $loop->index }}" role="button"><span class="color_blue">Edit</span></a> |
                            <a class="btnDeleteBrand" data-id="{{ $brand->id }}" role="button"><span class="color_red">Delete</span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $brands->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $brands->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $brands->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $brands->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $brands->lastPage() }}" class="form_global p1" value="{{ $brands->currentPage() }}"> of {{ $brands->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $brands->currentPage() < $brands->lastPage() ?  ' btn_paginate' : ''}}"{{ $brands->currentPage() == $brands->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $brands->currentPage() < $brands->lastPage() ?  ' btn_paginate' : ''}}"{{ $brands->currentPage() == $brands->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>

    <div id="deleteModalBrand" class="modal" data-modal="deleteModalBrand">
        <div class="modal_overlay" data-modal-close="deleteModalBrand"></div>
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
                                        <button data-modal-close="deleteModalBrand" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDeleteBrand">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="BrandTrTemplate">
        <tr>
            <td><span class="BrandIndex"></span></td>
            <td><span class="BrandName"></span></td>
            <td class="text_center">
                <div class="custom_checkbox">
                    <input type="checkbox" id="brandId" class="statusBrand"
                        value="1">
                    <label for="brandId" class="pr_0"></label>
                </div>
            </td>
            <td>
                <a class="btnEditBrand" role="button" style="color: blue">Edit</a> |
                <a class="btnDeleteBrand" role="button" style="color: red">Delete</a>
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
            var brands = <?php echo json_encode($brands->toArray()); ?>;
            var brands = brands.data;
            var selectedBrandId;
            var selectedBrandIndex;

            $('#btnAddNewBrand').click(function () {
                $('#addEditRowBrand').removeClass('d-none');
                $('#btnAddNewBrand').addClass('d-none');
                $('#addEditTitleBrand').html('Add a New Brand');

                $('#btnAddBrand').show();
                $('#btnUpdateBrand').hide();
            });

            $('#btnCancelBrand').click(function () {
                $('#addEditRowBrand').addClass('d-none');
                $('#btnAddNewBrand').removeClass('d-none');
                $('#btnAddNewBrand').css('display' , 'block');

                $('#statusActiveBrand').prop('checked', true);
                $('#BrandName').val('');

                $('#BrandName').removeClass('is-invalid');
            });
            
            $('#btnAddBrand').click(function () {
                var name = $('#BrandName').val();
                var status = 0;

                if (name == '') {
                    $('#BrandName').addClass('is-invalid');
                } else {
                    if ($('#statusActiveBrand').is(':checked'))
                        status = 1;

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_brands_add') }}",
                        data: { name: name, status: status }
                    }).done(function( brand ) {
                        brands.push(brand);
                        var index = brands.length-1;

                        var html = $('#BrandTrTemplate').html();
                        var row = $(html);
                        row.find('.BrandIndex').html(index+1);
                        row.find('.BrandName').html(name);
                        if (status == 1)
                            row.find('.statusBrand').prop('checked', true);

                        row.find('.statusBrand').attr("data-id", brand.id);
                        row.find('.btnEditBrand').attr("data-id", brand.id);
                        row.find('.btnEditBrand').attr("data-index", index);
                        row.find('.btnDeleteBrand').attr("data-index", index);
                        row.find('.btnDeleteBrand').attr("data-id", brand.id);

                        $('#BrandTbody').append(row);

                        toastr.success('Brand Added!');
                        $('#btnCancelBrand').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnEditBrand', function () {
                var id = $(this).data('id');
                var index = $(this).data('index');
                var brand = brands[index];
                selectedBrandId = id;
                selectedBrandIndex = index;

                $('#addEditRowBrand').removeClass('d-none');
                $('#btnAddNewBrand').addClass('d-none');
                $('#addEditTitleBrand').html('Edit Brand');

                if (brand.status == 1)
                    $('#statusActiveBrand').prop('checked', true);
                else
                    $('#statusInactiveBrand').prop('checked', true);

                $('#BrandName').val(brand.name);

                $('#btnAddBrand').hide();
                $('#btnUpdateBrand').show();
                $('#btnAddNewBrand').css('display' , 'none');
            });
            
            $('#btnUpdateBrand').click(function () {
                var name = $('#BrandName').val();
                var status = 0;

                if (name == '') {
                    $('#BrandName').addClass('is-invalid');
                } else {
                    if ($('#statusActiveBrand').is(':checked'))
                        status = 1;

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_brands_update') }}",
                        data: { id: selectedBrandId, name: name, status: status }
                    }).done(function( brand ) {
                        brand[selectedBrandIndex] = brand;

                        $('.BrandName:eq('+selectedBrandIndex+')').html(name);

                        if (status == 1)
                            $('.statusBrand:eq('+selectedBrandIndex+')').prop('checked', true);
                        else
                            $('.statusBrand:eq('+selectedBrandIndex+')').prop('checked', false);

                        toastr.success('Brand Updated!');
                        $('#btnCancelBrand').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnDeleteBrand', function () {
                var id = $(this).data('id');
                var index = $(".btnDeleteBrand").index(this);
                selectedBrandId = id;
                selectedBrandIndex = index;

                $('#deleteModalBrand').addClass('open_modal');
            });
            
            $('#modalBtnDeleteBrand').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_brands_delete') }}",
                    data: { id: selectedBrandId }
                }).done(function( country ) {
                    $('#BrandTbody tr:eq('+selectedBrandIndex+')').remove();
                    $('#deleteModalBrand').removeClass('open_modal');
                    toastr.success('Brand Deleted!');
                });

            });
            
            $('body').on('change', '.statusBrand', function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_brands_change_status') }}",
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
                var p1 = <?php echo $brands->currentPage(); ?> - 1;
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
                var p1 = <?php echo $brands->currentPage(); ?> + 1;
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
                var p1 = <?php echo $brands->lastPage(); ?>;
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