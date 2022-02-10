@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="vendor_info_content">
    <div class="tab_wrapper pa_0">
        <div class="ly_tab">
            <nav class="tabs tab_with_link">
                <div class="tab_three">
                    <ul>
                        <li id="CompanyInfoTab" href="#CompanyInfo" class="tab_link" data-toggle="tab">Company Info</li>
                        <li id="SizeChartTab" href="#SizeChart" class="tab_link" data-toggle="tab">Instructions</li>
                        {{-- <li id="OrderNoticeTab" href="#OrderNotice" class="tab_link" data-toggle="tab">Order Notice</li> --}}
                        {{-- <li id="SettingsTab" href="#Settings" class="tab_link" data-toggle="tab">Settings</li> --}}
                         <li id="ShippingTab" href="#Shipping" class="tab_link" data-toggle="tab">Shipping Policy</li>
                        <li id="ReturnPolicyTab" href="#ReturnPolicy" class="tab_link" data-toggle="tab">Return Policy</li>
                    </ul>
                </div>
            </nav>
            <div class="tab_content_wrapper">
                <div id="CompanyInfo" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.vendor_information.includes.company_info')
                    </div>
                </div>

                <div id="SizeChart" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.vendor_information.includes.size_chart')
                    </div>
                </div>

                {{-- <div id="OrderNotice" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.vendor_information.includes.order_notice')
                    </div>
                </div> --}}
                {{-- <div id="Settings" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.vendor_information.includes.settings')
                    </div>
                </div> --}}
                <div id="Shipping" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.vendor_information.includes.shipping')
                    </div>
                </div>
                <div id="ReturnPolicy" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.vendor_information.includes.return_policy')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            var usStates = <?php echo json_encode($usStates); ?>;
            var caStates = <?php echo json_encode($caStates); ?>;
            var showroomStateId = '{{ $user->vendor->billing_state_id }}';
            var warehouseStateId = '{{ $user->vendor->factory_state_id }}';

            var options = {
                filebrowserImageBrowseUrl: '{{ url('laravel-filemanager') }}?type=Images',
                filebrowserImageUploadUrl: '{{ url('laravel-filemanager') }}/upload?type=Images&_token=',
                filebrowserBrowseUrl: '{{ url('laravel-filemanager') }}?type=Files',
                filebrowserUploadUrl: '{{ url('laravel-filemanager') }}?type=Files&_token='
            };

            $('#showroom_country').change(function () {
                var countryId = $(this).val();
                $('#showroom_state').html('<option value="">Select State</option>');

                if (countryId == 1) {
                    $.each(usStates, function (index, value) {
                        if (value.id == showroomStateId)
                            $('#showroom_state').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#showroom_state').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                } else if (countryId == 2) {
                    $.each(caStates, function (index, value) {
                        if (value.id == showroomStateId)
                            $('#showroom_state').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#showroom_state').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });

            $('#warehouse_country').change(function () {
                var countryId = $(this).val();
                $('#warehouse_state').html('<option value="">Select State</option>');

                if (countryId == 1) {
                    $.each(usStates, function (index, value) {
                        if (value.id == warehouseStateId)
                            $('#warehouse_state').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#warehouse_state').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                } else if (countryId == 2) {
                    $.each(caStates, function (index, value) {
                        if (value.id == warehouseStateId)
                            $('#warehouse_state').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#warehouse_state').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });

            $('#showroom_country').trigger('change');
            $('#warehouse_country').trigger('change');

            $('#companyInfoSave').click(function () {
                $('#showroom_address').removeClass('is-invalid');
                $('#showroom_city').removeClass('is-invalid');
                $('#showroom_state').removeClass('is-invalid');
                $('#showroom_zip_code').removeClass('is-invalid');
                $('#showroom_country').removeClass('is-invalid');
                $('#showroom_tel').removeClass('is-invalid');
                $('#warehouse_address').removeClass('is-invalid');
                $('#warehouse_city').removeClass('is-invalid');
                $('#warehouse_state').removeClass('is-invalid');
                $('#warehouse_zip_code').removeClass('is-invalid');
                $('#warehouse_country').removeClass('is-invalid');
                $('#warehouse_tel').removeClass('is-invalid');

                var error = false;

                var companyDescription = $('#company_description').val();
                var showroomAddress = $('#showroom_address').val();
                var showroomCity = $('#showroom_city').val();
                var showroomState = $('#showroom_state').val();
                var showroomZipCode = $('#showroom_zip_code').val();
                var showroomCountry = $('#showroom_country').val();
                var showroomTel = $('#showroom_tel').val();
                var showroomAlt = $('#showroom_alt').val();
                var showroomFax = $('#showroom_fax').val();
                var warehouseAddress = $('#warehouse_address').val();
                var warehouseCity = $('#warehouse_city').val();
                var warehouseState = $('#warehouse_state').val();
                var warehouseZipCode = $('#warehouse_zip_code').val();
                var warehouseCountry = $('#warehouse_country').val();
                var warehouseTel = $('#warehouse_tel').val();
                var warehouseAlt = $('#warehouse_alt').val();
                var warehouseFax = $('#warehouse_fax').val();

                if (showroomAddress == ''){
                    error = true;
                    $('#showroom_address').addClass('is-invalid');
                }

                if (showroomCity == ''){
                    error = true;
                    $('#showroom_city').addClass('is-invalid');
                }

                if (showroomState == ''){
                    error = true;
                    $('#showroom_state').addClass('is-invalid');
                }

                if (showroomZipCode == ''){
                    error = true;
                    $('#showroom_zip_code').addClass('is-invalid');
                }

                if (showroomCountry == ''){
                    error = true;
                    $('#showroom_country').addClass('is-invalid');
                }

                if (showroomCountry == ''){
                    error = true;
                    $('#showroom_country').addClass('is-invalid');
                }

                if (showroomTel == ''){
                    error = true;
                    $('#showroom_tel').addClass('is-invalid');
                }

                if (warehouseAddress == ''){
                    error = true;
                    $('#warehouse_address').addClass('is-invalid');
                }

                if (warehouseCity == ''){
                    error = true;
                    $('#warehouse_city').addClass('is-invalid');
                }

                if (warehouseState == ''){
                    error = true;
                    $('#warehouse_state').addClass('is-invalid');
                }

                if (warehouseZipCode == ''){
                    error = true;
                    $('#warehouse_zip_code').addClass('is-invalid');
                }

                if (warehouseCountry == ''){
                    error = true;
                    $('#warehouse_country').addClass('is-invalid');
                }

                if (warehouseTel == ''){
                    error = true;
                    $('#warehouse_tel').addClass('is-invalid');
                }

                var showroomCountry = $('#showroom_country').val();
                var showroomTel = $('#showroom_tel').val();
                var showroomAlt = $('#showroom_alt').val();
                var showroomFax = $('#showroom_fax').val();
                var warehouseAddress = $('#warehouse_address').val();
                var warehouseCity = $('#warehouse_city').val();
                var warehouseState = $('#warehouse_state').val();
                var warehouseZipCode = $('#warehouse_zip_code').val();
                var warehouseCountry = $('#warehouse_country').val();
                var warehouseTel = $('#warehouse_tel').val();
                var warehouseAlt = $('#warehouse_alt').val();
                var warehouseFax = $('#warehouse_fax').val();

                if (!error) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_company_information_post') }}",
                        data: {
                            company_description: companyDescription,
                            showroom_address: showroomAddress,
                            showroom_city: showroomCity,
                            showroom_state: showroomState,
                            showroom_zip_code: showroomZipCode,
                            showroom_country: showroomCountry,
                            showroom_tel: showroomTel,
                            showroom_alt: showroomAlt,
                            showroom_fax: showroomFax,
                            warehouse_address: warehouseAddress,
                            warehouse_city: warehouseCity,
                            warehouse_state: warehouseState,
                            warehouse_zip_code: warehouseZipCode,
                            warehouse_country: warehouseCountry,
                            warehouse_tel: warehouseTel,
                            warehouse_alt: warehouseAlt,
                            warehouse_fax: warehouseFax,
                        }
                    }).done(function( msg ) {
                        toastr.success("Company Info Updated!");
                    });
                }
            });

            CKEDITOR.config.allowedContent = true;
            var sizeEditor = CKEDITOR.replace( 'size_chart_editor', options);
            // var orderNotice = CKEDITOR.replace( 'order_notice_editor' );
            var returnPolicy = CKEDITOR.replace( 'order_return_policy', options);
            var shipping = CKEDITOR.replace( 'shipping', options);

            $('#btnSizeChartSubmit').click(function (e) {
                e.preventDefault();
                var description = sizeEditor.getData();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_size_chart_post') }}",
                    data: { description: description }
                }).done(function( msg ) {
                    toastr.success("Size Chart Updated!");
                });
            });

            // $('#btnOrderNoticeSubmit').click(function (e) {
            //     e.preventDefault();
            //     var description = orderNotice.getData();

            //     $.ajax({
            //         method: "POST",
            //         url: "{{ route('admin_order_notice_post') }}",
            //         data: { description: description }
            //     }).done(function( msg ) {
            //         toastr.success("Order Notice Updated!");
            //     });
            // });


            // $('#btnSaveSettings').click(function (e) {
            //     e.preventDefault();

            //     $.ajax({
            //         method: "POST",
            //         url: "{{ route('admin_save_setting_post') }}",
            //         data: $('#form-settings').serialize(),
            //     }).done(function( data ) {
            //         if (data.success) {
            //             toastr.success("Settings Saved!");
            //         } else {
            //             alert(data.message);
            //         }
            //     });
            // });

            $('#btnreturnPolicySave').click(function (e) {
                e.preventDefault();
                var description = returnPolicy.getData();
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_return_policy_post') }}",
                    data: { description: description }
                }).done(function( msg ) {
                    toastr.success("Return Policy Updated!");
                });
            });
            $('#shippingPolicySave').click(function (e) {
                e.preventDefault();
                var description = shipping.getData();
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_shipping_notice_save') }}",
                    data: { description: description }
                }).done(function( msg ) {
                    toastr.success("Shipping Policy Updated!");
                });
            });
        })
    </script>
@stop
