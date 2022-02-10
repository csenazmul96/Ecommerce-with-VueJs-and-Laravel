<?php
    use App\Enumeration\Availability;
?>
@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <?php $subTotal = 0; ?>
    <div class="item_size_content">
        <div class="table-responsive ">
            @if($errors->has('error')) <p class="alert alert-danger">{{$errors->first('error')}}</p> @endif
            @if($errors->has('message')) <p class="alert alert-success">{{$errors->first('message')}}</p> @endif
            <form action="{{route('update_cart_admin')}}" method="post" class="cartListForm">
                @csrf
                <table class=" table header-border dataTable no-footer cart_table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cartItems as $item_index => $items)
                        @foreach($items as $item)
                        <tr>
                            <td>
                                @if (sizeof($items[0]->item->images) > 0)
                                    <a href="{{ route('admin_edit_item', $items[0]->item->id) }}" class="cartListItems float_left pr_30 font_12p">
                                        <img src="{{ Storage::url($items[0]->item->images[0]->thumbs_image_path) }}" alt="Product" width="50px" class="img-fluid">
                                    </a>
                                @else
                                    <a href="{{ route('admin_edit_item', $items[0]->item->id) }}" class="cartListItems float_left pr_30 font_12p">
                                        <img src="{{ asset('images/no-image.png') }}"alt="Product" width="50px">
                                    </a>
                                @endif
                                <a href="{{ route('admin_edit_item', $items[0]->item->id) }}" class="cartListItems float_left pr_30 font_12p">
                                    <h2 class="font_16p primary_color">{{$items[0]->item->name}}</h2>

                                    @if(!empty($item->color))
                                        <p class="font_12p pt_5">Color: {{ $item->color->name ?? ''}}</p>
                                    @endif

                                    <p class="font_12p pt_5">Style No: {{ $items[0]->item->style_no }}</p>

                                    @if(!empty($item->itemsize))
                                        <p class="font_12p pt_5">Size: {{ $item->itemsize->item_size ?? '' }}</p>
                                    @endif
                                </a>
                                <a class="font_12p ly_btn btn_danger link cart_small btnDelete" href="javascript:void(0)" data-id="{{$items[0]->item->id}}" data-size="{{ $item->itemsize->id ?? '' }}">Remove</a>
                            </td>
                            <td>${{ sprintf('%0.2f', $item->item->price) }}</td>
                            <td>
                                <input type="number" min="1" class="input_qty form-control" placeholder="1" value="{{ $item->quantity }}" data-price="{{ $item->item->price }}" data-id="{{ $item->id }}">
                            </td>
                            <td>
                                <span class="total_amount">${{ sprintf('%0.2f', $item->item->price * $item->quantity) }}</span>
                                <?php $subTotal += $item->item->price * $item->quantity; ?>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="2" class="pl_0">
                            <a href="{{route('admin_new_order_create')}}" class="ly_btn btn_blue btn_common_type2 float_left update_btn">Continue Shopping</a>
                        </td>
                        <td colspan="2" class="pr_0">
                            <a class="ly_btn btn_blue btn_common_type2 float_right update_btn" id="btnUpdate">Update Shopping Bag</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="ly-row">
            <div class="ly-8">

            </div>
            <div class="ly-4">
                <ul class="cart_total">
                    <li><span>Subtotal</span> <span class="amount sub_total"></span></li>
                    <?php if ( $user->storeCredit() > 0 ) : ?>
                    <li>
                        <span>Store Credits - ${{ number_format($user->storeCredit(), 2, '.', '') }}:</span>
                        <input type="text" class="store_credit" placeholder="$" style="width: 75px; text-align: right">
                    </li>
                    <?php endif; ?>
                    <li>
                        <span>Total</span>
                        <span class="total"></span>
                    </li>
                </ul>
                <a class="ly_btn btn_blue btn_common_type2 btnCheckout float_right mt_15 ">Checkout</a>
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
                    'X-CSRF-Token': '{!! csrf_token() !!}'
                }
            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('.input_qty').keyup(function () {
                var index = $('.input_qty').index($(this));
                var price = $(this).data('price');
                var i = 0;
                var val = $(this).val();
                if (isInt(val)) {
                    i = parseInt(val);

                    if (i < 0)
                        i = 0;
                }
                $('.total_qty:eq('+index+')').html(i);
                $('.total_amount:eq('+index+')').html('$' + (i * price).toFixed(2));
                calculate();
            });

            $('#btnUpdate').click(function () {
                var ids = [];
                var qty = [];

                var valid = true;
                $('.input_qty').each(function () {
                    var i = 0;
                    var val = $(this).val();

                    if (isInt(val)) {
                        i = parseInt(val);

                        if (i < 0)
                            return valid = false;
                    } else {
                        return valid = false;
                    }

                    ids.push($(this).data('id'));
                    qty.push(i);
                });

                if (!valid) {
                    alert('Invalid Quantity.');
                    return;
                }

                $.ajax({
                    method: "POST",
                    url: "{{ route('update_cart_admin') }}",
                    data: { ids: ids, qty: qty },
                    headers: {
                        'X-CSRF-Token': '{!! csrf_token() !!}'
                    }
                }).done(function( data ) {
                    if (data.success) {
                        window.location.replace("{{ route('admin_update_cart_success') }}");
                    } else {
                        alert(data.message);
                    }
                });
            });

            $('.btnDelete').click(function () {
                var itemId = $(this).data('id');
                var sizeId = $(this).data('size');
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_delete_cart') }}",
                    data: { itemId: itemId, sizeId: sizeId}
                }).done(function( data ) {
                    location.reload();
                });
            });


            $('.btnCheckout').click(function (e) {
                e.preventDefault();
                $.ajax({
                    method: "POST",
                    url: "{{ route('create_admin_checkout') }}"
                }).done(function( data ) {
                    if (data.success)
                        window.location.replace("{{ route('admin_show_checkout') }}" + "?id=" + data.message);
                    else
                        alert(data.message);
                });
            });

            function calculate() {
                var subTotal = 0;

                $('.input_qty').each(function () {
                    var price = $(this).data('price');
                    var i = 0;
                    var val = $(this).val();

                    if (isInt(val)) {
                        i = parseInt(val);

                        if (i < 0)
                            i = 0;
                    }

                    subTotal += i * price;
                });

                var store_credit = parseFloat($('.store_credit').val());

                if(isNaN(store_credit))
                    store_credit = 0;


                var total = subTotal-store_credit;

                if (total < 0)
                    total = 0;


                $('.sub_total').html('$' + subTotal.toFixed(2));
                $('.total').html('$' + total.toFixed(2));
            }

            calculate();

            function isInt(value) {
                return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
            }

            $('.store_credit').keyup(function () {
                calculate();
            });
        });
    </script>
@stop
