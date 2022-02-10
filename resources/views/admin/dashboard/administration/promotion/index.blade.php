@extends('admin.layouts.main')



@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="ly_accrodion">
    <div class="ly_accrodion_heading">
        <div id="btnAddNew" class="ly_accrodion_title {{ count($errors) > 0 ? ' open_acc' : ''}}" data-toggle="accordion" data-target="#addPromotion" data-class="accordion">
            <span id="addEditTitle">{{ old('inputAdd') == '0' ? 'Edit Coupon' : 'Add Coupon' }}</span>
        </div>
    </div>
    <div class="accordion_body default_accrodion  {{ count($errors) > 0 ? ' open' : ''}}" id="addPromotion" style="">
        <form class="form-horizontal" enctype="multipart/form-data" id="form"
              method="post" action="{{ (old('inputAdd') == '0') ? route('admin_promotions_edit_post') : route('admin_promotions_add_post') }}">
            @csrf

            <input type="hidden" name="inputAdd" id="inputAdd" value="{{ old('inputAdd') }}">
            <input type="hidden" name="promotionId" id="promotionId" value="{{ old('promotionId') }}">

            <div class="form_row">
                <div class="label_inline required width_150p">
                    Status:
                </div>
                <div class="form_inline">
                    <div class="custom_radio">
                        <input type="radio" id="statusActive" name="status" value="1" checked="">
                        <label for="statusActive">Active</label>
                    </div>
                    <div class="custom_radio">
                        <input type="radio" id="statusInactive" name="status" value="0">
                        <label for="statusInactive">Inactive</label>
                    </div>
                </div>
            </div>

            <div class="form_row">
                <div class="label_inline required width_150p">
                    <label for="promotion_type" class="col-form-label">Promotion Type </label>
                </div>
                <div class="form_inline">
                    <div class="select">
                        <select class="form_global" name="promotion_type" id="promotion_type">
                            <option value="Percentage discount by order amount">Percentage discount by order amount</option>
                            <option value="Unit price discount by order amount">Unit price discount by order amount</option>
                       </select>
                    </div>
                </div>
            </div>

            <div class="form_row">
                <div class="label_inline width_150p">
                    <label for="title" class="col-form-label">Title </label>
                </div>
                <div class="form_inline">
                    <input type="text" id="title" class="form_global"
                           placeholder="Promotion Title" name="title" value="{{ old('title') }}">
                </div>
            </div>

            <div class="form_row">
                <div class="label_inline width_150p">
                    <label for="title" class="col-form-label">Description </label>
                </div>
                <div class="form_inline">
                    <textarea class="form_global" name="description" id="description" rows="3" cols="106">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline required width_150p">
                    <label class="col-form-label">Validity </label>
                </div>
                <div class="form_inline" style="display: flex">
                    <div class="custom_radio">
                        <input id="permanentYes" name="is_permanent" type="radio" class="custom-control-input date_range_hide"
                               value="1" {{ (old('is_permanent') == '1' || empty(old('is_permanent'))) ? 'checked' : '' }}>
                        <label for="permanentYes" class="custom-control custom-radio">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Permanent</span>
                        </label>
                    </div>

                    <div class="custom_radio">
                        <input id="permanentNo" name="is_permanent" type="radio" class="custom-control-input date_range_show" value="0" {{ old('is_permanent') == '0' ? 'checked' : '' }}>
                        <label for="permanentNo" class="custom-control custom-radio" style="display: flex">
                            <span class="custom-control-indicator"></span>From&nbsp;&nbsp;
                            <div class="date_range" style="display: none;">
                                <input type="text" id="valid_from" class="form-control{{ $errors->has('valid_from') ? ' is-invalid' : '' }}"
                                name="valid_from" value="{{ old('valid_from') }}" autocomplete="off">&nbsp;&nbsp;To&nbsp;&nbsp;
                                <span class="custom-control-indicator"></span>
                                <input type="text" id="valid_to" class="form-control{{ $errors->has('valid_to') ? ' is-invalid' : '' }}"
                                    name="valid_to" value="{{ old('valid_to') }}" autocomplete="off">
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline width_150p {{ $errors->has('hasCouponCode') ? ' has-danger' : '' }}">
                    <label for="ship_method" class="col-form-label">Coupon Code  </label>
                </div>
                <div class="form_inline">
                    <div class=" ly-row">
                        <div class="ly-1">
                            <div class="custom_checkbox">
                            <input type="checkbox" class="" value="1" id="hasCouponCode" name="hasCouponCode">
                                <label for="hasCouponCode">coupon</label>
                            </div>
                        </div>

                        <div class="ly-2">
                            <input type="text" id="coupon_code" class="form_global" placeholder="Coupon Code" name="coupon_code">
                        </div>

                        <div class="ly-2">
                            <button id="generate_coupon" class="ly_btn btn_blue btn-sm">Generate Coupon Code</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form_row">
                <div class="label_inline required width_150p">
                    Multiple Use
                </div>
                <div class="form_inline">
                    <div class="custom_radio">
                        <input id="multipleUseActive" name="multiple_use" type="radio" class="custom-control-input"
                               value="1" {{ (old('multiple_use') == '1' || empty(old('multiple_use'))) ? 'checked' : '' }}>
                        <label for="multipleUseActive" class="custom-control custom-radio">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Yes</span>
                        </label>
                    </div>
                    <div class="custom_radio">
                        <input id="multipleUseInactive" name="multiple_use" type="radio" class="custom-control-input" value="0" {{ old('multiple_use') == '0' ? 'checked' : '' }}>
                        <label for="multipleUseInactive" class="custom-control custom-radio">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">No</span>
                        </label>
                    </div>
                </div>
            </div>


            <div class="form_row">
                <div class="label_inline width_150p">
                    <label for="ship_method" class="col-form-label">Discount Details  </label>
                </div>
                <div class="form_inline">

                    <table style="width: 100%; border: 1px solid grey; padding: 5px;">
                        <tr>
                            <th style="width: 40%; text-align: center;">Requirement</th>
                            <th style="width: 60%; text-align: center;">Discount</th>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $ <input type="text" id="from_price_1" name="from_price_1" size="8" class=""> - $ <input type="text" size="8" id="to_price_1" name="to_price_1" class="">
                                <br>
                                <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $ <input type="text" id="from_price_2" name="from_price_2" size="8" class=""> - $ <input type="text" size="8" id="to_price_2" name="to_price_2" class="">
                                <br>
                                <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $ <input type="text" id="from_price_3" name="from_price_3" size="8" class=""> - $ <input type="text" size="8" id="to_price_3" name="to_price_3" class="">
                                <br>
                                <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $ <input type="text" id="from_price_4" name="from_price_4" size="8" class=""> - $ <input type="text" size="8" id="to_price_4" name="to_price_4" class="">
                                <br>
                                <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $ <input type="text" id="from_price_5" name="from_price_5" size="8" class=""> - $ <input type="text" size="8" id="to_price_5" name="to_price_5" class="">
                                <br>
                                <br>
                            </td>
                            <td>
                                <span class="dollarSpan"></span><input type="text" id="discount_1" name="discount_1" size="3" class=""> <span class="discountSpan"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="" value="1" id="free_shipping_1" name="free_shipping_1"><span class="custom-control-indicator"></span><span class="custom-control-description">Free Shipping</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: blue;" class="btnEdit" href="">View Coupon</a>
                                <br>
                                <br>
                                <span class="dollarSpan"></span><input type="text" id="discount_2" name="discount_2" size="3" class=""> <span class="discountSpan"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="" value="1" id="free_shipping_2" name="free_shipping_2"><span class="custom-control-indicator"></span><span class="custom-control-description">Free Shipping</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: blue;" class="btnEdit" href="">View Coupon</a>
                                <br>
                                <br>
                                <span class="dollarSpan"></span><input type="text" id="discount_3" name="discount_3" size="3" class=""> <span class="discountSpan"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="" value="1" id="free_shipping_3" name="free_shipping_3"><span class="custom-control-indicator"></span><span class="custom-control-description">Free Shipping</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: blue;" class="btnEdit" href="">View Coupon</a>
                                <br>
                                <br>
                                <span class="dollarSpan"></span><input type="text" id="discount_4" name="discount_4" size="3" class=""> <span class="discountSpan"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="" value="1" id="free_shipping_4" name="free_shipping_4"><span class="custom-control-indicator"></span><span class="custom-control-description">Free Shipping</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: blue;" class="btnEdit" href="">View Coupon</a>
                                <br>
                                <br>
                                <span class="dollarSpan"></span><input type="text" id="discount_5" name="discount_5" size="3" class=""> <span class="discountSpan"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" class="" value="1" id="free_shipping_5" name="free_shipping_5"><span class="custom-control-indicator"></span><span class="custom-control-description">Free Shipping</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: blue;" class="btnEdit" href="">View Coupon</a>
                                <br>
                                <br>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="label_inline width_250p"></div>
            </div>



        </form>
        <div class="create_item_color">
            <div class="float_right">
                <div class="display_inline">
                    <span id="btnCancel" data-toggle="accordion" data-target="#addPromotion" data-class="accordion" class="accordion_heading" data-class="accordion" id="addPromotionDismiss"><span class="ly_btn btn_danger width_80p " style="text-align:center">Close</span> </span>
                </div>
            </div>
            <div class="float_right">
                <div class="display_inline">
                    <a href="javascript:void(0)" onclick="document.getElementById('form').submit();"><span class="ly_btn  btn_blue " id="btnSubmit">{{ old('inputAdd') == '0' ? 'Update' : 'Add' }}</span> </a>
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
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Coupon Code</th>
                    <th>Permanent</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($promotions as $promotion)
                    <tr>
                        <td>{{ $promotion->title }}</td>
                        <td>{{ $promotion->promotion_type }}</td>
                        <td>
                            @if ($promotion->status == 1)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td>
                            @if ($promotion->hasCouponCode == 1)
                                {{ $promotion->coupon_code }}
                            @else
                                {{ $promotion->coupon_code }}
                            @endif
                        </td>
                        <td>
                            @if ($promotion->is_permanent == 1)
                                Yes
                            @else
                                No
                            @endif
                        </td>
                        <td>{{ $promotion->valid_from }}</td>
                        <td>{{ $promotion->valid_to }}</td>
                        <td>
                            <a class="link btnEdit" data-id="{{ $promotion->id }}" data-index="{{ $loop->index }}" role="button" style="color: blue">Edit</a> |
                            <a class="link btnDelete" data-id="{{ $promotion->id }}" role="button" style="color: red">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $promotions->links() }}
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
    <script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var promotions = <?php echo json_encode($promotions->toArray()); ?>;
            promotions = promotions.data;
            var selectedId;
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('.date_range_show').click(function () {
                $('.date_range').css('display', 'flex');
            });

            $('.date_range_hide').click(function () {
                $('.date_range').css('display', 'none');
            });

            $('#btnAddNew').click(function () {
                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Add Coupon');
                $('#btnSubmit').html('Add');
                $('#inputAdd').val('1');
                $('#form').attr('action', '{{ route('admin_promotions_add_post') }}');
            });

            $('#btnCancel').click(function (e) {
                e.preventDefault();

                $('#addEditRow').addClass('d-none');
                $('#addBtnRow').removeClass('d-none');

                $('#addEditTitle').html('Add Coupon');
                $('.open_acc').removeClass('open_acc');
                $('#btnSubmit').html('Add');
                $('#inputAdd').val('1');
                $('#form').attr('action', '{{ route('admin_promotions_add_post') }}');

                // Clear form
                $('#typeFixed').prop('checked', true);
                $('#multipleUseYes').prop('checked', true);
                $('#name').val('');
                $('#amount').val('');
                $('#description').val('');
                $('#expiry_date').val('');
                $('#statusActive').prop('checked', true);

                $('input').removeClass('is-invalid');
                $('.form-group').removeClass('has-danger');
            });

            $('.btnEdit').click(function () {
                var id = $(this).data('id');
                var index = $(this).data('index');

                $('#addEditRow').removeClass('d-none');
                $('#addBtnRow').addClass('d-none');
                $('#addEditTitle').html('Edit Coupon');
                $('#btnSubmit').html('Update');
                $('#inputAdd').val('0');
                $('#form').attr('action', '{{ route('admin_promotions_edit_post') }}');
                $('#promotionId').val(id);

                var promotion = promotions[index];


                if (promotion.status == 1)
                {
                    $('#statusActive').prop('checked', true);
                } else {
                    $('#statusInactive').prop('checked', true);
                }

                if (promotion.is_permanent == 1)
                {
                    $('#permanentYes').prop('checked', true);
                    $('.date_range').css('display', 'none');

                } else {
                    $('#permanentNo').prop('checked', true);
                    $('.date_range').css('display', 'flex');
                    $('#valid_from').val(promotion.valid_from);
                    $('#valid_to').val(promotion.valid_to);
                }

                if(promotion.promotion_type == 'Unit price discount by order amount') {
                    $('.dollarSpan').html('$ ');
                    $('.discountSpan').html('off unit price');
                } else {
                    $('.dollarSpan').html('');
                    $('.discountSpan').html('% discount');
                }

                if(promotion.hasCouponCode == 1) {
                    $('#hasCouponCode').prop('checked', true);
                    $('#coupon_code').css('display', 'block');
                    $('#generate_coupon').css('display', 'block');
                }

                if (promotion.multiple_use == 1)
                {
                    $('#multipleUseActive').attr('checked', true);
                } else {
                    $('#multipleUseInactive').attr('checked', true);
                }


                $('#promotion_type').val(promotion.promotion_type);
                $('#title').val(promotion.title);
                $('#description').val(promotion.description);
                $('#coupon_code').val(promotion.coupon_code);

                $('#from_price_1').val(promotion.from_price_1);
                $('#to_price_1').val(promotion.to_price_1);

                if(promotion.promotion_type == 'Percentage discount by order amount') {
                    $('#discount_1').val(promotion.percentage_discount_1);
                } else {
                    $('#discount_1').val(promotion.unit_price_discount_1);
                }

                if(promotion.free_shipping_1 == 1) {
                    $('#free_shipping_1').prop('checked', true);
                }

                $('#from_price_2').val(promotion.from_price_2);
                $('#to_price_2').val(promotion.to_price_2);

                if(promotion.promotion_type == 'Percentage discount by order amount') {
                    $('#discount_2').val(promotion.percentage_discount_2);
                } else {
                    $('#discount_2').val(promotion.unit_price_discount_2);
                }

                if(promotion.free_shipping_2 == 1) {
                    $('#free_shipping_2').prop('checked', true);
                }

                $('#from_price_3').val(promotion.from_price_3);
                $('#to_price_3').val(promotion.to_price_3);

                if(promotion.promotion_type == 'Percentage discount by order amount') {
                    $('#discount_3').val(promotion.percentage_discount_3);
                } else {
                    $('#discount_3').val(promotion.unit_price_discount_3);
                }

                if(promotion.free_shipping_3 == 1) {
                    $('#free_shipping_3').prop('checked', true);
                }

                $('#from_price_4').val(promotion.from_price_4);
                $('#to_price_4').val(promotion.to_price_4);

                if(promotion.promotion_type == 'Percentage discount by order amount') {
                    $('#discount_4').val(promotion.percentage_discount_4);
                } else {
                    $('#discount_4').val(promotion.unit_price_discount_4);
                }

                if(promotion.free_shipping_4 == 1) {
                    $('#free_shipping_4').prop('checked', true);
                }

                $('#from_price_5').val(promotion.from_price_5);
                $('#to_price_5').val(promotion.to_price_5);

                if(promotion.promotion_type == 'Percentage discount by order amount') {
                    $('#discount_5').val(promotion.percentage_discount_5);
                } else {
                    $('#discount_5').val(promotion.unit_price_discount_5);
                }

                if(promotion.free_shipping_5 == 1) {
                    $('#free_shipping_5').prop('checked', true);
                }

                if(!$('#addPromotion').is(":visible")) {
                    let target = $('#addPromotion');
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
                    url: "{{ route('admin_promotions_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });

            $('#valid_from').datepicker({
                autoclose: true,
                format: 'mm/dd/yyyy'
            });

            $('#valid_to').datepicker({
                autoclose: true,
                format: 'mm/dd/yyyy'
            });


            var isCouponChecked = $('#hasCouponCode').is(':checked');

            if(isCouponChecked) {
                $('#coupon_code').css('display', 'block');
                $('#generate_coupon').css('display', 'block');
            } else {
                $('#coupon_code').css('display', 'none');
                $('#generate_coupon').css('display', 'none');
            }

            $('#hasCouponCode').click(function() {

                var isChecked = $(this).is(':checked');

                if(isChecked) {
                    $('#coupon_code').css('display', 'block');
                    $('#generate_coupon').css('display', 'block');
                } else {
                    $('#coupon_code').css('display', 'none');
                    $('#generate_coupon').css('display', 'none');
                }
            });


            var promotionType = $('#promotion_type').val();

            if(promotionType == 'Unit price discount by order amount') {
                $('.dollarSpan').html('$ ');
                $('.discountSpan').html('off unit price');
            } else {
                $('.dollarSpan').html('');
                $('.discountSpan').html('% discount');
            }


            $('#promotion_type').change(function() {

            var promotionType = $(this).val();

            if(promotionType == 'Unit price discount by order amount') {
                $('.dollarSpan').html('$ ');
                $('.discountSpan').html('off unit price');
            } else {
                $('.dollarSpan').html('');
                $('.discountSpan').html('% discount');
            }

            });

            $('#generate_coupon').click(function(e) {

                e.preventDefault();

                var couponCode = generateCouponCode(10);

                console.log(couponCode);

                $('#coupon_code').val(couponCode);


            });

            function generateCouponCode(len) {

                var code = '';
                var possibleChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

                for (var i = len; i > 0; i--) {

                    code += possibleChars.charAt(Math.floor(Math.random() * possibleChars.length));

                }

                return code;

            }

        })
    </script>
@stop
