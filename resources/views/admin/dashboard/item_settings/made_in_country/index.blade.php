@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="fadein">
        <div class="ly_page_wrapper mb_15 d-none" id="addEditRowMadeInCountry">
            <div class="add_new_cat_color">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitleMadeInCountry">Add Made In Country</h3>
                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                        Status
                    </div>
                    <div class="form_inline">
                        <div class="custom_radio">
                            <input type="radio" id="statusActiveMadeInCountry" name="statusMadeInCountry" class="custom-control-input"
                                    value="1" {{ (old('statusMadeInCountry') == '1' || empty(old('statusMadeInCountry'))) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusActiveMadeInCountry">Active</label>
                        </div>
                        <div class="custom_radio">
                            <input type="radio" id="statusInactiveMadeInCountry" name="statusMadeInCountry" class="custom-control-input"
                                    value="0" {{ old('statusMadeInCountry') == '0' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="statusInactiveMadeInCountry">Inactive</label>
                        </div>
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline required width_150p fw_500">
                        Made In Country Name
                    </div>
                    <div class="form_inline">
                        <input type="text" class="form_global" id="madeInCountryName">
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline width_150p fw_500">
                            Default
                    </div>
                    <div class="form_inline">
                        <div class="custom_checkbox">
                            <input type="checkbox" name="defaultMadeInCountry" id="defaultMadeInCountry" value="1">
                            <label for="defaultMadeInCountry"></label>
                        </div>
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_inline">
                        <div class="text_right">
                            <div class="display_inline">
                                <button class="ly_btn btn_grey min_width_100p" id="btnCancelMadeInCountry">Cancel</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnAddMadeInCountry">Add</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnUpdateMadeInCountry">Update</button>
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
                            <span class="link mr_20 item_color_btn" id="btnAddNewMadeInCountry">+ Add New Made In Country</span>
                        </div>
                    </div>
                    <div class="ly-6 pr_0">
                        <div class="text_right">
                            <span class="font_italic color_grey_type2 font_12p">You currently have {{ count($madeInCountries) }} Made In Countries.</span>
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
                        <th>Made In Country  <span class="data_table_arrow"></span> </th>
                        <th class="text_center width_150p">Active  <span class="data_table_arrow"></span> </th>
                        <th class="text_center width_150p">Default  <span class="data_table_arrow"></span> </th>
                        <th class="width_150p">Action <span class="data_table_arrow"></span> </th>
                    </tr>
                </thead>
                <tbody id="madeInCountryTbody">
                    @foreach($madeInCountries as $country)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $country->name }}</td>
                        <td class="text_center">
                            <div class="custom_checkbox">
                                <input type="checkbox" id="mics_{{ $country->id }}" data-id="{{ $country->id }}" class="statusMadeInCountry"
                                value="1" {{ $country->status == 1 ? 'checked' : '' }}>
                                <label for="mics_{{ $country->id }}" class="pr_0"></label>
                            </div>
                        </td>
                        <td class="text_center">
                            <div class="custom_radio">
                                <input type="radio" id="micd_{{ $country->id }}" data-id="{{ $country->id }}" class="defaultMadeInCountry"
                                    value="1" {{ $country->default == 1 ? 'checked' : '' }}>
                                <label for="micd_{{ $country->id }}" class="pr_0"></label>
                            </div>
                        </td>
                        <td>
                            <a class="btnEditMadeInCountry" data-id="{{ $country->id }}" data-index="{{ $loop->index }}" role="button"><span class="color_blue">Edit</span></a> |
                            <a class="btnDeleteMadeInCountry" data-id="{{ $country->id }}" role="button"><span class="color_red">Delete</span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $madeInCountries->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $madeInCountries->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $madeInCountries->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $madeInCountries->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $madeInCountries->lastPage() }}" class="form_global p1" value="{{ $madeInCountries->currentPage() }}"> of {{ $madeInCountries->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $madeInCountries->currentPage() < $madeInCountries->lastPage() ?  ' btn_paginate' : ''}}"{{ $madeInCountries->currentPage() == $madeInCountries->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $madeInCountries->currentPage() < $madeInCountries->lastPage() ?  ' btn_paginate' : ''}}"{{ $madeInCountries->currentPage() == $madeInCountries->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>

    <div id="deleteModalMadeInCountry" class="modal" data-modal="deleteModalMadeInCountry">
        <div class="modal_overlay" data-modal-close="deleteModalMadeInCountry"></div>
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
                                        <button data-modal-close="deleteModalMadeInCountry" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDeleteMadeInCountry">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="madeInCountryTrTemplate">
        <tr>
            <td><span class="madeInCountryIndex"></span></td>
            <td><span class="madeInCountryName"></span></td>
            <td class="text_center">
                <div class="custom_checkbox">
                    <input type="checkbox" id="countryId" class="statusMadeInCountry"
                        value="1">
                    <label for="countryId" class="pr_0"></label>
                </div>
            </td>
            <td class="text_center">
                <div class="custom_radio">
                    <input type="radio" id="defaultCountry" name="defaultMadeInCountryTable" class="defaultMadeInCountry"
                        value="1">
                    <label for="defaultCountry" class="pr_0"></label>
                </div>
            </td>
            <td>
                <a class="btnEditMadeInCountry" role="button" style="color: blue">Edit</a> |
                <a class="btnDeleteMadeInCountry" role="button" style="color: red">Delete</a>
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

            // Made in country
            var madeInCountries = <?php echo json_encode($madeInCountries->toArray()); ?>;
            var madeInCountries = madeInCountries.data;
            var selectedMadeInCountryId;
            var selectedMadeInCountryIndex;

            $('#btnAddNewMadeInCountry').click(function () {
                $('#addEditRowMadeInCountry').removeClass('d-none');
                $('#btnAddNewMadeInCountry').addClass('d-none');
                $('#addEditTitleMadeInCountry').html('Add a New Made In Country');

                $('#btnAddMadeInCountry').show();
                $('#btnUpdateMadeInCountry').hide();
            });

            $('#btnCancelMadeInCountry').click(function () {
                $('#addEditRowMadeInCountry').addClass('d-none');
                $('#btnAddNewMadeInCountry').removeClass('d-none');

                // Clear form
                $('#statusActiveMadeInCountry').prop('checked', true);
                $('#madeInCountryName').val('');
                $('#defaultMadeInCountry').prop('checked', false);

                $('#madeInCountryName').removeClass('is-invalid');
            });
            
            $('#btnAddMadeInCountry').click(function () {
                var name = $('#madeInCountryName').val();
                var status = 0;
                var defaultVal = 0;

                if (name == '') {
                    $('#madeInCountryName').addClass('is-invalid');
                } else {
                    if ($('#statusActiveMadeInCountry').is(':checked'))
                        status = 1;

                    if ($('#defaultMadeInCountry').is(':checked'))
                        defaultVal = 1;

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_made_in_country_add') }}",
                        data: { name: name, status: status, defaultVal: defaultVal }
                    }).done(function( country ) {
                        madeInCountries.push(country);

                        var index = madeInCountries.length-1;

                        var html = $('#madeInCountryTrTemplate').html();
                        var row = $(html);
                        row.find('.madeInCountryIndex').html(index+1);
                        row.find('.madeInCountryName').html(name);

                        if (status == 1)
                            row.find('.statusMadeInCountry').prop('checked', true);

                        if (defaultVal == 1) {
                            $('.defaultMadeInCountry').prop('checked', false);
                            row.find('.defaultMadeInCountry').prop('checked', true);
                        }

                        row.find('.statusMadeInCountry').attr("data-id", country.id);
                        row.find('.defaultMadeInCountry').attr("data-id", country.id);
                        row.find('.btnEditMadeInCountry').attr("data-id", country.id);
                        row.find('.btnEditMadeInCountry').attr("data-index", index);
                        row.find('.btnDeleteMadeInCountry').attr("data-index", index);
                        row.find('.btnDeleteMadeInCountry').attr("data-id", country.id);

                        $('#madeInCountryTbody').append(row);

                        toastr.success('Made In Country Added!');
                        $('#btnCancelMadeInCountry').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnEditMadeInCountry', function () {
                var id = $(this).data('id');
                var index = $(this).data('index');
                var country = madeInCountries[index];
                selectedMadeInCountryId = id;
                selectedMadeInCountryIndex = index;

                $('#addEditRowMadeInCountry').removeClass('d-none');
                $('#btnAddNewMadeInCountry').addClass('d-none');
                $('#addEditTitleMadeInCountry').html('Edit Made In Country');

                if (country.status == 1)
                    $('#statusActiveMadeInCountry').prop('checked', true);
                else
                    $('#statusInactiveMadeInCountry').prop('checked', true);

                if (country.default == 1)
                    $('#defaultMadeInCountry').prop('checked', true);
                else
                    $('#defaultMadeInCountry').prop('checked', false);

                $('#madeInCountryName').val(country.name);

                $('#btnAddMadeInCountry').hide();
                $('#btnUpdateMadeInCountry').show();
            });
            
            $('#btnUpdateMadeInCountry').click(function () {
                var name = $('#madeInCountryName').val();
                var status = 0;
                var defaultVal = 0;

                if (name == '') {
                    $('#madeInCountryName').addClass('is-invalid');
                } else {
                    if ($('#statusActiveMadeInCountry').is(':checked'))
                        status = 1;

                    if ($('#defaultMadeInCountry').is(':checked'))
                        defaultVal = 1;

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_made_in_country_update') }}",
                        data: { id: selectedMadeInCountryId, name: name, status: status, defaultVal: defaultVal }
                    }).done(function( country ) {
                        madeInCountries[selectedMadeInCountryIndex] = country;

                        $('.madeInCountryName:eq('+selectedMadeInCountryIndex+')').html(name);

                        if (status == 1)
                            $('.statusMadeInCountry:eq('+selectedMadeInCountryIndex+')').prop('checked', true);
                        else
                            $('.statusMadeInCountry:eq('+selectedMadeInCountryIndex+')').prop('checked', false);

                        if (defaultVal == 1) {
                            $('.defaultMadeInCountry').prop('checked', false);
                            $('.defaultMadeInCountry:eq('+selectedMadeInCountryIndex+')').prop('checked', true);
                        }

                        toastr.success('Made In Country Updated!');
                        $('#btnCancelMadeInCountry').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnDeleteMadeInCountry', function () {
                var id = $(this).data('id');
                var index = $(".btnDeleteMadeInCountry").index(this);
                selectedMadeInCountryId = id;
                selectedMadeInCountryIndex = index;

                $('#deleteModalMadeInCountry').addClass('open_modal');
            });
            
            $('#modalBtnDeleteMadeInCountry').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_made_in_country_delete') }}",
                    data: { id: selectedMadeInCountryId }
                }).done(function( country ) {
                    $('#madeInCountryTbody tr:eq('+selectedMadeInCountryIndex+')').remove();
                    $('#deleteModalMadeInCountry').removeClass('open_modal');
                    toastr.success('Made In Country Deleted!');
                });

            });
            
            $('body').on('change', '.statusMadeInCountry', function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_made_in_country_change_status') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });

            $('body').on('change', '.defaultMadeInCountry', function () {
                var id = $(this).data('id');

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_made_in_country_change_default') }}",
                    data: { id: id }
                }).done(function( msg ) {
                    toastr.success('Default Made In Country Updated!');
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
                var p1 = <?php echo $madeInCountries->currentPage(); ?> - 1;
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
                var p1 = <?php echo $madeInCountries->currentPage(); ?> + 1;
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
                var p1 = <?php echo $madeInCountries->lastPage(); ?>;
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