<?php use App\Enumeration\OrderStatus; ?>

@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <style>
        .table .table {
            background-color: white;
        }
        .is-invalid{
            border-color: red;
        }
        .panel-title{
            font-size: 12px;
        }

        .order_details_cardinfo_ul {
            display: flex;
        }

        .order_details_shipping_method li {
            width: 50%;
            float: left;
        }

        .size_ul {
            width: 400px;
        }

        .size_ul ul {
            width: max-content;
            margin: 0px auto;
            display: flex;
        }

        .size_ul ul li {
            width: 35px;
            float: left;
            margin-right: 5px;
            text-align: center;
        }

        .order_details_item_table tbody tr td {
            border-top: 0px !important;
        }

        .order_details_summation tr td {
            border-top: 0 !important;
            border-bottom: 0px !important;
            width: 50%;
            border-right: 1px solid #efefef;
        }

        .order_details_summation tr {
            border-bottom: 1px solid #efefef;
            width: 100%;
        }

        .text_center{
            text-align: center !important;
        }
    </style>
@stop

@section('content')

    <div class="order_details_content">

        <form action="{{ route('admin_order_details_post', ['order' => $order->id]) }}" method="post">
            @csrf
            <div class="ly-row mb_15">
                <div class="ly-7">
                    <ul class="order_details_shipping_method float_left">
                        @if($order->user_id == 0)
                            <h3 class="text-danger float-left">Guest Order</h3>
                        @endif
                    </ul>
                </div>
                <div class="ly-5">
                    <ul class="order_details_shipping_method float_right">
                        @if($order->user_id != 0)
                            <a class="ly_btn btn_blue" role="button" data-modal-open="message-modal">Send Message</a>
                        @endif
                        <a class="ly_btn btn_blue" role="button" data-modal-open="print-modal">Print</a>
                        @if($order->user_id != 0)
                            <a class="ly_btn btn_blue" data-modal-open="modal-store-credit" id="modal-store-credit-button">Store Credit</a>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="ly_card order_information_card">
                <div class="ly_card_heading">
                    <h1>Order Information </h1>
                </div>
                <div class="ly_card_body">
                    <div class="ly-row">
                        <div class="ly-4">
                            <table class="table table_bordered border">
                                <tbody>
                                <tr>
                                    <td><strong>Order No.</strong></td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Date</strong></td>
                                    <td>{{ date('F d, Y h:i:s a', strtotime($order->created_at)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong> Status</strong></td>
                                    <td>
                                        <div class="ly-row">
                                            <div class="ly-8">
                                                <div class="select">
                                                    <select class="form_global" name="order_status" id="order_status">
                                                        <option value="{{ OrderStatus::$NEW_ORDER }}" {{ $order->status == OrderStatus::$NEW_ORDER ? 'selected' : '' }}>New Orders</option>
                                                        <option value="{{ OrderStatus::$CONFIRM_ORDER }}" {{ $order->status == OrderStatus::$CONFIRM_ORDER ? 'selected' : '' }}>Confirmed Orders</option>
                                                        <option value="{{ OrderStatus::$PARTIALLY_SHIPPED_ORDER }}" {{ $order->status == OrderStatus::$PARTIALLY_SHIPPED_ORDER ? 'selected' : '' }}>Partially Shipped Orders</option>
                                                        <option value="{{ OrderStatus::$FULLY_SHIPPED_ORDER }}" {{ $order->status == OrderStatus::$FULLY_SHIPPED_ORDER ? 'selected' : '' }}>Fully Shipped Orders</option>
                                                        <option value="{{ OrderStatus::$BACK_ORDER }}" {{ $order->status == OrderStatus::$BACK_ORDER ? 'selected' : '' }}>Back Ordered</option>
                                                        <option value="{{ OrderStatus::$CANCEL_BY_BUYER }}" {{ $order->status == OrderStatus::$CANCEL_BY_BUYER ? 'selected' : '' }}>Cancelled by Buyer</option>
                                                        <option value="{{ OrderStatus::$CANCEL_BY_VENDOR }}" {{ $order->status == OrderStatus::$CANCEL_BY_VENDOR ? 'selected' : '' }}>Cancelled by Vendor</option>
                                                        <option value="{{ OrderStatus::$CANCEL_BY_AGREEMENT }}" {{ $order->status == OrderStatus::$CANCEL_BY_AGREEMENT ? 'selected' : '' }}>Cancelled by Agreement</option>
                                                        <option value="{{ OrderStatus::$RETURNED }}" {{ $order->status == OrderStatus::$RETURNED ? 'selected' : '' }}>Returned</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="ly-4">
                                                @if ($order->status == OrderStatus::$BACK_ORDER)
                                                    @if ($order->rejected == 0)
                                                        <span class="orange_bg">Pending</span>
                                                    @elseif ($order->rejected == 1)
                                                        <span class="text-danger">Rejected</span>
                                                    @else
                                                        <span class="text-success">Approved</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Number</strong></td>
                                    <td>
                                        <input type="text" name="invoice_number" class="form_global" value="{{ $order->invoice_number }}">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="ly-4">
                            <table class="table table_bordered border d_none" id="card-info-table">
                                <tbody>
                                <tr>
                                    <td><strong>Full Name</strong></td>
                                    <td colspan="3"><span id="card-info-fullName"></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Card Number</strong></td>
                                    <td colspan="3"><span id="card-info-number"></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Expire</strong></td>
                                    <td><span id="card-info-expire"></span></td>
                                    <td><strong>CVC</strong></td>
                                    <td><span id="card-info-cvc"></span></td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="row form-group">
                                <div class="col-12">
                                    @if(!empty($order->paypal_payer_id))
                                        @if($order->aStatus === 'approved')
                                            <span style="color: green;">Paypal Payment success</span>
                                            <br>
                                            <br>
                                            <span class="text-success">Transaction No: {{ $order->paypal_payment_id }}</span>
                                        @else
                                            <button class="ly_btn btn_blue" id="paypalRecaptcha" data-id="{{ $order->id }}">Paypal Recaptcha</button>
                                        @endif
                                    @else
                                        <button class="ly_btn btn_blue" id="btnShowCard">Show Card Info</button>
                                        <?php
                                            $authorizeInfo = $order->authorize_info;
                                        ?>
                                        @if(!empty($authorizeInfo))
                                            @if($authorizeInfo['status'] == "Success" && !empty($order->payment_id))
                                                <?php
                                                    $transactionTime = isset($authorizeInfo['authorized_time']) ? $authorizeInfo['authorized_time'] : null ;
                                                    $timeDifferenceInHours = ( time() - strtotime($transactionTime) ) / 3600;
                                                ?>
                                                <button class="ly_btn btn_blue" id="refundPayment" data-confirm="Are you sure want to Refund Payment?">Refund Payment</button>
                                            @elseif($authorizeInfo['status'] == "Refund")
                                                <button class="btn btn-secondary" disabled style="color: #fff;background: blue;">Authorize and Capture</button>
                                            @elseif(($authorizeInfo['status'] == "Failed" ))
                                                <button class="ly_btn btn_blue" id="stripeAuth" data-id="{{ $order->id }}" data-confirm="Are you sure want to authorize?">Authorize & Capture</button>
                                            @else
                                                <button class="ly_btn btn_blue" disabled style="color: #fff;background: blue;">Authorize & Capture</button>
                                            @endif
                                        @endif

                                        @if(empty($authorizeInfo))
                                            <button class="ly_btn btn_blue" id="stripeAuth" data-id="{{ $order->id }}" data-confirm="Are you sure want to authorize?">Authorize and Capture</button>
                                        @endif
                                        <button type="button" class="ly_btn btn_blue d_none" id="btnMask">Mask</button>
                                        <button type="button" class="ly_btn btn_blue d_none" id="btnHideCard">Hide Card Info</button>
                                        <button type="button" class="ly_btn btn_blue d_none" id="btnEditCardInfo">Edit Card</button>
                                    @endif
                                </div>
                            </div>
                            @if(isset($order->authorize_info['status']))
                                @if($order->authorize_info['status'] == "Failed")
                                    <p style="font-size: 16px;padding-top: 10px;color:red">{{ $order->authorize_info['status'] }}</p>
                                @endif
                                @if($order->authorize_info['status'] == "Success")
                                    <p style="font-size: 16px;padding-top: 10px;color:green">{{ $order->authorize_info['status'] }}</p>
                                @endif
                            @endif

                            @if(isset($order->authorize_info['message']))
                                @if($order->authorize_info['status'] == "Failed")
                                    <p style="font-size: 16px;padding-top: 10px; color:red">{{ $order->authorize_info['message'] }}</p>
                                @endif
                                @if($order->authorize_info['status'] == "Success")
                                    <p style="font-size: 16px;padding-top: 10px; color:green">{{ $order->authorize_info['message'] }}</p>
                                @endif
                            @endif
                        </div>
                        <div class="ly-4">
                            <table class="table table_bordered border">
                                <tbody>
                                <tr>
                                    <td><strong>Created At</strong></td>
                                    <td>{{ date('F d, Y h:i:s a', strtotime($order->created_at)) }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Modified At</strong></td>
                                    <td>{{ date('F d, Y h:i:s a', strtotime($order->updated_at)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong># of Orders</strong></td>
                                    <td>{{ $countText }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Can Call</strong></td>
                                    <td>{{ $order->can_call == 1 ? 'Yes' : 'No' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ly_card order_information_card">
                <div class="ly_card_heading">
                    <h1>Buyer Information </h1>
                </div>

                <div class="ly_card_body">
                    <div class="ly-row">
                        <div class="ly-6">
                            <table class="table table_bordered border">
                                <tbody>
                                <tr>
                                    <td colspan="2"><b>Billing Address</b></td>
                                </tr>

                                <tr>
                                    <td><strong>Name</strong></td>
                                    <td>{{ $order->b_user_name }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Address</strong></td>
                                    <td>
                                        {{ $order->billing_address }},
                                        @if ($order->billing_unit && $order->billing_unit != '')
                                            #{{ $order->billing_unit }},
                                        @endif
                                        {{ $order->billing_city }}, <br>
                                        {{ $order->billing_state }}, {{ $order->billing_location }} - {{ $order->billing_zip }},
                                        {{ $order->billing_country }}
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td>{{ $order->billing_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ $order->email }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="ly-6">
                            <table class="table table_bordered border">
                                <tbody>
                                <tr>
                                    <td colspan="2"><b>Shipping Address</b></td>
                                </tr>

                                <tr>
                                    <td><strong>Name</strong></td>
                                    <td>{{ $order->s_user_name }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Address</strong></td>
                                    <td>
                                        {{ $order->shipping_address }},

                                        @if ($order->shipping_unit && $order->shipping_unit != '')
                                            #{{ $order->shipping_unit }},
                                        @endif
                                        {{ $order->shipping_city }}, <br>
                                        {{ $order->shipping_state }}, {{ $order->shipping_location }} - {{ $order->shipping_zip }},
                                        {{ $order->shipping_country }}
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td>{{ $order->shipping_phone }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ $order->email }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ly_card order_information_card">
                <div class="ly_card_heading">
                    <h1>Shipping Method </h1>
                </div>

                <div class="ly_card_body">
                    <div class="ly-row">
                        <div class="ly-6">
                            <ul class="order_details_shipping_method">
                                <li><strong>Shipping Method</strong></li>
                                <li>
                                    <div class="select">
                                        <select class="form_global{{ $errors->has('shipping_method_id') ? ' is-invalid' : '' }}" name="shipping_method_id">
                                            <option value="">Select Shipping Method</option>
                                            @foreach($shippingMethods as $method)
                                                <option value="{{ $method->id }}" {{ $method->id == $order->shipping_method_id ? 'selected' : '' }}>
                                                    @if(!empty($method->courier->name))
                                                        {{ $method->courier->name }} |
                                                    @endif
                                                    {{ $method->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="ly-6">
                            <ul class="order_details_shipping_method">
                                <li><strong>Tracking Number</strong></li>
                                <li>
                                    <input type="text" class="form_global{{ $errors->has('tracking_number') ? ' is-invalid' : '' }}" name="tracking_number"
                                           value="{{ empty(old('tracking_number')) ? ($errors->has('tracking_number') ? '' : $order->tracking_number) : old('tracking_number') }}">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ly_card order_information_card">
                <div class="ly_card_heading">
                    <h1>Items </h1>
                </div>
                <div class="ly_card_body">
                    <div class="ly-row mb_15">
                        <div class="ly-12">
                            <ul class="text_right">
                                <button type="button" class="ly_btn btn_blue"  id="btnAddProduct">Add Product</button>
                                <button type="button" class="ly_btn btn_grey" id="btnBackOrder">Back Order</button>
                                <button type="button" class="ly_btn btn_blue" id="btnOutOfStock">Out Of Stock</button>
                                <button type="button" class="ly_btn btn_danger" id="btnDeleteItem">Delete</button>
                            </ul>
                        </div>
                    </div>
                    <div class="ly-row">
                        <div class="ly-12">
                            <table class="table table_bordered order_details_item_table">
                                <thead>
                                <tr>
                                    <th colspan="1">Image</th>
                                    <th class="text_center" colspan="1">Style No.</th>
                                    <th colspan="1" class=" text_center">Color</th>
                                    <th class="size_ul text_center">Size</th>
                                    <th colspan="1" class=" text_center">Qty</th>
                                    <th colspan="1" class=" text_center">Unit Price</th>
                                    <th colspan="1" class=" text_center">Amount</th>
                                    <th colspan="1" class=" text_center">Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($allItems as $item_id => $items)
                                    @foreach($items as $item)
                                        @if($loop->first)
                                            <tr>
                                                <td rowspan="{{ count($items) }}">
                                                    @if (sizeof($items[0]->item->images) > 0)
                                                        <img class="width_50p" src="{{ Storage::url($items[0]->item->images[0]->thumbs_image_path) }}" alt="Product">
                                                    @else
                                                        <img class="width_50p" src="{{ asset('images/no-image.png') }}" alt="Product">
                                                    @endif
                                                </td>

                                                <td class="text_center" rowspan="{{ count($items) }}">
                                                    {{ $items[0]->style_no }}
                                                    <br>
                                                    @if($items[0]->item->available_on != null)
                                                        Available On: {{ $items[0]->item->available_on }}
                                                    @endif
                                                </td>

                                                <td class="text_center" colspan="1">@if(!empty($item->color_name)) {{ $item->color_name }} @else - @endif</td>
                                                <td class="text_center" colspan="1">@if(!empty($item->size_name)) {{ $item->size_name }} @else - @endif</td>

                                                <?php
                                                $sizes = explode("-", $item->size);
                                                ?>

                                                @for($i=0; $i < sizeof($sizes); $i++)
                                                    <td class="text_center">
                                                        <input class="input_size input_size_{{ $item->id }} {{ $errors->has('size_'.$item->id.'.'.$i) ? ' is-invalid' : '' }}"
                                                               data-id="{{ $item->id }}"
                                                               type="text" name="size_{{ $item->id }}"
                                                               value="{{$item->total_qty}}" style="width: 50px; text-align: center">
                                                    </td>
                                                @endfor

                                                <td class="text_center">
                                                    $ <input class="input_unit_price {{ $errors->has('unit_price_'.$item->id) ? ' is-invalid' : '' }}"
                                                             id="input_unit_price_{{ $item->id }}"
                                                             type="text" data-id="{{ $item->id }}"
                                                             name="unit_price_{{ $item->id }}"
                                                             value="{{ empty(old('unit_price_'.$item->id)) ? ($errors->has('unit_price_'.$item->id) ? '' : sprintf('%0.2f', $item->per_unit_price)) : old('unit_price_'.$item->id) }}" style="width: 50px; text-align: center">
                                                </td>

                                                <td class="text_center">
                                                    <span id="amount_{{ $item->id }}">${{ sprintf('%0.2f', $item->total_qty * $item->per_unit_price) }}</span>
                                                    <input type="hidden" class="input_amount" id="input_amount_{{ $item->id }}" value="{{ sprintf('%0.2f', $item->total_qty * $item->per_unit_price) }}">

                                                </td>

                                                <td class="text_right width_60p">
                                                    <div class="form-check custom_checkbox">
                                                        <input class="form-check-input item_checkbox" type="checkbox" id="checkbox_{{ $item->id }}" name="checkbox_{{ $item->id }}" value="{{ $item->id }}">
                                                        <label class="form-check-label" for="checkbox_{{ $item->id }}">&nbsp;</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="text_center" colspan="1">@if(!empty($item->color_name)) {{ $item->color_name }} @else - @endif</td>
                                                <td class="text_center" colspan="1">@if(!empty($item->size_name)) {{ $item->size_name }} @else - @endif</td>

                                                <?php
                                                $sizes = explode("-", $item->size);
                                                ?>

                                                @for($i=0; $i < sizeof($sizes); $i++)
                                                    <td class="text_center">
                                                        <input class="input_size input_size_{{ $item->id }} {{ $errors->has('size_'.$item->id.'.'.$i) ? ' is-invalid' : '' }}"
                                                               data-id="{{ $item->id }}"
                                                               type="text" name="size_{{ $item->id }}"
                                                               value="{{$item->total_qty}}" style="width: 50px; text-align: center">
                                                    </td>
                                                @endfor

                                                <td class="text_center">
                                                    $ <input class="input_unit_price {{ $errors->has('unit_price_'.$item->id) ? ' is-invalid' : '' }}"
                                                             id="input_unit_price_{{ $item->id }}"
                                                             type="text" data-id="{{ $item->id }}"
                                                             name="unit_price_{{ $item->id }}"
                                                             value="{{ empty(old('unit_price_'.$item->id)) ? ($errors->has('unit_price_'.$item->id) ? '' : sprintf('%0.2f', $item->per_unit_price)) : old('unit_price_'.$item->id) }}" style="width: 50px; text-align: center">
                                                </td>

                                                <td class="text_center">
                                                    <span id="amount_{{ $item->id }}">${{ sprintf('%0.2f', $item->total_qty * $item->per_unit_price) }}</span>
                                                    <input type="hidden" class="input_amount" id="input_amount_{{ $item->id }}" value="{{ sprintf('%0.2f', $item->total_qty * $item->per_unit_price) }}">

                                                </td>

                                                <td class="text_right width_60p">
                                                    <div class="form-check custom_checkbox">
                                                        <input class="form-check-input item_checkbox" type="checkbox" id="checkbox_{{ $item->id }}" name="checkbox_{{ $item->id }}" value="{{ $item->id }}">
                                                        <label class="form-check-label" for="checkbox_{{ $item->id }}">&nbsp;</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="ly-row">
                        <div class="ly-4">
                            <table class="table table_bordered">
                                <tbody>
                                <tr>
                                    <td><strong> TRANSACTION DETAIL</strong></td>
                                </tr>
                                    <tr>
                                        @if($order->payment_id != 'Paypal')
                                            <td>
                                                @if(@$order->authorize_info['error_message'])
                                                    <p style="font-size: 16px;padding-top: 1px;margin-bottom: 1px;">{{@$order->authorize_info['error_message']}}</p>
                                                @endif
                                                @if(@$order->authorize_info['message'])
                                                    <p style="font-size: 16px;padding-top: 1px;margin-bottom: 1px;">{{@$order->authorize_info['message']}}</p>
                                                @endif
                                                @if(@$order->authorize_info['transaction_response_code'] && @$order->authorize_info['transaction_response_code'] == 1)
                                                    <p style="font-size: 16px;padding-top: 1px;color: green">{{@$order->authorize_info['desc']}}.</p>
                                                @endif
                                                @if(@$order->authorize_info['transaction_response_code'] && @$order->authorize_info['transaction_response_code'] == 2)
                                                    <p style="font-size: 16px;padding-top: 1px;color: red">{{@$order->authorize_info['desc']}}</p>
                                                @endif
                                                @if(@$order->authorize_info['captured'] == true)
                                                    <p style="font-size: 16px;padding-top: 5px;color: green">Order Capture</p>
                                                @endif
                                            </td>
                                        @else
                                            <td>
                                                <p style="font-size: 16px;padding-top: 5px;color: green">Order Captured by Paypal</p>
                                            </td>
                                        @endif
                                    </tr>

                                    @if(!empty($order->payment_id) && $order->aStatus != 'Refund')
                                        <tr>
                                            <td>
                                                <h3 style="color: seagreen">{{$order->payment_id}}</h3>
                                            </td>
                                        </tr>
                                    @endif


                                </tbody>
                            </table>
                        </div>
                        <div class="ly-4">
                            <table class="table table_bordered">
                                <tbody>
                                <tr>
                                    <td> <strong> Buyer Note</strong></td>
                                </tr>
                                <tr>
                                    @if($order->note)
                                        <td>{{ $order->note }}</td>
                                    @else
                                        <td> <i>No note</i> </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td><strong>Admin Note</strong></td>
                                </tr>
                                <tr>
                                    @if(!empty($order->admin_note))
                                        <td>
                                            <textarea name="admin_note" value="{{ $order->admin_note }}" class="admin_note form_global" style="width:100%" cols="30" rows="10">{{$order->admin_note}}</textarea>
                                        </td>
                                    @else
                                        <td>
                                            <textarea name="admin_note" class="admin_note form_global" style="width:100%" cols="30" rows="10"></textarea>
                                        </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="ly-4">
                            <div class="col-4">
                                <table class="table table-bordered">
                                </table>
                            </div>

                            <table class="table table_bordered border order_details_summation">
                                <tbody>
                                <tr>
                                    <td><strong>Subtotal ($)</strong></td>
                                    <td>
                                        <span id="span_subtotal">{{ sprintf('%0.2f', $order->subtotal) }}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Discount ($)</strong></td>
                                    <td>
                                        <input name="order_discount" id="order_discount" type="text"
                                               class="form-control{{ $errors->has('order_discount') ? ' is-invalid' : '' }}"
                                               value="{{ empty(old('order_discount')) ? ($errors->has('order_discount') ? '' : $order->discount) : old('order_discount') }}"
                                               placeholder="$">
                                    </td>
                                </tr>

                                @if($order->reward_percantage)
                                    <tr>
                                        <td><strong>Point Discount ($)</strong></td>
                                        <td>
                                            <input name="reward_percantage" id="reward_percantage" type="text"
                                                   class="form-control{{ $errors->has('reward_percantage') ? ' is-invalid' : '' }}"
                                                   value="{{ empty(old('reward_percantage')) ? ($errors->has('reward_percantage') ? '' : $order->reward_percantage) : old('reward_percantage') }}"
                                                   placeholder="$" readonly="">
                                            <br>
                                            @if(!empty($rewardData))
                                                [ {{ $rewardData->from_price_1 }} points - {{ $rewardData->percentage_discount_1 }} % discount ]
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($order->reward_fixed)
                                    <tr>
                                        <td><strong>Point Discount ($)</strong></td>
                                        <td>
                                            <input name="reward_fixed" id="reward_fixed" type="text"
                                                   class="form-control{{ $errors->has('reward_fixed') ? ' is-invalid' : '' }}"
                                                   value="{{ empty(old('reward_fixed')) ? ($errors->has('reward_fixed') ? '' : $order->reward_fixed) : old('reward_fixed') }}"
                                                   placeholder="$" readonly="">
                                            <br>
                                            @if(!empty($rewardData))
                                                [ {{ $rewardData->from_price_1 }} points - ${{ $rewardData->unit_price_discount_1 }} discount ]
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($order->dollar_point_discount)
                                    <tr>
                                        <td><strong>Point Discount ($)</strong></td>
                                        <td>
                                            <input name="dollar_point_discount" id="dollar_point_discount" type="text"
                                                   class="form-control{{ $errors->has('dollar_point_discount') ? ' is-invalid' : '' }}"
                                                   value="{{ empty(old('dollar_point_discount')) ? ($errors->has('dollar_point_discount') ? '' : $order->dollar_point_discount) : old('dollar_point_discount') }}"
                                                   placeholder="$" readonly="">
                                            <br><br>
                                            [ {{ $order->used_dollar_point }} points ]
                                        </td>
                                    </tr>
                                @endif
                                @if($order->promotion_details)
                                    <tr>
                                        <td><strong>Promotions</strong></td>
                                        <td>
                                            <span id="promotion-text">{{ $order->promotion_details }}</span>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td><strong>Shipping Charge ($)</strong></td>
                                    <td>
                                        <input name="input_shipping_cost" id="input_shipping_cost" type="text"
                                               class="form-control{{ $errors->has('input_shipping_cost') ? ' is-invalid' : '' }}"
                                               value="{{ empty(old('input_shipping_cost')) ? ($errors->has('input_shipping_cost') ? '' : $order->shipping_cost) : old('input_shipping_cost') }}"
                                               placeholder="$">
                                        <br>
                                        <?php if($order->free_shipping == 1) { echo '<span>[ Free By Promotion ]</span>'; } ?>
                                        @if(!empty($rewardData))
                                            <?php if($rewardData->free_shipping_1 == 1) { echo '<span>[ Free By Point ]</span>'; } ?>
                                        @endif
                                        <input type="hidden" id="free_shipping" name="free_shipping" value="{{ $order->free_shipping }}">
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong><span id="span_total">${{ sprintf('%0.2f', $order->total) }}</span></strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="ly-row">
                        <div class="ly-8"></div>
                        <div class="ly-4">
                            <ul class="order_details_shipping_method text_right">
                                <li>
                                    <div class="form_row">
                                        {{-- <div class="custom_checkbox">
                                            <input type="checkbox" id="notify_user" name="notify_user" class="mr_20" value="1">
                                            <label class=" " for="notify_user">Buyer Notify</label>
                                        </div> --}}
                                    </div>
                                </li>
                                <li>
                                    <button class="ly_btn btn_blue btn-medium float_right">Save</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" data-modal="password-modal">
        <div class="modal_overlay" data-modal-close="password-modal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_700p">
                <div class="modal_header display_table white_bg   pa15 pb_0">
                    <span class="modal_header_title">Enter Password </span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="password-modal"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <div class="form_row">
                        <input type="password" class="form_global" id="input-password">
                        <button type="button" class="ly_btn btn_blue float_right mt_15" id="btnCheckPassword">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" data-modal="message-modal">
        <div class="modal_overlay" data-modal-close="message-modal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_700p">
                <div class="modal_header display_table white_bg   pa15 pb_0">
                    <span class="modal_header_title">Send Message To Buyer</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="message-modal"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <form id="message-form" action="{{ route('send_message_buyer') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $order->user_id}}">
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row">
                                    <div class="label_inline fw_500 width_150p">
                                        From
                                    </div>
                                    <div class="form_inline">
                                        <p id="sender">Shop Hologram</p>
                                        <input type="hidden" name="sender" value="Shop Hologram">
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline fw_500 width_150p">
                                        To
                                    </div>
                                    <div class="form_inline">
                                        <label id="recipient">{{ $order->name }}&nbsp; ({{ $order->email }})</label>
                                        <input type="hidden" name="recipient" value="{{ $order->name }}&nbsp; ({{ $order->email }})">
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline required fw_500 width_150p">
                                        Title
                                    </div>
                                    <div class="form_inline">
                                        <div class="select mb_5">
                                            <select class="form_global" name="topics" required>
                                                <option selected="" disabled>Select Topics</option>
                                                <option value="Product">Product</option>
                                                <option value="Order Status">Order Status</option>
                                                <option value="Payment">Payment</option>
                                                <option value="Shipment">Shipment</option>
                                                <option value="Return Policy">Return Policy</option>
                                                <option value="Other">Other</option>
                                                <option value="General">General</option>
                                                <option value="Photo-studio">Photo-studio</option>
                                            </select>
                                        </div>
                                        <input name="order" value="Regarding Your Order ({{ $order->order_number }})" class="form_global">
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline fw_500 width_150p">
                                        Message
                                    </div>
                                    <div class="form_inline">
                                        <textarea name="message" class="form_global" cols="30" rows="10" placeholder="Write your message here ...." required></textarea>
                                        <label for="">File type allowed .jpg, .gif, .png, .pdf, .xls, .xlsx</label>
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline mb_5 fw_500 width_150p"> </div>
                                    <div class="form_inline">
                                        <span> <i class="fa fa-paperclip" aria-hidden="true"></i>  File 1</span>
                                        <input type="file" id="attachment1" name="attachment1">
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline mb_5 fw_500 width_150p"> </div>
                                    <div class="form_inline">
                                        <span> <i class="fa fa-paperclip" aria-hidden="true"></i>  File 2</span>
                                        <input type="file" id="attachment2" name="attachment2">
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline mb_5 fw_500 width_150p"> </div>
                                    <div class="form_inline">
                                        <span> <i class="fa fa-paperclip" aria-hidden="true"></i>  File 3</span>
                                        <input type="file" id="attachment3" name="attachment3">
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline mb_5 fw_500 width_150p"></div>
                                    <div class="form_inline">
                                        <button type="button" class="ly_btn btn_blue float_right" data-modal-close="message-modal">Close</button>
                                        <button type="submit" id="btnMessageSend" class="ly_btn btn_blue float_right mr_8">Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" data-modal="print-modal">
        <div class="modal_overlay" data-modal-close="print-modal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_380p">
                <div class="modal_header display_table white_bg   pa15 pb_0">
                    <span class="modal_header_title">Print Invoice</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="print-modal"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <a class="ly_btn btn_blue" href="{{ route('admin_print_pdf', ['order' => $order->id]) }}" target="_blank">Print with Images</a><br><br>
                    <a class="ly_btn btn_blue" href="{{ route('admin_print_pdf_without_image', ['order' => $order->id]) }}" target="_blank">Print without Images</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" data-modal="modal-store-credit">
        <div class="modal_overlay" data-modal-close="modal-store-credit"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_380p">
                <div class="modal_header display_table white_bg   pa15 pb_0">
                    <span class="modal_header_title">Store Credit</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="modal-store-credit"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <form id="form-store-credit">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="label_inline required mb_5">Reason</div>
                                        <input type="text" class="form_global" id="sc-reason" aria-describedby="sc-reason" placeholder="Enter Reason" name="reason">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="label_inline required mb_5">Amount</div>
                                        <input type="text" class="form_global" id="sc-amount" placeholder="Amount" name="amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <div class="label_inline required mb_5">Re-Amount</div>
                                        <input type="text" class="form_global" id="sc-re-amount" placeholder="Re-Amount" name="re_amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row">
                                    <div class="form_inline">
                                        <button type="button" class="ly_btn btn_blue" id="btnAddStoreCredit">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" data-modal="item-modal">
        <div class="modal_overlay" data-modal-close="item-modal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_1080p">
                <div class="modal_header display_table white_bg   pa15 pb_0">
                    <span class="modal_header_title">Select Item</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="item-modal"></span>
                    </div>
                </div>
                <div class="modal_content pl_15 pr_15">
                    <div class="ly-row">
                        <div class="ly-12">
                            <div class="ly-row">
                                <div class="ly-4">
                                    <div class="form_row">
                                        <div class="form_inline">
                                            <input type="text" class="form_global" placeholder="Search" id="modal-search">
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-4">
                                    <div class="form_row">
                                        <div class="form_inline">
                                            <div class="select">
                                                <select class="form_global" id="modal-category">
                                                    <option value="">All Category</option>

                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-4">
                                    <div class="form_row">
                                        <div class="form_inline">
                                            <button type="button" class="ly_btn btn_blue" type="submit" id="modal-btn-search">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ly-row modal-items">
                        @foreach($products as $item)
                            <div class="ly-2 modal-item" data-id="{{ $item->id }}">
                                <div class="item_list">
                                    <div class="item_list_text">
                                        @if (sizeof($item->images) > 0)
                                            <img src="{{ Storage::url($item->images[0]->thumbs_image_path) }}" alt="{{ $item->style_no }}">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $item->style_no }}">
                                        @endif
                                    </div>
                                    <span class="item_list_desc">
                                    <h2 class="style_no">
                                        <a class="openColorModal link" data-toggle="modal" data-target="#colorModal" data-id="{{$item->id}}">{{ $item->style_no }}</a>
                                    </h2>
                                </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal-pagination">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" data-modal="colorModal">
        <div class="modal_overlay" data-modal-close="colorModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Add Color and Quantity</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="colorModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="form_row color_row">
                                        <div class="label_inline required width_150p">
                                            Color
                                        </div>
                                        <div class="form_inline">
                                            <div class="select" id="colorSelect">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form_row size_row">
                                        <div class="label_inline required width_150p">
                                            Size
                                        </div>
                                        <div class="form_inline">
                                            <div class="select" id="sizeSelect">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form_row">
                                        <div class="label_inline width_150p">
                                            Quantity
                                        </div>
                                        <div class="form_inline">
                                            <input type="text" class="form_global" placeholder="qty" name="qty">
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_danger width_150p " data-modal-close="colorModal">Close</button>
                                            <input type="hidden" id="itemId">
                                            <input type="hidden" name="order_id" id="orderId" value="{{ $order->id }}">
                                            <button class="ly_btn btn_blue width_150p addItem">Add Item</button>
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

    <template id="template-modal-item">
        <div class="ly-2 modal-item">
            <div class="item_list">
                <div class="item_list_text">
                    <img src="">
                </div>
                <span class="item_list_desc">
                <h2 class="style_no">
                    <a class="openColorModal link" id="openColorModal" data-toggle="modal" data-target="#colorModal" data-id=""></a>
                </h2>
            </span>
            </div>
        </div>
    </template>

    <input type="hidden" id="promo_type" value="@if(!empty($order->promo_type)) {{ $order->promo_type }} @endif">
    <input type="hidden" id="promo_percent" value="@if(!empty($order->promo_percent)) {{ $order->promo_percent }} @endif">
    <input type="hidden" id="promo_amount" value="@if(!empty($order->promo_ammount)) {{ $order->promo_ammount }} @endif">
    <input type="hidden" id="coupon_type" value="@if(!empty($order->coupon_type)) {{$order->coupon_type}} @endif">
    <input type="hidden" id="coupon_amount" value="@if(!empty($order->coupon_amount)) {{$order->coupon_amount}} @endif">

@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function () {

            var promotion = <?php echo json_encode($promotion); ?>;
            var coupon = <?php echo json_encode($coupon); ?>;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '#authorizeCapture', function (e) {
                e.preventDefault();
                var orderID = '{{ $order->id }}';

                var choice = confirm($(this).attr('data-confirm'));

                if (choice) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('authorize_capture') }}",
                        data: {order: orderID}
                    }).done(function (data) {
                        if (data.success){
                            setTimeout(function () {
                                location.reload(true);
                                toastr.success('Status Updated!');
                            })
                        }
                    });
                }
            });

            $(document).on('click', '#cancelPayment', function (e) {
                e.preventDefault();
                var orderID = '{{ $order->id }}';

                var choice = confirm($(this).attr('data-confirm'));

                if (choice) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('cancel_payment') }}",
                        data: {order: orderID}
                    }).done(function (data) {
                        if (data.success){
                            setTimeout(function () {
                                location.reload(true);
                                toastr.success('Status Updated!');
                            })
                        }
                        location.reload(true);
                    });
                }
            });

            $(document).on('click', '#captureOnly', function (e) {
                e.preventDefault();
                var orderID = '{{ $order->id }}';

                var choice = confirm($(this).attr('data-confirm'));

                if (choice) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('capture_authorized_amount') }}",
                        data: {order: orderID}
                    }).done(function (data) {
                        if (data.success){
                            setTimeout(function () {
                                location.reload(true);
                                toastr.success('Status Updated!');
                            })
                        }
                        location.reload(true);
                    });
                }
            });

            $(document).on('click', '#refundPayment', function (e) {
                e.preventDefault();
                var orderID = '{{ $order->id }}';

                var choice = confirm($(this).attr('data-confirm'));

                if (choice) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('refund_payment') }}",
                        data: {order: orderID}
                    }).done(function (data) {
                        if (data.success){
                            setTimeout(function () {
                                // location.reload(true);
                                toastr.success('Status Updated!');
                            })
                        }
                        location.reload(true);
                    });
                }
            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('#order_discount, #input_shipping_cost, #input_store_credit').keyup(function() {

                var discount = 0;
                var shippingCost = 0;
                var subTotal = parseFloat($('#span_subtotal').html());
                var storeCredit = 0;
                var dollarDiscount = 0;

                var discountInput = parseFloat($('#order_discount').val());
                var shippingInput = parseFloat($('#input_shipping_cost').val());
                var storeCreditInput = parseFloat($('#input_store_credit').val());
                var dollarDiscountInput = parseFloat($('#dollar_point_discount').val());

                if (!isNaN(discountInput))
                    discount = discountInput;

                if (!isNaN(shippingInput))
                    shippingCost = shippingInput;

                if (!isNaN(storeCreditInput))
                    storeCredit = storeCreditInput;

                if (!isNaN(dollarDiscountInput))
                    dollarDiscount = dollarDiscountInput;

                var total = subTotal + shippingCost - discount - storeCredit - dollarDiscount;
                var finalSubTotal = subTotal - discount - storeCredit - dollarDiscount;
                if(finalSubTotal<0){
                    alert('Storecredit and discount cannot be greater than subtotal!')
                    location.reload();
                }
                $('#span_total').html('$' + total.toFixed(2));
                $('#span_subtotal').html(subTotal.toFixed(2));

            });

            function calculate() {
                var discount = 0;
                var shippingCost = 0;
                var subTotal = 0;
                var storeCredit = 0;
                var dollarDiscount = 0;

                $('.input_amount').each(function () {
                    subTotal += parseFloat($(this).val());
                });

                var discountInput = parseFloat($('#order_discount').val());
                var dollarDiscountInput = parseFloat($('#dollar_point_discount').val());
                var shippingInput = parseFloat($('#input_shipping_cost').val());

                var storeCreditInput = parseFloat($('#input_store_credit').val());

                if (!isNaN(discountInput))
                    discount = discountInput;

                if (!isNaN(shippingInput))
                    shippingCost = shippingInput;

                if (!isNaN(storeCreditInput))
                    storeCredit = storeCreditInput;

                if (!isNaN(dollarDiscountInput))
                    dollarDiscount = dollarDiscountInput;

                var promotion_discount = 0;
                var coupon_discount = 0;

                var couponDetails = '';

                if(coupon) {

                    if(coupon.to_price_1) {
                    } else {
                        coupon.to_price_1 = 1000000;
                    }

                    if(subTotal >= coupon.from_price_1 && subTotal <= coupon.to_price_1) {

                        if(coupon.promotion_type == 'Percentage discount by order amount') {

                            coupon_discount = (coupon.percentage_discount_1 / 100) * subTotal;
                            couponDetails = '["' + coupon.coupon_code + '" - ' + coupon.percentage_discount_1 + '%]';

                        } else {

                            coupon_discount = coupon.unit_price_discount_1;
                            couponDetails = '["' + coupon.coupon_code + '" - $' + coupon.unit_price_discount_1 + ']';

                        }

                    } else {

                        if(coupon.to_price_2) {
                        } else {
                            coupon.to_price_2 = 1000000;
                        }

                        if(subTotal >= coupon.from_price_2 && subTotal <= coupon.to_price_2) {

                            if(coupon.promotion_type == 'Percentage discount by order amount') {

                                coupon_discount = (coupon.percentage_discount_2 / 100) * subTotal;
                                couponDetails = '["' + coupon.coupon_code + '" - ' + coupon.percentage_discount_2 + '%]';

                            } else {

                                coupon_discount = coupon.unit_price_discount_2;
                                couponDetails = '["' + coupon.coupon_code + '" - $' + coupon.unit_price_discount_2 + ']';

                            }

                        } else {

                            if(coupon.to_price_3) {
                            } else {
                                coupon.to_price_3 = 1000000;
                            }

                            if(subTotal >= coupon.from_price_3 && subTotal <= coupon.to_price_3) {

                                if(coupon.promotion_type == 'Percentage discount by order amount') {

                                    coupon_discount = (coupon.percentage_discount_3 / 100) * subTotal;
                                    couponDetails = '["' + coupon.coupon_code + '" - ' + coupon.percentage_discount_3 + '%]';

                                } else {

                                    coupon_discount = coupon.unit_price_discount_3;
                                    couponDetails = '["' + coupon.coupon_code + '" - $' + coupon.unit_price_discount_3 + ']';

                                }

                            } else {

                                if(coupon.to_price_4) {
                                } else {
                                    coupon.to_price_4 = 1000000;
                                }

                                if(subTotal >= coupon.from_price_4 && subTotal <= coupon.to_price_4) {

                                    if(coupon.promotion_type == 'Percentage discount by order amount') {

                                        coupon_discount = (coupon.percentage_discount_4 / 100) * subTotal;
                                        couponDetails = '["' + coupon.coupon_code + '" - ' + coupon.percentage_discount_4 + '%]';

                                    } else {

                                        coupon_discount = coupon.unit_price_discount_4;
                                        couponDetails = '["' + coupon.coupon_code + '" - $' + coupon.unit_price_discount_4 + ']';

                                    }

                                } else {

                                    if(coupon.to_price_5) {
                                    } else {
                                        coupon.to_price_5 = 1000000;
                                    }

                                    if(subTotal >= coupon.from_price_5 && subTotal <= coupon.to_price_5) {

                                        if(coupon.promotion_type == 'Percentage discount by order amount') {

                                            coupon_discount = (coupon.percentage_discount_5 / 100) * subTotal;
                                            couponDetails = '["' + coupon.coupon_code + '" - ' + coupon.percentage_discount_5 + '%]';

                                        } else {

                                            coupon_discount = coupon.unit_price_discount_5;
                                            couponDetails = '["' + coupon.coupon_code + '" - $' + coupon.unit_price_discount_5 + ']';

                                        }

                                    }

                                }
                            }
                        }
                    }
                }else{
                    coupon_discount = parseFloat($('#order_discount').val());
                }

                var promotionDetails = '';

                if(promotion) {

                    if(promotion.to_price_1) {
                    } else {
                        promotion.to_price_1 = 1000000;
                    }

                    if(subTotal >= promotion.from_price_1 && subTotal <= promotion.to_price_1) {

                        if(promotion.promotion_type == 'Percentage discount by order amount') {

                            promotion_discount = (promotion.percentage_discount_1 / 100) * subTotal;
                            promotionDetails = '["' + promotion.title + '" - ' + promotion.percentage_discount_1 + '%]';

                        } else {

                            promotion_discount = promotion.unit_price_discount_1;
                            promotionDetails = '["' + promotion.title + '" - $' + promotion.unit_price_discount_1 + ']';

                        }

                    } else {

                        if(promotion.to_price_2) {
                        } else {
                            promotion.to_price_2 = 1000000;
                        }

                        if(subTotal >= promotion.from_price_2 && subTotal <= promotion.to_price_2) {

                            if(promotion.promotion_type == 'Percentage discount by order amount') {

                                promotion_discount = (promotion.percentage_discount_2 / 100) * subTotal;
                                promotionDetails = '["' + promotion.title + '" - ' + promotion.percentage_discount_2 + '%]';

                            } else {

                                promotion_discount = promotion.unit_price_discount_2;
                                promotionDetails = '["' + promotion.title + '" - $' + promotion.unit_price_discount_2 + ']';

                            }

                        } else {

                            if(promotion.to_price_3) {
                            } else {
                                promotion.to_price_3 = 1000000;
                            }

                            if(subTotal >= promotion.from_price_3 && subTotal <= promotion.to_price_3) {

                                if(promotion.promotion_type == 'Percentage discount by order amount') {

                                    promotion_discount = (promotion.percentage_discount_3 / 100) * subTotal;
                                    promotionDetails = '["' + promotion.title + '" - ' + promotion.percentage_discount_3 + '%]';

                                } else {

                                    promotion_discount = promotion.unit_price_discount_3;
                                    promotionDetails = '["' + promotion.title + '" - $' + promotion.unit_price_discount_3 + ']';

                                }

                            } else {

                                if(promotion.to_price_4) {
                                } else {
                                    promotion.to_price_4 = 1000000;
                                }

                                if(subTotal >= promotion.from_price_4 && subTotal <= promotion.to_price_4) {

                                    if(promotion.promotion_type == 'Percentage discount by order amount') {

                                        promotion_discount = (promotion.percentage_discount_4 / 100) * subTotal;
                                        promotionDetails = '["' + promotion.title + '" - ' + promotion.percentage_discount_4 + '%]';

                                    } else {

                                        promotion_discount = promotion.unit_price_discount_4;
                                        promotionDetails = '["' + promotion.title + '" - $' + promotion.unit_price_discount_4 + ']';

                                    }

                                } else {

                                    if(promotion.to_price_5) {
                                    } else {
                                        promotion.to_price_5 = 1000000;
                                    }

                                    if(subTotal >= promotion.from_price_5 && subTotal <= promotion.to_price_5) {

                                        if(promotion.promotion_type == 'Percentage discount by order amount') {

                                            promotion_discount = (promotion.percentage_discount_5 / 100) * subTotal;
                                            promotionDetails = '["' + promotion.title + '" - ' + promotion.percentage_discount_5 + '%]';

                                        } else {

                                            promotion_discount = promotion.unit_price_discount_5;
                                            promotionDetails = '["' + promotion.title + '" - $' + promotion.unit_price_discount_5 + ']';

                                        }

                                    }

                                }
                            }

                        }
                    }

                }

                var discount = parseFloat(coupon_discount) + parseFloat(promotion_discount);
                $('#promotion-text').html('');
                console.log(couponDetails)
                console.log(promotionDetails)
                $('#promotion-text').html(couponDetails + promotionDetails);
                $('#order_discount').val(parseFloat(discount.toFixed(2)));

                var total = subTotal + shippingCost - discount - storeCredit - dollarDiscount;
                var finalSubTotal = subTotal - discount - storeCredit - dollarDiscount;
                $('#span_total').html('$' + total.toFixed(2));
                $('#span_subtotal').html(subTotal.toFixed(2));
            }

            function isFloat(n){
                return Number(n) === n && n % 1 !== 0;
            }

            $('#order_discount').keyup(function () {
                $('#order_discount').addClass('disable_backend_discount');
            });
            $('#order_discount').click(function () {
                $('#order_discount').addClass('disable_backend_discount');
            });


            $('#btnBackOrder').click(function (e) {
                e.preventDefault();
                var ids = [];

                $('.item_checkbox').each(function (i) {
                    if ($(this).is(':checked')) {
                        ids.push($(this).val());
                    }
                });

                if (ids.length > 0) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_create_back_order') }}",
                        data: {ids: ids}
                    }).done(function (data) {
                        if (data.success)
                            window.location.replace(data.url);
                    });
                }
            });

            $('#btnOutOfStock').click(function (e) {
                e.preventDefault();
                var ids = [];

                $('.item_checkbox').each(function (i) {
                    if ($(this).is(':checked')) {
                        ids.push($(this).val());
                    }
                });

                if (ids.length > 0) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_out_of_stock') }}",
                        data: {ids: ids}
                    }).done(function (msg) {
                        location.reload();
                    });
                }
            });

            $('#btnDeleteItem').click(function (e) {
                e.preventDefault();
                var ids = [];

                $('.item_checkbox').each(function (i) {
                    if ($(this).is(':checked')) {
                        ids.push($(this).val());
                    }
                });

                if (ids.length > 0) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_delete_order_item') }}",
                        data: {ids: ids}
                    }).done(function (msg) {
                        location.reload();
                    });
                }
            });

            $('#btnShowCard').click(function (e) {
                e.preventDefault();

                $('#input-password').val('');
                var targeted_modal_class = 'password-modal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });
            $('#paypalRecaptcha').click(function (e) {
                e.preventDefault();
                var id = $(this).data('id')
                $.ajax({
                    method: "POST",
                    url: "{{ route('paypal_recaptcha') }}",
                    data: {id: id}
                }).done(function (data) {
                    console.log(data)
                });

            });
            $('#stripeAuth').click(function (e) {
                e.preventDefault();
                var id = $(this).data('id')
                $.ajax({
                    method: "POST",
                    url: "{{ route('stripe_auth') }}",
                    data: {id: id}
                }).done(function (data) {
                    location.reload();
                });

            });

            $('#btnCheckPassword').click(function () {
                var password = $('#input-password').val();
                var orderID = '{{ $order->id }}';

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_order_check_password') }}",
                    data: {password: password, orderID: orderID}
                }).done(function (data) {
                    if (data.success) {
                        var targeted_modal_class = 'password-modal';
                        $('[data-modal="' + targeted_modal_class + '"]').removeClass('open_modal');

                        $('#card-info-fullName').html(data.fullName);
                        $('#card-info-number').html(data.number);
                        $('#card-info-cvc').html(data.cvc);
                        $('#card-info-expire').html(data.expire);
                        $('#card-info-table').removeClass('d_none');
                        $('#btnShowCard').addClass('d_none');
                        $('#btnMask').removeClass('d_none');
                        $('#btnHideCard').removeClass('d_none');
                    } else {
                        alert('Invalid Password.');
                    }
                });
            });

            $('#btnMask').click(function (e) {
                e.preventDefault();

                var orderID = '{{ $order->id }}';

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_order_mask_card_number') }}",
                    data: {id: orderID}
                }).done(function (data) {
                    if (data.success) {
                        $('#card-info-number').html(data.mask);
                    }
                });
            });

            $('#btnHideCard').click(function (e) {
                e.preventDefault();

                $('#card-info-fullName').html('');
                $('#card-info-number').html('');
                $('#card-info-cvc').html('');
                $('#card-info-expire').html('');
                $('#card-info-table').addClass('d_none');

                $('#btnShowCard').removeClass('d_none');
                $('#btnMask').addClass('d_none');
                $('#btnHideCard').addClass('d_none');
            });

            // Update Calculation
            $('.input_size, .input_unit_price').keyup(function () {
                var id = $(this).data('id');

                allSizeCalculation(id);
            });

            // Add product
            $('#btnAddProduct').click(function (e) {
                e.preventDefault();

                var targeted_modal_class = 'item-modal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $(document).on('click','.openColorModal',function(){
                var targeted_modal_class = 'colorModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');

                var id = $(this).data('id');
                $('#itemId').val(id);

                itemId =id;

                $.ajax({
                    method: "GET",
                    url: "{{ route('admin_get_item_details') }}",
                    data: {itemId: id }
                }).done(function(res) {
                    if (res.color.length == 0) {
                        $('.color_row').css('display' , 'none');
                    } else {
                        $('.color_row').css('display' , 'table');

                        var output = '<select class="form_global" name="color_id" id="color_id">';
                        output += '<option value="">Select Color</option>';
                        $.each( res.color, function( i, e ){
                            output += '<option value="'+e.color_id+'">'+e.name+'</option>';
                        });
                        output +='</select>';

                        $('#colorSelect').html(output);
                    }

                    if (res.size.length == 0) {
                        $('.size_row').css('display' , 'none');
                    } else {
                        $('.size_row').css('display' , 'table');

                        if (res.color.length == 0) {
                            var output = '<select class="form_global" name="size_id">';
                            output += '<option value="">Select Size</option>';
                            $.each( res.size, function( i, e ){
                                output += '<option value="'+e.size_id+'">'+e.name+'</option>';
                            });
                            output +='</select>';
                        } else {
                            var output = '<select class="form_global" name="size_id"><option value="">Select Size</option></select>';
                        }

                        $('#sizeSelect').html(output);
                    }
                });
            });

            $(document).on('change', '#color_id', function () {
                var colorId = $(this).val();

                $.ajax({
                    url:"{{route('cart_item_size')}}",
                    data: { colorId: colorId, itemId: itemId },
                    success: function( response ) {
                        var output = '<select class="form_global" name="size_id">';
                        output += '<option value="">Select Size</option>';
                        $.each( response, function( i, e ){
                            if (e.size)
                                output += '<option value="'+e.size.id+'">'+e.size.item_size+'</option>';
                        });
                        output +='</select>';

                        $('#sizeSelect').html(output);
                    }
                });
            });

            $('.addItem').click(function (e) {
                e.preventDefault();

                var itemId = $('#itemId').val();
                var orderId = $('#orderId').val();
                var colorId = $("select[name=color_id]").val();
                var sizeId = $("select[name=size_id]").val();

                // if(colorId == ''){
                //     alert('please select a color');
                // }

                // if(sizeId == ''){
                //     alert('please select a Size');
                // }

                var qty = $("input[name=qty]").val();

                if(qty == ''){
                    alert('please give a quantity');
                }else{
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_order_add_item') }}",
                        data: {itemId: itemId , qty : qty, colors:colorId, size:sizeId, orderId:orderId}
                    }).done(function (data) {
                        $('#colorModal').hide();
                        window.location.reload();
                    });
                }
            });

            function allSizeCalculation(id) {
                var totalSize = 0;
                var unitPrice = 0;

                // Size calculation
                $('.input_size_'+id).each(function () {
                    var i = 0;
                    var val = $(this).val();

                    if (isInt(val)) {
                        i = parseInt(val);

                        if (i < 0)
                            i = 0;
                    }

                    totalSize += i;
                });
                // Price
                var val = $('#input_unit_price_'+id).val();

                if (!isNaN(val) && val != '')
                    unitPrice = parseFloat(val);

                $('#amount_'+id).html('$' + (totalSize  * unitPrice).toFixed(2));
                $('#input_amount_'+id).val((totalSize  * unitPrice).toFixed(2));

                calculate();
            }

            function isInt(value) {
                return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
            }

            // Pagination
            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var page = getURLParameter(url, 'page');

                filterItem(page);
            });

            function getURLParameter(url, name) {
                return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
            }

            function filterItem(page) {
                page = typeof page !== 'undefined' ? page : 1;
                var search = $('#modal-search').val();
                var category = $('#modal-category').val();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_get_items_for_modal') }}",
                    data: { search: search, category: category, page: page }
                }).done(function( data ) {
                    var products = data.items.data;
                    $('#modal-pagination').html(data.pagination);

                    $('.modal-items').html('');

                    $.each(products, function (index, product) {
                        var html = $('#template-modal-item').html();
                        var row = $(html);
                        row.attr('data-id', product.id);
                        row.find('.openColorModal').attr('data-id', product.id);
                        row.find('img').attr('src', product.imagePath);
                        row.find('.openColorModal').html(product.style_no);

                        $('.modal-items').append(row);
                    });
                });
            }

            $('#modal-btn-search').click(function () {
                filterItem();
            });

            // Store Credit
            $('#btnAddStoreCredit').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_add_store_credit') }}",
                    data: $('#form-store-credit').serialize(),
                }).done(function( data ) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            });

            $('#modal-store-credit-button').on('click', function (e) {
                $('#form-store-credit').trigger("reset");
            });

            $('#showRefundMessage').click(function(){
                alert('Payment Not Settled Yet');
                location.reload(true);
            });

            // Order Status Changed
            $('#order_status').change(function () {
                var id = '{{ $order->id }}';
                var status = $(this).val();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_change_order_status') }}",
                    data: { id: [id], status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });
        });
    </script>
@stop
