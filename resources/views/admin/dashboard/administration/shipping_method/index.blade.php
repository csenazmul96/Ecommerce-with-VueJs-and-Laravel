@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="row {{ ($errors && sizeof($errors) > 0) ? 'd-none' : '' }}" id="addBtnRow">
        <div class="col-md-12">
            <button class="btn btn-primary" id="btnAddNewPack">Add New Shipping Method</button>
        </div>
    </div>

    <div class="row {{ ($errors && sizeof($errors) > 0) ? '' : 'd-none' }}" id="addEditRow">
        <div class="col-md-12" style="border: 1px solid black">
            <h3><span id="addEditTitle">{{ old('inputAdd') == '0' ? 'Edit Shipping Method' : 'Add Shipping Method' }}</span></h3>

            <form class="form-horizontal" id="form" method="post" action="{{ (old('inputAdd') == '1') ? route('admin_shipping_method_add_post') : route('admin_shipping_method_edit_post') }}">
                @csrf

                <input type="hidden" name="inputAdd" id="inputAdd" value="{{ old('inputAdd') }}">
                <input type="hidden" name="shippingMethodId" id="shippingMethodId" value="{{ old('shippingMethodId') }}">

                <div class="form-group row">
                    <div class="col-lg-1">
                        <label for="status" class="col-form-label">Status</label>
                    </div>

                    <div class="col-lg-5">
                        <label for="statusActive" class="custom-control custom-radio">
                            <input id="statusActive" name="status" type="radio" class="custom-control-input"
                                   value="1" {{ (old('status') == '1' || empty(old('status'))) ? 'checked' : '' }}>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Active</span>
                        </label>
                        <label for="statusInactive" class="custom-control custom-radio signin_radio4">
                            <input id="statusInactive" name="status" type="radio" class="custom-control-input" value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Inactive</span>
                        </label>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('courier') ? ' has-danger' : '' }}">
                    <div class="col-lg-1">
                        <label for="courier" class="col-form-label">Courier *</label>
                    </div>

                    <div class="col-lg-5">
                        <select class="form-control{{ $errors->has('courier') ? ' is-invalid' : '' }}" name="courier" id="courier">
                            <option value="">Select Courier</option>

                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id }}" data-index="{{ $loop->index }}"
                                        {{ old('courier') == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('type') ? ' has-danger' : '' }}">
                    <div class="col-lg-1">
                        <label for="courier" class="col-form-label">Shipping Type *</label>
                    </div>
                    <div class="col-lg-5">
                        <select class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" id="type">
                            <option value="Expedited">Expedited Shipping</option>
                            <option value="Standard">Standard Shipping</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('ship_method') ? ' has-danger' : '' }}">
                    <div class="col-lg-1">
                        <label for="ship_method" class="col-form-label">Ship Method *</label>
                    </div>

                    <div class="col-lg-5">
                        <select class="form-control{{ $errors->has('ship_method') ? ' is-invalid' : '' }}" name="ship_method" id="ship_method">
                            <option value="">Select Ship Method</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('list_order') ? ' has-danger' : '' }}">
                    <div class="col-lg-1">
                        <label for="list_order" class="col-form-label">List Order *</label>
                    </div>

                    <div class="col-lg-5">
                        <input type="text" id="list_order" class="form-control{{ $errors->has('list_order') ? ' is-invalid' : '' }}"
                               name="list_order" value="{{ old('list_order') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-1">
                        <label for="default" class="col-form-label">Default</label>
                    </div>

                    <div class="col-lg-5">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="default" value="1" name="default" {{ old('default') ? 'checked' : '' }}>
                            <span class="custom-control-indicator"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12 text-right">
                        <button class="btn btn-primary" id="btnCancel">Cancel</button>
                        <input type="submit" id="btnSubmit" class="btn btn-primary" value="{{ old('inputAdd') == '0' ? 'Update' : 'Add' }}">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Courier</th>
                        <th>Ship Method</th>
                        <th>List Order</th>
                        <th>Created On</th>
                        <th>Type</th>
                        <th>Active</th>
                        <th>Default</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($shippingMethods as $shippingMethod)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shippingMethod->courier->name }}</td>
                            <td>{{ $shippingMethod->shipMethod->name }}</td>
                            <td>{{ $shippingMethod->list_order }}</td>
                            <td>{{ date('d/m/Y', strtotime($shippingMethod->created_at)) }}</td>
                            <td>{{ $shippingMethod->type }}</td>
                            <td>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" data-id="{{ $shippingMethod->id }}" class="custom-control-input status" value="1" {{ $shippingMethod->status == 1 ? 'checked' : '' }}>
                                    <span class="custom-control-indicator"></span>
                                </label>
                            </td>
                            <td>
                                <label class="custom-control custom-radio">
                                    <input type="radio" name="defaultTable" class="custom-control-input default" data-id="{{ $shippingMethod->id }}"
                                           value="1" {{ $shippingMethod->default == 1 ? 'checked' : '' }}>
                                    <span class="custom-control-indicator"></span>
                                </label>
                            </td>
                            <td>
                                <a class="btnEdit" data-id="{{ $shippingMethod->id }}" data-index="{{ $loop->index }}" role="button" style="color: blue">Edit</a> |
                                <a class="btnDelete" data-id="{{ $shippingMethod->id }}" role="button" style="color: red">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" role="dialog" aria-labelledby="deleteModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title text-white" id="deleteModal">Delete</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure want to delete?
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn  btn-default" data-dismiss="modal">Close</button>
                    <button class="btn  btn-danger" id="modalBtnDelete">Delete</button>
                </div>
            </div>
        </div>
        <!--- end modals-->
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

            var shippingMethods = <?php echo json_encode($shippingMethods->toArray()); ?>;
            var couriers = <?php echo json_encode($couriers->toArray()); ?>;

            var selectedId;
            var message = '{{ session('message') }}';
            var old_ship_method = '{{ old('ship_method') }}';

            if (message != '')
                toastr.success(message);


            $('#btnAddNewPack').click(function () {
                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Add Shipping Method');
                $('#btnSubmit').val('Add');
                $('#inputAdd').val('1');
                $('#form').attr('action', '{{ route('admin_shipping_method_add_post') }}');
            });

            $('#btnCancel').click(function (e) {
                e.preventDefault();

                $('#addEditRow').addClass('d-none');
                $('#addBtnRow').removeClass('d-none');

                // Clear form
                $('#statusActive').prop('checked', true);
                $('#default').prop('checked', false);
                $('#courier').val('');
                $('#courier').trigger('change');
                $('#list_order').val('');

                $('input').removeClass('is-invalid');
                $('.form-group').removeClass('has-danger');
            });

            $('.btnEdit').click(function () {
                var id = $(this).data('id');
                var index = $(this).data('index');

                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Edit Shipping Method');
                $('#btnSubmit').val('Update');
                $('#inputAdd').val('0');
                $('#form').attr('action', '{{ route('admin_shipping_method_edit_post') }}');
                $('#shippingMethodId').val(id);

                var shippingMethod = shippingMethods[index];

                if (shippingMethod.status == 1)
                    $('#statusActive').prop('checked', true);
                else
                    $('#statusInactive').prop('checked', true);

                if (shippingMethod.type == 'Expedited')
                    $('#type option[value="Expedited"]').prop('checked', true);
                else
                    $('#type option[value="Standard"]').prop('checked', true);

                if (shippingMethod.default == 1)
                    $('#default').prop('checked', true);
                else
                    $('#default').prop('checked', false);

                $('#courier').val(shippingMethod.courier_id);
                $('#list_order').val(shippingMethod.list_order);
                old_ship_method = shippingMethod.ship_method_id;
                $('#courier').trigger('change');
            });

            $('.btnDelete').click(function () {
                $('#deleteModal').modal('show');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_shipping_method_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });

            $('.status').change(function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_shipping_method_change_status') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });

            $('.default').change(function () {
                var id = $(this).data('id');

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_shipping_method_change_default') }}",
                    data: { id: id }
                }).done(function( msg ) {
                    toastr.success('Default Updated!');
                });
            });

            $('#courier').change(function () {
                var val = $(this).val();
                var index = $('#courier option:selected').data('index');

                $('#ship_method').html('<option value="">Select Ship Method</option>');

                if (val != '') {
                    shipMethods = couriers[index].ship_methods;

                    $.each(shipMethods, function (i, value) {
                        if (old_ship_method == value.id)
                            $('#ship_method').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#ship_method').append('<option value="'+value.id+'">'+value.name+'</option>');
                    })
                }
            });

            $('#courier').trigger('change');
        })
    </script>
@stop
