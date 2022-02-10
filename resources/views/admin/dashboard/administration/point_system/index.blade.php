@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="vendor_info_content">
    <div class="tab_wrapper pa_0">
        <div class="ly_tab">
            <nav class="tabs">
                <ul class="tab_four">
                    <li href="#Settings">Settings</li>
                    <li href="#PointsDollarDiscount" class=" active">Discount</li>
                </ul>   
            </nav>
            <div class="tab_content_wrapper">
                <div id="Settings" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.point_system.settings')
                    </div>
                </div>

                <div id="PointsDollarDiscount" class="tab_content show active">
                    <div class="fadein">
                        @include('admin.dashboard.administration.point_system.dollar_discount')                   
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
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

            //save settings
            $('#btnSaveSettings').click(function (e) {
                e.preventDefault();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_save_point_system_setting_post') }}",
                    data: $('#form-settings').serialize(),
                }).done(function( data ) {
                    if (data.success) {
                        toastr.success("Settings Saved!");
                    } else {
                        alert(data.message);
                    }
                });
            });

            //save discount
            $('#btnSaveDiscount').click(function (e) {
                e.preventDefault();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_save_discount_setting_post') }}",
                    data: $('#form-discounts').serialize(),
                }).done(function( data ) {
                    if (data.success) {
                        toastr.success("Data Saved!");
                    } else {
                        alert(data.message);
                    }
                });
            });

            //points
            var points = <?php echo json_encode($points->toArray()); ?>;
            var selectedId;

            //discount or unit field disable if free shipping is checked  
            $("#free_shipping_1").change(function(event){
                if (this.checked){
                    $("#discount_1").attr("disabled", true);
                }else{
                    $("#discount_1").attr("disabled", false);
                }
            });

            $('#btnAddNew').click(function () {
                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Add Point');
                $('#btnSubmit').val('Add');
                $('#inputAdd').val('1');
                $('#form').attr('action', '{{ route('admin_points_add_post') }}');
            });

            $('#btnCancel').click(function (e) {
                e.preventDefault();

                $('#addEditRow').addClass('d-none');
                $('#addBtnRow').removeClass('d-none');

                // Clear form
                $('#typeFixed').prop('checked', true);
                $('#statusActive').prop('checked', true);

                $('input').removeClass('is-invalid');
                $('.form-group').removeClass('has-danger');
            });

            $('.btnEdit').click(function () {
                var id = $(this).data('id');
                var index = $(this).data('index');

                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Edit Point');
                $('#btnSubmit').val('Update');
                $('#inputAdd').val('0');
                $('#form').attr('action', '{{ route('admin_points_edit_post') }}');
                $('#pointId').val(id);

                var point = points[index];

                if(point.free_shipping_1 == 1){
                    $("#discount_1").attr("disabled", true);
                }

                if (point.status == 1)
                {
                    $('#statusActive').prop('checked', true);
                } else {
                    $('#statusInactive').prop('checked', true);
                }

                if(point.point_type == 'Unit price discount by order amount') {
                    $('.dollarSpan').html('$ ');
                    $('.discountSpan').html('off unit price');
                } else {
                    $('.dollarSpan').html('');
                    $('.discountSpan').html('% discount');
                }


                $('#point_type').val(point.point_type);

                $('#from_price_1').val(point.from_price_1);

                if(point.point_type == 'Percentage discount by order amount') {
                    $('#discount_1').val(point.percentage_discount_1);
                } else {
                    $('#discount_1').val(point.unit_price_discount_1);
                }
                if(point.free_shipping_1 == 1) {
                    $('#free_shipping_1').prop('checked', true);
                } else {
                    $('#free_shipping_1').prop('checked', false);
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
                    url: "{{ route('admin_points_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });


            var pointType = $('#point_type').val();

            if(pointType == 'Unit price discount by order amount') {
                $('.dollarSpan').html('$ ');
                $('.discountSpan').html('off unit price');
            } else {
                $('.dollarSpan').html('');
                $('.discountSpan').html('% discount');
            }


            $('#point_type').change(function() {

            var pointType = $(this).val();

            if(pointType == 'Unit price discount by order amount') {
                $('.dollarSpan').html('$ ');
                $('.discountSpan').html('off unit price');
            } else {
                $('.dollarSpan').html('');
                $('.discountSpan').html('% discount');
            }

            });
        });
    </script>
@stop