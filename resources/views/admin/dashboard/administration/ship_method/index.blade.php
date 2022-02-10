@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="ly_page_wrapper{{ ($errors && sizeof($errors) > 0) ? '' : ' d-none' }}" id="addEditRow">
        <form class="form-horizontal" enctype="multipart/form-data" id="form"
                method="post" action="{{ (old('inputAdd') == '1') ? route('admin_ship_method_add') : route('admin_ship_method_update') }}">
            @csrf
            <input type="hidden" name="inputAdd" id="inputAdd" value="{{ old('inputAdd') }}">
            <input type="hidden" name="shipMethodId" id="shipMethodId" value="{{ old('shipMethodId') }}">
            <div class="add_new_ship_method">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitle">{{ old('inputAdd') == '0' ? 'Edit Ship Method' : 'Add Ship Method' }}</h3>
                <div class="form_row">
                    <div class="label_inline required width_150p">
                        <label for="ship_method" class="col-form-label">Ship Method  </label>
                    </div>
                    <div class="form_inline">
                        <input type="text" id="ship_method" class="form_global{{ $errors->has('ship_method') ? ' is-invalid' : '' }}"
                                placeholder="Ship Method" name="ship_method" value="{{ old('ship_method') }}">
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline required width_150p">
                        <label for="courier" class="col-form-label">Courier  </label>
                    </div>
                    <div class="form_inline">
                        <div class="select">
                            <select class="form_global{{ $errors->has('courier') ? ' is-invalid' : '' }}" id="courier"  name="courier">
                                <option value="">Select Courier</option>
                                @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline required width_150p">
                        <label for="courier" class="col-form-label">Courier  </label>
                    </div>
                    <div class="form_inline">
                        <div class="select">
                            <select class="form_global {{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" id="type">
                                <option value="Expedited">Expedited Shipping</option>
                                <option value="Standard">Standard Shipping</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form_row">
                    <div class="label_inline width_150p">
                        <label for="fee" class="col-form-label">Fee  </label>
                    </div>
                    <div class="form_inline">
                        <input type="text" id="fee" class="form-control{{ $errors->has('fee') ? ' is-invalid' : '' }}"
                        placeholder="Fee" name="fee" value="{{ old('fee') }}">
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_inline">
                        <div class="text_right">
                            <div class="display_inline">
                                <button class="ly_btn btn_grey min_width_100p close_ship_method" id="btnCancel">Cancel</button>
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

    <div class="shipping_heading m15">
        <div class="ly-wrap">
            <div class="ly-row">
                <div class="ly-6 pl_0">
                    <div class="shipping_heading_left">
                        <span class="{{ ($errors && sizeof($errors) > 0) ? 'd-none' : '' }}" id="addBtnRow">
                            <span class="link mr_20 item_color_btn" id="btnAddNew">+ Add New Ship Method</span>
                        </span>
                        <span class="font_italic color_grey_type2">You currently have {{ $shipMethods->total() }} Shipping Methods.</span>
                    </div>
                </div>
                <div class="ly-6 pr_0">
                    <div class="shipping_heading_right text_right">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="shipping_method_search p15">
        <div class="ly-wrap">
            <div class="ly-row">
                <form action="{{ route('admin_ship_method') }}">
                    <div class="ly-12 pl_0">
                        <div class="display_inline width_350p">
                            <input type="text" class="form_global" placeholder="Search" name="s" value="{{ request()->get('s') }}">
                        </div>
                        <div class="display_inline">
                            <button type="submit" class="ly_btn btn_blue width_100p toggle_item_search">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Courier</th>
                        <th>Type</th>
                        <th>Fee</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($shipMethods as $sm)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sm->name }}</td>
                            <td>{{ $sm->courier->name }}</td>
                            <td>{{ $sm->type }}</td>
                            <td>
                                @if ($sm->fee === null)
                                    Actual Rate
                                @else
                                    ${{ number_format($sm->fee, 2, '.', '') }}
                                @endif
                            </td>
                            <td>
                                <a class="link btnEdit" data-id="{{ $sm->id }}" data-index="{{ $loop->index }}" role="button" style="color: blue">Edit</a> |
                                <a class="link btnDelete" data-id="{{ $sm->id }}" role="button" style="color: red">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $shipMethods->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $shipMethods->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $shipMethods->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $shipMethods->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $shipMethods->lastPage() }}" class="form_global p1" value="{{ $shipMethods->currentPage() }}"> of {{ $shipMethods->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $shipMethods->currentPage() < $shipMethods->lastPage() ?  ' btn_paginate' : ''}}"{{ $shipMethods->currentPage() == $shipMethods->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $shipMethods->currentPage() < $shipMethods->lastPage() ?  ' btn_paginate' : ''}}"{{ $shipMethods->currentPage() == $shipMethods->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
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
                                        <button data-modal-close="deleteModal" class="ly_btn btn_grey min_width_100p close_ship_method">Close</button>
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

            var pageCount = <?php echo $shipMethods->lastPage(); ?>;

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

                var p1 = <?php echo $shipMethods->currentPage(); ?> - 1;
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

                var p1 = <?php echo $shipMethods->currentPage(); ?> + 1;
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

                var p1 = <?php echo $shipMethods->lastPage(); ?>;
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

            var shipMethods = <?php echo json_encode($shipMethods->toArray()); ?>;
            shipMethods = shipMethods.data;
            var selectedId;
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('#btnAddNew').click(function () {
                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Add Ship Method');
                $('#btnSubmit').html('Add');
                $('#inputAdd').val('1');
                $('#form').attr('action', '{{ route('admin_ship_method_add') }}');
            });

            $('#btnCancel').click(function (e) {
                e.preventDefault();
                $('#addEditRow').addClass('d-none');
                $('#addBtnRow').removeClass('d-none');
                $('#ship_method').val('');
                $('#courier').val('');
                $('input').removeClass('is-invalid');
                $('.form-group').removeClass('has-danger');
            });

            $('.btnEdit').click(function () {

                var id = $(this).data('id');
                var index = $(this).data('index');

                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Edit Ship Method');
                $('#btnSubmit').html('Update');
                $('#inputAdd').val('0');
                $('#addShipMethodForm').attr('action', '{{ route('admin_ship_method_update') }}');
                $('#shipMethodId').val(id);

                var shipMethod = shipMethods[index];

                $('#ship_method').val(shipMethod.name);
                $('#courier').val(shipMethod.courier_id);
                $('#fee').val(shipMethod.fee);

                if (shipMethod.type == 'Expedited')
                    $('#type option[value="Expedited"]').attr("selected", "selected");
                else
                    $('#type option[value="Standard"]').attr("selected", "selected");

            });

            $('.btnDelete').click(function () {
                $('#deleteModal').addClass('open_modal');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_ship_method_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });
        })
    </script>
@stop
