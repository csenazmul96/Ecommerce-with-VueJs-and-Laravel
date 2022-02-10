@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
@stop

@section('content')
    <div class="fadein">
        <div class="ly_page_wrapper mb_15 d-none" id="addEditRowAgeGroup">
            <div class="add_new_cat_color">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitleAgeGroup">Add New Age Group</h3>
                <div class="form_row">
                    <div class="label_inline required width_150p fw_500">
                        Lower Limit
                    </div>
                    <div class="form_inline">
                        <input type="number" id="lower_limit" min="0" class="form_global{{ $errors->has('lower_limit') ? ' is-invalid' : '' }}"
                            placeholder="Lower Limit" name="lower_limit" value="{{ old('lower_limit') }}" oninput="validity.valid||(value='');">
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline required width_150p fw_500">
                        Upper Limit
                    </div>
                    <div class="form_inline">
                        <input type="number" id="upper_limit" min="0" class="form_global{{ $errors->has('upper_limit') ? ' is-invalid' : '' }}"
                            placeholder="Lower Limit" name="upper_limit" value="{{ old('upper_limit') }}" oninput="validity.valid||(value='');">
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_inline">
                        <div class="text_right">
                            <div class="display_inline">
                                <button class="ly_btn btn_grey min_width_100p" id="btnCancelAgeGroup">Cancel</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnAddAgeGroup">Add</button>
                            </div>
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" id="btnUpdateAgeGroup">Update</button>
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
                            <span class="link mr_20 item_color_btn" id="btnAddNewAgeGroup">+ Add New Age Group</span>
                        </div>
                    </div>
                    <div class="ly-6 pr_0">
                        <div class="text_right">
                            <span class="font_italic color_grey_type2 font_12p">You currently have {{ count($ageGroups) }} Age Group.</span>
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
                        <th>Lower Limit  <span class="data_table_arrow"></span> </th>
                        <th>Upper Limit  <span class="data_table_arrow"></span> </th>
                        <th class="width_150p">Action <span class="data_table_arrow"></span> </th>
                    </tr>
                </thead>
                <tbody id="AgeGroupTbody">
                    @foreach($ageGroups as $ageGroup)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ageGroup->lower_limit }}</td>
                        <td>{{ $ageGroup->upper_limit }}</td>
                        <td>
                            <a class="btnEditAgeGroup" data-id="{{ $ageGroup->id }}" data-index="{{ $loop->index }}" role="button"><span class="color_blue">Edit</span></a> |
                            <a class="btnDeleteAgeGroup" data-id="{{ $ageGroup->id }}" role="button"><span class="color_red">Delete</span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $ageGroups->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $ageGroups->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $ageGroups->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $ageGroups->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $ageGroups->lastPage() }}" class="form_global p1" value="{{ $ageGroups->currentPage() }}"> of {{ $ageGroups->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $ageGroups->currentPage() < $ageGroups->lastPage() ?  ' btn_paginate' : ''}}"{{ $ageGroups->currentPage() == $ageGroups->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $ageGroups->currentPage() < $ageGroups->lastPage() ?  ' btn_paginate' : ''}}"{{ $ageGroups->currentPage() == $ageGroups->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>

    <div id="deleteModalAgeGroup" class="modal" data-modal="deleteModalAgeGroup">
        <div class="modal_overlay" data-modal-close="deleteModalAgeGroup"></div>
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
                                        <button data-modal-close="deleteModalAgeGroup" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDeleteAgeGroup">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="AgeGroupTrTemplate">
        <tr>
            <td><span class="AgeGroupIndex"></span></td>
            <td><span class="AgeGroupLowerLimit"></span></td>
            <td><span class="AgeGroupUpperLimit"></span></td>
            <td>
                <a class="btnEditAgeGroup" role="button" style="color: blue">Edit</a> |
                <a class="btnDeleteAgeGroup" role="button" style="color: red">Delete</a>
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
            var ageGroups = <?php echo json_encode($ageGroups->toArray()); ?>;
            var ageGroups = ageGroups.data;
            var selectedAgeGroupId;
            var selectedAgeGroupIndex;

            $('#btnAddNewAgeGroup').click(function () {
                $('#addEditRowAgeGroup').removeClass('d-none');
                $('#btnAddNewAgeGroup').addClass('d-none');
                $('#addEditTitleAgeGroup').html('Add a New Age Group');

                $('#btnAddAgeGroup').show();
                $('#btnUpdateAgeGroup').hide();
            });

            $('#btnCancelAgeGroup').click(function () {
                $('#addEditRowAgeGroup').addClass('d-none');
                $('#btnAddNewAgeGroup').removeClass('d-none');
                $('#btnAddNewAgeGroup').css('display' , 'block');
                // Clear form
                $('#lower_limit').val('');
                $('#upper_limit').val('');

                $('#lower_limit').removeClass('is-invalid');
                $('#upper_limit').removeClass('is-invalid');
            });
            
            $('#btnAddAgeGroup').click(function () {
                var lower_limit = $('#lower_limit').val();
                var upper_limit = $('#upper_limit').val();

                if ((lower_limit || upper_limit) == '') {
                    $('#lower_limit').addClass('is-invalid');
                    $('#upper_limit').addClass('is-invalid');
                }
                if(lower_limit >= upper_limit){
                    $('#lower_limit').addClass('is-invalid');
                }
                if(upper_limit <= lower_limit){
                    $('#upper_limit').addClass('is-invalid');
                }else {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_age_group_add') }}",
                        data: { lower_limit: lower_limit, upper_limit: upper_limit }
                    }).done(function( ageGroup ) {
                        ageGroups.push(ageGroup);
                        var index = ageGroups.length-1;

                        var html = $('#AgeGroupTrTemplate').html();
                        var row = $(html);
                        row.find('.AgeGroupIndex').html(index+1);
                        row.find('.AgeGroupLowerLimit').html(lower_limit);
                        row.find('.AgeGroupUpperLimit').html(upper_limit);

                        row.find('.btnEditAgeGroup').attr("data-id", ageGroup.id);
                        row.find('.btnEditAgeGroup').attr("data-index", index);
                        row.find('.btnDeleteAgeGroup').attr("data-index", index);
                        row.find('.btnDeleteAgeGroup').attr("data-id", ageGroup.id);

                        $('#AgeGroupTbody').append(row);

                        toastr.success('Age Group Added!');
                        $('#btnCancelAgeGroup').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnEditAgeGroup', function () {
                var id = $(this).data('id');
                var index = $(this).data('index');
                var ageGroup = ageGroups[index];
                selectedAgeGroupId = id;
                selectedAgeGroupIndex = index;

                $('#addEditRowAgeGroup').removeClass('d-none');
                $('#btnAddNewAgeGroup').addClass('d-none');
                $('#addEditTitleAgeGroup').html('Edit Age Group');

                $('#lower_limit').val(ageGroup.lower_limit);
                $('#upper_limit').val(ageGroup.upper_limit);

                $('#btnAddAgeGroup').hide();
                $('#btnUpdateAgeGroup').show();
            });
            
            $('#btnUpdateAgeGroup').click(function () {
                var lower_limit = $('#lower_limit').val();
                var upper_limit = $('#upper_limit').val();

                if ((lower_limit || upper_limit) == '') {
                    $('#lower_limit').addClass('is-invalid');
                    $('#upper_limit').addClass('is-invalid');
                }
                if(lower_limit >= upper_limit){
                    $('#lower_limit').addClass('is-invalid');
                }
                if(upper_limit <= lower_limit){
                    $('#upper_limit').addClass('is-invalid');
                }else {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_age_group_update') }}",
                        data: { id: selectedAgeGroupId, lower_limit: lower_limit, upper_limit: upper_limit }
                    }).done(function( ageGroup ) {
                        ageGroup[selectedAgeGroupIndex] = ageGroup;

                        $('.AgeGroupLowerLimit:eq('+selectedAgeGroupIndex+')').html(lower_limit);
                        $('.AgeGroupUpperLimit:eq('+selectedAgeGroupIndex+')').html(upper_limit);

                        toastr.success('Age Group Updated!');
                        $('#btnCancelAgeGroup').trigger('click');
                    });
                }
            });

            $('body').on('click', '.btnDeleteAgeGroup', function () {
                var id = $(this).data('id');
                var index = $(".btnDeleteAgeGroup").index(this);
                selectedAgeGroupId = id;
                selectedAgeGroupIndex = index;

                $('#deleteModalAgeGroup').addClass('open_modal');
            });
            
            $('#modalBtnDeleteAgeGroup').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_age_group_delete') }}",
                    data: { id: selectedAgeGroupId }
                }).done(function( ageGroup ) {
                    $('#AgeGroupTbody tr:eq('+selectedAgeGroupIndex+')').remove();
                    $('#deleteModalAgeGroup').removeClass('open_modal');
                    toastr.success('Age Group Deleted!');
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
                var p1 = <?php echo $ageGroups->currentPage(); ?> - 1;
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
                var p1 = <?php echo $ageGroups->currentPage(); ?> + 1;
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
                var p1 = <?php echo $ageGroups->lastPage(); ?>;
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