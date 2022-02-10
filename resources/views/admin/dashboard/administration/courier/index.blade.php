@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/Nestable/style.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <span class="link mr_20 item_color_btn" id="btnAddNew">+ Add New Courier</span>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($couriers as $courier)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $courier->name }}</td>

                        <td>
                            <a class="link btnEdit" data-id="{{ $courier->id }}" data-index="{{ $loop->index }}" role="button" style="color: blue">Edit</a> |
                            <a class="link btnDelete" data-id="{{ $courier->id }}" role="button" style="color: red">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" data-modal="addEditModal">
        <div class="modal_overlay" data-modal-close="addEditModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Add Courier</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="addEditModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">                      
                            <form class="form-horizontal" id="form" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="" id="modalInputId">
                                <div class="form_row">
                                    <div class="label_inline required width_150p">
                                        <label for="modalName">Name</label>
                                    </div>
                                    <div class="form_inline">
                                        <input type="text" id="modalName" class="form_global" placeholder="Courier Name" name="name">
                                    </div>
                                </div>
                                
                                <div class="ly-row">
                                    <div class="ly-12">
                                        <div class="display_table m15">
                                            <div class="float_right">                                            
                                                <button class="ly_btn btn_danger " data-modal-close="addEditModal">Close</button>
                                                <button class="ly_btn btn_blue " id="modalBtnAdd">Add</button>
                                                <button class="ly_btn btn_blue " id="modalBtnUpdate">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

            var couriers = <?php echo json_encode($couriers); ?>;
            var selectedID;
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('#btnAddNew').click(function () {
                $('#modalName').removeClass('is-invalid');
                $('#modalName').val('');
                $('#modalBtnAdd').show();
                $('#modalBtnUpdate').hide();
                $('#form').attr('action', '{{ route('admin_courier_add') }}');

                var targeted_modal_class = 'addEditModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnAdd').click(function (e) {
                e.preventDefault();
                var name = $('#modalName').val();

                if (name == '') {
                    $('#modalName').addClass('is-invalid');
                } else {
                    $('#form').submit();
                }
            });

            $('.btnDelete').click(function () {
                selectedID = $(this).data('id');
                var targeted_modal_class = 'deleteModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_courier_delete') }}",
                    data: { id: selectedID }
                }).done(function( msg ) {
                    location.reload();
                });
            });

            $('.btnEdit').click(function () {
                selectedID = $(this).data('id');
                var index = $(this).data('index');
                var courier = couriers[index];

                $('#modalName').removeClass('is-invalid');
                $('#modalName').val(courier.name);

                $('#modalBtnAdd').hide();
                $('#modalBtnUpdate').show();

                $('#form').attr('action', '{{ route('admin_courier_update') }}');
                $('#modalInputId').val(selectedID);
                var targeted_modal_class = 'addEditModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnUpdate').click(function (e) {
                e.preventDefault();
                var name = $('#modalName').val();

                if (name == '') {
                    $('#modalName').addClass('is-invalid');
                } else {
                    $('#form').submit();
                }
            });
        })
    </script>
@stop