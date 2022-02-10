@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
@stop

@section('content')
<div class="ly_accrodion">
    <div class="ly_accrodion_heading">
        <div id="btnAddNew" class="ly_accrodion_title {{ count($errors) > 0 ? ' open_acc' : ''}}" data-toggle="accordion" data-target="#addSkinType" data-class="accordion">
            <span id="addEditTitle">{{ old('inputAdd') == '0' ? 'Edit Skin Type' : 'Add Skin Type' }}</span>
        </div>
    </div>
    <div class="accordion_body default_accrodion  {{ count($errors) > 0 ? ' open' : ''}}" id="addSkinType" style="">
        <form class="form-horizontal" enctype="multipart/form-data" id="addSkinTypeForm"
              method="post" action="{{ (old('inputAdd') == '0') ? route('admin_skin_types_update') : route('admin_skin_types_add') }}">
            @csrf
            
            <input type="hidden" name="inputAdd" id="inputAdd" value="{{ old('inputAdd') }}">  
            <input type="hidden" name="skinTypeId" id="skinTypeId" value="{{ old('skinTypeId') }}">  
                    
            <div class="form_row">
                <div class="label_inline required width_150p">
                    <label for="type" class="col-form-label">Type </label>
                </div>
                <div class="form_inline">
                    <input type="text" id="type" class="form_global{{ $errors->has('type') ? ' is-invalid' : '' }}"
                           placeholder="Skin Type" name="type" value="{{ old('type') }}">
                </div>
            </div>
        </form>
        <div class="create_item_color">
            <div class="float_right">
                <div class="display_inline">
                    <span id="btnCancel" data-toggle="accordion" data-target="#addSkinType" data-class="accordion" class="accordion_heading" data-class="accordion" id="addSkinTypeDismiss"><span class="ly_btn btn_danger width_80p " style="text-align:center">Close</span> </span>
                </div>
            </div>
            <div class="float_right">
                <div class="display_inline">
                    <a href="javascript:void(0)" onclick="document.getElementById('addSkinTypeForm').submit();"><span class="ly_btn  btn_blue " id="btnSubmit">{{ old('inputAdd') == '0' ? 'Update' : 'Add' }}</span> </a>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
    <br>

    <div class="ly-row">
        <div class="ly-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Skin Type</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach($skinTypes as $st)
                    <tr>
                        <td>{{ $st->type }}</td>
                        <td>
                            <a class="link btnEdit" data-id="{{ $st->id }}" data-index="{{ $loop->index }}" role="button" style="color: blue">Edit</a> |
                            <a class="link btnDelete" data-id="{{ $st->id }}" role="button" style="color: red">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $skinTypes->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $skinTypes->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $skinTypes->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $skinTypes->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $skinTypes->lastPage() }}" class="form_global p1" value="{{ $skinTypes->currentPage() }}"> of {{ $skinTypes->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $skinTypes->currentPage() < $skinTypes->lastPage() ?  ' btn_paginate' : ''}}"{{ $skinTypes->currentPage() == $skinTypes->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $skinTypes->currentPage() < $skinTypes->lastPage() ?  ' btn_paginate' : ''}}"{{ $skinTypes->currentPage() == $skinTypes->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>

    <div class="modal" data-modal="deleteModal">
        <div class="modal_overlay" data-modal-close="deleteModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Are you sure want to delete?</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="deleteModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_blue width_150p " data-modal-close="deleteModal">Close</button>
                                            <button class="ly_btn btn_danger width_150p" id="modalBtnDelete">Delete</button>
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

            var skinTypes = <?php echo json_encode($skinTypes->toArray()); ?>;
            skinTypes = skinTypes.data;
            var selectedId;
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('#btnAddNew').click(function () {
                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Add Skin Type');
                $('#btnSubmit').html('Add');
                $('#inputAdd').val('1');
                $('#addSkinTypeForm').attr('action', '{{ route('admin_skin_types_add') }}');
            });

            $('#btnCancel').click(function (e) {
                e.preventDefault();

                $('#addEditTitle').html('Add Skin Type');
                $('#btnAddNew').removeClass('open_acc');
                $('#addEditRow').addClass('d-none');
                $('#addBtnRow').removeClass('d-none');

                // Clear form
                $('#type').val('');

                $('input').removeClass('is-invalid');
                $('.form-group').removeClass('has-danger');
            });

            $('.btnEdit').click(function () {
                var id = $(this).data('id');
                var index = $(this).data('index');
                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Edit Skin Type');
                $('#btnSubmit').html('Update');
                $('#inputAdd').val('0');
                $('#addSkinTypeForm').attr('action', '{{ route('admin_skin_types_update') }}');
                $('#skinTypeId').val(id);

                var skinType = skinTypes[index];
                $('#type').val(skinType.type);
                
                if(!$('#addSkinType').is(":visible")) {
                    let target = $('#addSkinType');
                    $('.ly_accrodion_title').toggleClass('open_acc');
                    target.slideToggle();
                }
            });

            $('.btnDelete').click(function () {
                var targeted_modal_class = 'deleteModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_skin_types_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
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
                var p1 = <?php echo $skinTypes->currentPage(); ?> - 1;
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
                var p1 = <?php echo $skinTypes->currentPage(); ?> + 1;
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
                var p1 = <?php echo $skinTypes->lastPage(); ?>;
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