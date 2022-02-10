<?php
use App\Enumeration\Availability;
?>

@extends('admin.layouts.main')

@section('additionalCSS')
    <style>
        input.form-control{
            padding: 2px 15px;
            font-size: 16px;
            line-height: 35px;
        }
        .btn-primary{
            padding: 13px 16px;
        }
        .btn-primary:hover{
            background-color: #000;
            border-color: #000;
            color: #fff;
        }

        .has-danger{
            border: 1px solid red;
        }
    </style>
@stop

@section('content')
    <div class="item_size_content">
        <div class="table-responsive ">
            <form action="{{ route('admin_checkout_post') }}" method="POST" class="cartListForm">
                @csrf
                <div class="ly-wrap">
                    <div class="ly-row">
                        <div class="ly-7">
                            <form action="{{ route('admin_checkout_post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ request()->get('id') }}" id="orders">
                                <div class="ly-row">
                                    <div class="ly-12">
                                        <div class="form_row">
                                            <h2 class="font_18p mb_15 item_change_title">Shipping Address</h2>
                                            <p id="address_text">
                                                @if ($address != null)
                                                    {{ $address->address }}, {{ $address->city }}, {{ ($address->state == null) ? $address->state_text : $address->state->name }},
                                                    <br>
                                                    {{ $address->country->name }} - {{ $address->zip }}
                                                @endif
                                            </p>
                                            <div class="display_inline">
                                                <button class="ly_btn btn_blue" data-modal-open="selectShippingModal" role="button" id="btnChangeAddress">Change</button>
                                                <button class="ly_btn btn_blue" data-modal-open="addEditShippingModal" role="button" id="btnAddShippingAddress">Add New Shipping Address</button>
                                                <input type="hidden" name="address_id" value="{{ ($address != null) ? $address->id : '' }}" id="address_id">
                                            </div>
                                            @if ($errors->has('address_id'))
                                                <div class="form-control-feedback text-danger">Please select or add the shipping address</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-row">
                                    @csrf
                                    <div class="ly-10">
                                        <div class="form_row">
                                            <div class="form_inline">
                                                <input type="text" id="coupon_{{ $order->id }}" value="{{ $order->coupon }}" placeholder="Enter your promo code" class="form_global" name="code">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ly-2">
                                        <div class="form_row">
                                            <div class="form_inline">
                                                <button type="submit" class="ly_btn btn_blue width_full btnApplyCoupon" data-order-id="{{ $order->id }}">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="ly-row">
                                <div class="ly-12">
                                    <h2 class="font_18p mb_15 mt_15 item_change_title">Shipping Method</h2>
                                    <table class="table header-border no-footer">
                                        <thead class="thead-default">
                                            <tr>
                                                <th></th>
                                                <th>Shipping method</th>
                                                <th>Fee</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($shipping_methods as $shipping_method)
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="custom_radio">
                                                        <input class="shipping_method" type="radio"
                                                            id="{{ $shipping_method->id }}" name="shipping_method"
                                                            value="{{ $shipping_method->id }}" data-index="{{ $loop->index }}"
                                                                {{ old('shipping_method') == $shipping_method->id ? 'checked' : '' }}>
                                                        <label for="{{ $shipping_method->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    @if(!empty($shipping_method->courier->name))
                                                        <span class="text-medium">{{ $shipping_method->courier->name }}</span><br>
                                                    @endif
                                                    <span class="text-muted text-sm">{{ $shipping_method->name }}</span>
                                                </td>
                                                <td>
                                                    @if ($shipping_method->fee === null)
                                                        Actual Rate
                                                    @else
                                                        ${{ number_format($shipping_method->fee, 2, '.', '') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @if ($errors->has('shipping_method'))
                                        <div class="form-control-feedback text-danger">Select a shipping method</div>
                                    @endif
                                    <p class="p15">Flat rate prices are for Continental US ONLY Prices for Expedited shipping will be determined by weight, dimensions, and shipping address</p>
                                </div>
                            </div>
                            <div class="ly-row table_bordered mt_15">
                                <div class="ly-12">
                                    <h2 class="font_18p mb_15 mt_15 item_change_title">Payment Method</h2>

                                    <input type="hidden" id="paymentMethod" name="paymentMethod" value="2">

                                    @if ($errors->has('paymentMethod'))
                                        <div class="form-control-feedback text-danger">Select a Payment Method</div>
                                    @endif
                                    @if ($errors->has('number'))
                                        <div class="form-control-feedback text-danger">Invalid Card Number</div>
                                    @endif
                                    @if ($errors->has('cvc'))
                                        <div class="form-control-feedback text-danger">Invalid Card CVC</div>
                                    @endif

                                    <h4 class="font_14p mb_15 mt_15 item_change_title">
                                        <a class="btnPM " href="#collapseTwo" data-id="2">
                                            <i class="fa fa-lock"  ></i> Credit Card
                                        </a>
                                        <img class="float_right" src="{{ asset('images/card.jpg') }}"/>
                                    </h4>
                                </div>
                                <div class="ly-6">
                                    <div class="form_row ">
                                        <div class="form_inline{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <div class="label_inline required mb_5">Card Holder's Name</div>
                                            <input class="form_global" type="text" name="name" placeholder="Full Name"
                                                value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $order->card_full_name) : old('name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-6">
                                    <div class="form_row ">
                                        <div class="form_inline{{ $errors->has('number') ? ' has-danger' : '' }}">
                                            <div class="label_inline required mb_5">Card Number</div>
                                            <input class="form_global" type="text" name="number" placeholder="Card Number"
                                                value="{{ empty(old('number')) ? ($errors->has('number') ? '' : $order->card_number) : old('number') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-6">
                                    <div class="form_row ">
                                        <div class="form_inline{{ $errors->has('expiry') ? ' has-danger' : '' }}">
                                            <div class="label_inline required mb_5">Expiration Date</div>
                                            <input class="form_global" type="text" name="expiry" placeholder="MM/YY"
                                                data-inputmask="'mask': '99/99'" id="expiry"
                                                value="{{ empty(old('expiry')) ? ($errors->has('expiry') ? '' : $order->card_expire) : old('expiry') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-6">
                                    <div class="form_row ">
                                        <div class="form_inline{{ $errors->has('cvc') ? ' has-danger' : '' }}">
                                            <div class="label_inline required mb_5">Secure Code (CVV):</div>
                                            <input class="form_global" type="text" name="cvc" placeholder="CVC"
                                                value="{{ empty(old('cvc')) ? ($errors->has('cvc') ? '' : $order->card_cvc) : old('cvc') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-12">
                                    <p class="p15">CVV2 is that extra set of numbers after the normal 16 or 14 digits of the account usually printed on the back of the credit card. The "CVV2 security code", as it is formally referred to, provides an extra measure
                                        of security and we require it on all transactions.</p>
                                </div>
                            </div>
                            <div class="ly-row m30">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="custom_checkbox">
                                            <input type="checkbox" id="checkbox-agree" name="checkbox-agree" class="mr_20">
                                            <label class=" " for="checkbox-agree">By Selecting this box and clicking the "Place My Order button", I agree that I have read the Policy. Your order may not be complete! Would you like us to contact you before shipping your order?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ly-row m10">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="custom_radio">
                                            <input type="radio" id="can_call_no" name="can_call" value="1">
                                            <label for="can_call_no">YES! Please call me if anything is missing from my order!</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="custom_radio">
                                            <input type="radio" id="can_call" name="can_call" value="0" checked="">
                                            <label for="can_call">NO! Do not call me if something is missing. Just ship me what you have!</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="custom_radio">
                                            <input id="btnSubmit" type="submit" class="ly_btn btn_blue width_200p mt_15 p10  signin_btn place-my-order-btn" value="PLACE MY ORDER">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-5">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="form_row">
                                        <h2 class="font_18p mb_15 item_change_title">Order Summary</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="ly-row">
                                <div class="ly-12">
                                    <table class="table header-border table_bordered no-footer">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Style No</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $thumb = null;
                                                        for($i=0; $i < count($item->item->images); $i++) {
                                                            // if ($item->item->images[$i]->color != null) {
                                                            //     if ($item->item->images[$i]->color->name == $item->color) {
                                                            //         $thumb = $item->item->images[$i];
                                                            //         break;
                                                            //     }
                                                            // }
                                                            $thumb = $item->item->images[$i];
                                                        }
                                                        ?>

                                                        @if ($thumb)
                                                            <img src="{{ Storage::url($thumb->thumbs_image_path) }}" width="50px">
                                                        @endif

                                                    </td>
                                                    <td>{{ $item->style_no }}</td>
                                                    <td> @if(!empty($item->color)) {{ $item->color }} @endif</td>
                                                    <td> @if(!empty($item->itemSize)) {{ $item->itemSize->item_size }} @endif</td>
                                                    <td>${{ number_format($item->per_unit_price, 2, '.', '') }}</td>
                                                    <td>{{ $item->total_qty }}</td>
                                                    <td>${{ number_format($item->per_unit_price * $item->total_qty, 2, '.', '') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th colspan="6">Sub Total</th>
                                                <td>${{ number_format($order->subtotal, 2, '.', '') }}</td>
                                            </tr>
                                            @if ($order->discount != null || $order->discount != 0)
                                                <tr>
                                                    <th colspan="6">Discount</th>
                                                    <td>-${{ number_format($order->discount, 2, '.', '') }}</td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <th colspan="6">Store Credit</th>
                                                <td>${{ number_format($order->store_credit, 2, '.', '') }}</td>
                                            </tr>

                                            <tr>
                                                <th colspan="6">Shipping Cost</th>
                                                <td>
                                                    <span id="shippingCost">${{ number_format($order->shipping_cost, 2, '.', '') }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th colspan="6">Total</th>
                                                <td><span id="total">${{ number_format($order->total, 2, '.', '') }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="form_row">
                                        <div class="form_inline">
                                            <h2 class="font_18p mb_15 mt_15 item_change_title">Note</h2>
                                            <textarea class="form_global mb-4 ml-2" name="order_note" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" data-modal="selectShippingModal">
        <div class="modal_overlay" data-modal-close="selectShippingModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_700p">
                <div class="modal_header display_table white_bg   pa15 pb_0">
                    <span class="modal_header_title">Select Shipping Address</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="selectShippingModal"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <table class="table  no-footer">
                        <tbody>
                            @foreach($shippingAddresses as $address)
                                <tr>
                                    <td>
                                        {{ $address->address }}, {{ $address->city }}, {{ ($address->state == null) ? $address->state_text : $address->state->name }},
                                        <br>
                                        {{ $address->country->name }} - {{ $address->zip }}
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="ly_btn btn_blue btnSelectAddress" data-index="{{ $loop->index }}" data-id="{{ $address->id }}">Select</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" data-modal="addEditShippingModal">
        <div class="modal_overlay" data-modal-close="addEditShippingModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_1080p">
                <div class="modal_header display_table white_bg   pa15 pb_0">
                    <span class="modal_header_title">Add New Shipping Address</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="addEditShippingModal"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <form id="modalForm">
                        <input type="hidden" id="editAddressId" name="id">
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="label_inline mb_5">Location</div>
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="custom_radio">
                                            <input class="location" type="radio" id="locationUS" name="location" value="US" checked="">
                                            <label for="locationUS">United States</label>
                                        </div>
                                        <div class="custom_radio">
                                            <input class=" location" type="radio" id="locationCA" name="location" value="CA">
                                            <label for="locationCA">Canada</label>
                                        </div>
                                        <div class="custom_radio">
                                            <input class="location" type="radio" id="locationInt" name="location" value="INT">
                                            <label for="locationInt">International</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-6">
                                <div class="form_row ">
                                    <div class="form_inline " id="form-group-address">
                                        <div class="label_inline required mb_5">Address</div>
                                        <input class="form_global" type="text" id="address" name="address">
                                    </div>
                                </div>
                            </div>
                            <div class="ly-6">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="label_inline  mb_5">Unit#</div>
                                        <input class="form_global" type="text" id="unit" name="unit">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-6">
                                <div class="form_row ">
                                    <div class="form_inline"  id="form-group-city">
                                        <div class="label_inline required mb_5">City</div>
                                        <input class="form_global" type="text" id="city" name="city">
                                    </div>
                                </div>
                            </div>
                            <div class="ly-6">
                                <div class="form_row ">
                                    <div class="form_inline "  id="form-group-state">
                                        <div class="label_inline required mb_5">State</div>
                                        <input class="form_global" type="text" id="state" name="state">
                                    </div>
                                    <div class="form_inline" id="form-group-state-select">
                                        <div class="label_inline mb_5">State</div>
                                        <div class="select">
                                            <select class="form_global" id="stateSelect" name="stateSelect">
                                                <option value="">Select State</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-6">
                                <div class="form_row ">
                                    <div class="form_inline" id="form-group-country">
                                        <div class="label_inline required mb_5">Country</div>
                                        <div class="select">
                                            <select class="form_global" id="country" name="country">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option data-code="{{ $country->code }}" value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ly-6">
                                <div class="form_row ">
                                    <div class="form_inline" id="form-group-zip">
                                        <div class="label_inline required mb_5">Zip Code </div>
                                        <input class="form_global" type="text" id="zipCode" name="zipCode">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-6">
                                <div class="form_row ">
                                    <div class="form_inline " id="form-group-phone">
                                        <div class="label_inline required mb_5">Phone</div>
                                        <input type="text" id="phone" name="phone" class="form_global">
                                    </div>
                                </div>
                            </div>
                            <div class="ly-6">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="label_inline  mb_5">Fax</div>
                                        <input type="text" class="form_global" id="fax" name="fax">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <button type="button" class="ly_btn btn_blue float_right" id="modalBtnAdd">Add</button>
                                        <button data-modal-close="addEditShippingModal" class="ly_btn btn_blue float_right mr_15 ">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/inputmask/js/inputmask.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/inputmask/js/jquery.inputmask.js') }}"></script>
    <script>
        $(function () {
            /*Add new shipping address text change*/
            if ( $(window).width() <= 480 ) {
                $('#btnAddShippingAddress').text("Add New Shipping");

                // Open credit card by default
                $('.credit-card-collapse').removeClass('collapse');
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': '{!! csrf_token() !!}'
                }
            });

            var shippingAddresses = <?php echo json_encode($shippingAddresses); ?>;
            var shippingMethods = <?php echo json_encode($shipping_methods); ?>;
            var usStates = <?php echo json_encode($usStates); ?>;
            var caStates = <?php echo json_encode($caStates); ?>;

            $('#expiry').inputmask();


            $('.shipping_method').change(function () {
                var index = parseInt($(".shipping_method:checked").data('index'));
                var storeCredit = parseFloat('{{ $order->store_credit }}');

                if (!isNaN(index)) {
                    var subTotal = parseFloat('{{ $order->subtotal }}');

                    var discount = parseFloat('{{ $order->discount }}');

                    var sm = shippingMethods[index];

                    if (sm.fee === null)
                        shipmentFee = 0;
                    else
                        shipmentFee = parseFloat(sm.fee);

                    $('#total').html('$' + ((subTotal - discount)+ shipmentFee - storeCredit).toFixed(2));
                    $('#shippingCost').html('$' + shipmentFee.toFixed(2));
                }
            });
            //promo code
            $('.btnApplyCoupon').click(function (e) {
                e.preventDefault();

                var orderId = $(this).data('order-id');
                var coupon = $('#coupon_'+orderId).val();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_apply_coupon') }}",
                    data: { id: orderId, coupon: coupon }
                }).done(function( data ) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            });

            $('.shipping_method').trigger('change');

            $('.btnPM').click(function () {
                var id = $(this).data('id');

                if($(this).attr('aria-expanded') == 'true') {
                    $('#paymentMethod').val('');
                } else {
                    $('#paymentMethod').val(id);
                }

            });

            $('#checkbox-agree').change(function () {
                if ($(this).is(':checked')) {
                    $('#btnSubmit').prop('disabled', false);
                } else {
                    $('#btnSubmit').prop('disabled', true);
                }
            });

            $('#checkbox-agree').trigger('change');

            // Shipping Address
            $('#btnChangeAddress').click(function (e) {
                e.preventDefault();
                var targeted_modal_class = 'selectShippingModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('.btnSelectAddress').click(function () {
                var id = $(this).data('id');
                var index = $(this).data('index');

                $('#address_id').val(id);

                var address = shippingAddresses[index];

                $('#address_text').html(address.address + ', ' + address.city + ', ');

                if (address.state == null) {
                    $('#address_text').append(address.state_text + ', ');
                } else {
                    $('#address_text').append(address.state.name + ', ');
                }

                $('#address_text').append('<br>' + address.country.name + ' - ' + address.zip);

                var targeted_modal_class = 'selectShippingModal';
                $('[data-modal="' + targeted_modal_class + '"]').removeClass('open_modal');
            });

            $('#btnAddShippingAddress').click(function (e) {
                e.preventDefault();
                var targeted_modal_class = 'addEditShippingModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('.location').change(function () {
                var location = $('.location:checked').val();

                if (location == 'CA' || location == 'US') {
                    if (location == 'US')
                        $('#country').val('1');
                    else
                        $('#country').val('2');

                    $('#country').prop('disabled', 'disabled');
                    $('#form-group-state-select').show();
                    $('#stateSelect').val('');
                    $('#form-group-state').hide();

                    $('#stateSelect').html('<option value="">Select State</option>');

                    if (location == 'US') {
                        $.each(usStates, function (index, value) {
                            $('#stateSelect').append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }

                    if (location == 'CA') {
                        $.each(caStates, function (index, value) {
                            $('#stateSelect').append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                } else {
                    $('#country').prop('disabled', false);
                    $('#form-group-state-select').hide();
                    $('#form-group-state').show();
                    $('#country').val('');
                }
            });

            $('.location').trigger('change');

            $('#country').change(function () {
                var countryId = $(this).val();

                if (countryId == 1) {
                    $("#locationUS").prop("checked", true);
                    $('.location').trigger('change');
                } else if (countryId == 2) {
                    $("#locationCA").prop("checked", true);
                    $('.location').trigger('change');
                }
            });

            $(document).on('click','#modalBtnAdd',function(){
                if (!shippingAddressValidate()) {
                    $('#country').prop('disabled', false);

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_add_shipping_address') }}",
                        data: $('#modalForm').serialize(),
                    }).done(function( data ) {
                        setAddressId(data.id);
                    });

                    $('#country').prop('disabled', true);
                }
            });

            function setAddressId(id) {
                var orders = $('#orders').val();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_checkout_address_select') }}",
                    data: { shippingId: id, id: orders },
                }).done(function( data ) {
                    window.location.reload(true);
                });
            }

            $('#addEditShippingModal').on('hide.bs.modal', function (event) {
                $("#locationUS").prop("checked", true);
                $('.location').trigger('change');

                $('#address').val('');
                $('#unit').val('');
                $('#city').val('');
                $('#stateSelect').val('');
                $('#state').val('');
                $('#zipCode').val('');
                $('#phone').val('');
                $('#fax').val('');

                clearModalForm();
            });

            function clearModalForm() {
                $('#form-group-address').removeClass('has-danger');
                $('#form-group-city').removeClass('has-danger');
                $('#form-group-state-select').removeClass('has-danger');
                $('#form-group-state').removeClass('has-danger');
                $('#form-group-country').removeClass('has-danger');
                $('#form-group-zip').removeClass('has-danger');
                $('#form-group-phone').removeClass('has-danger');
            }

            function shippingAddressValidate() {
                var error = false;
                var location = $('.location:checked').val();

                clearModalForm();

                if ($('#address').val() == '') {
                    $('#form-group-address').addClass('has-danger');
                    error = true;
                }

                if ($('#city').val() == '') {
                    $('#form-group-city').addClass('has-danger');
                    error = true;
                }

                if ((location == 'US' || location == 'CA') && $('#stateSelect').val() == '') {
                    $('#form-group-state-select').addClass('has-danger');
                    error = true;
                }

                if (location == 'INT' && $('#state').val() == '') {
                    $('#form-group-state').addClass('has-danger');
                    error = true;
                }

                if ($('#country').val() == '') {
                    $('#form-group-country').addClass('has-danger');
                    error = true;
                }

                if ($('#zipCode').val() == '') {
                    $('#form-group-zip').addClass('has-danger');
                    error = true;
                }

                if ($('#phone').val() == '') {
                    $('#form-group-phone').addClass('has-danger');
                    error = true;
                }

                return error;
            }
        });
    </script>
@stop
