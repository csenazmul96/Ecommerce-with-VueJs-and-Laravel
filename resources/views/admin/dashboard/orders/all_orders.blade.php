<?php use App\Enumeration\OrderStatus; ?>

@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <style>
        .shipstation .success{
            color:green;
        }
        .shipstation .fail{
            color:red;
        }
    </style>
@stop

@section('content')
    <div class="ly_accrodion">
        <div class="ly_accrodion_heading">
            <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#orderFilter" class="accordion_heading" data-class="accordion">
                <span> Search</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="orderFilter">
            <div class="ly-row">
                <div class="ly-2">
                    <div class="display_inline width_200p">
                        <div class="select">
                            <select class="form_global" id="selectOrderDate">
                                <option value="0" {{ (request()->get('date') == '0') ? 'selected' : '' }}>Input Period</option>
                                <option value="1" {{ (request()->get('date') == '1') ? 'selected' : '' }}>Today</option>
                                <option value="2" {{ (request()->get('date') == '2') ? 'selected' : '' }}>This Week</option>
                                <option value="3" {{ (request()->get('date') == '3') ? 'selected' : '' }}>This Month</option>
                                <option value="5" {{ (request()->get('date') == '5') ? 'selected' : '' }}>This Year</option>
                                <option value="6" {{ (request()->get('date') == '6') ? 'selected' : '' }}>Yesterday</option>
                                <option value="8" {{ (request()->get('date') == '8') ? 'selected' : '' }}>Last Month</option>
                                <option value="10" {{ (request()->get('date') == '10') ? 'selected' : '' }}>Last Year</option>
                                <option value="13" {{ (request()->get('date') == '13') ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="14" {{ (request()->get('date') == '14' || request()->get('date') == null) ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="15" {{ (request()->get('date') == '15') ? 'selected' : '' }}>Last 90 Days</option>
                                <option value="16" {{ (request()->get('date') == '16') ? 'selected' : '' }}>Last 365 Days</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="ly-2 " id="date-range">
                    <div class="ly-auto">
                        <label class="sr-only" for="inlineFormInputGroup"></label>
                        <div class="datepicker_wrapper">
                            <input type="text" id="dateRange" class="form_global"
                                   value="{{ (request()->get('startDate') != null ) ? request()->get('startDate').' - '.request()->get('endDate') : '' }}">
                        </div>
                    </div>
                </div>

                <div class="ly-2">
                    <div class="display_inline width_200p">
                        <div class="select">
                            <select class="form_global" id="searchItem">
                                <option value="2" {{ request()->get('search') == '2' ? 'selected' : '' }}>Order Number</option>
                                <option value="3" {{ request()->get('search') == '3' ? 'selected' : '' }}>Tracking No.</option>
                                <option value="4" {{ request()->get('search') == '4' ? 'selected' : '' }}>Customer Name</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="ly-3">
                    <div class="ly-auto">
                        <div class="display_inline ">
                            <div class="plc_fixed_left_search width_350p">
                                <input class="form_global" type="text" id="inputText" value="{{ request()->get('text') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="ly-row form-group">
                <div class="ly-2">
                    <div class="display_inline width_200p">
                        <div class="select">
                            <select class="form_global" id="selectShipStatus">
                                <option value="">Shipped Status</option>
                                <option value="1" {{ request()->get('ship') == '1' ? 'selected' : '' }}>Partially Shipped</option>
                                <option value="2" {{ request()->get('ship') == '2' ? 'selected' : '' }}>Fully Shipped</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="ly-8">
                    <button class="ly_btn btn_blue min_width_100p" id="btnApply">Apply</button>
                    <button class="ly_btn btn_danger min_width_100p" id="btnReset">Reset All</button>
                </div>
                <div class="ly-2">
                    <button class="ly_btn btn_blue min_width_100p float-right float_right" id="sync_shipstation">Sync Order To ShipStation</button>
                </div>
            </div>
        </div>
    </div>

    <div class="ly_accrodion">
        <div class="ly_accrodion_heading">
            <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#newOrder" class="accordion_heading" data-class="accordion">
                <span> New Order</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="newOrder">
            <div class="ly-row">
                <div class="ly-12">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <div class="form-check custom_checkbox">
                                        <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-new-order">
                                        <label class="form-check-label" for="checkbox-new-order"></label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Order #</th>
                                <th>Customer Name</th>
                                <th>Amount</th>
                                <th>Shipping Method</th>
                                <th># of Orders</th>
                                <th>Status</th>
                                <th>Payment Type</th>
                                <th>Authorize</th>
                                <th>AVS</th>
                                <th>CVV</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $total = 0; ?>
                            @foreach($newOrders as $order)
                                <tr>
                                    <td>
                                        <div class="form-check custom_checkbox">
                                            <input class="form-check-input checkbox-order" type="checkbox" id="checkbox-order-{{ $order->id }}" data-id="{{ $order->id }}">
                                            <label class="form-check-label" for="checkbox-order-{{ $order->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ date('m/d/Y', strtotime($order->created_at)) }}</td>
                                    <td><a class="text-primary" href="{{ route('admin_order_details', ['order' => $order->id]) }}">@if($order->user_id == 0) {{ $order->order_number }}(GO) @else {{ $order->order_number }} @endif</a></td>
                                    <td>{{ $order->name }}</td>
                                    <td>${{ sprintf('%0.2f', $order->total) }}</td>
                                    <td>@if(!empty($order->shipping_courier_name)) {{ $order->shipping_courier_name }} | @endif {{ $order->shipping_method_name }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global order_status" name="order_status" data-id="{{ $order->id }}">
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
                                    </td>
                                    <td>{{ $order->payment_type }}</td>
                                    <td>
                                        @if (@$order->aStatus)
                                            <span class="{{ $order->aStatus['status'] }}" title="{{$order->aStatus['message']}}">{{ $order->aStatus['status'] }}</span>
                                        @else
                                            <span class="" title="Not Authorized Yet">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['avs_code']))
                                                <span class="flag_icon {{$order->aStatus['avs_code']}}" title="{{ @$order->aStatus['avs_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['cvv_code']))
                                                <span class="flag_icon cvv {{$order->aStatus['cvv_code']}}" title="{{ @$order->aStatus['cvv_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btnDelete link" data-id="{{ $order->id }}" role="button" style="color: red">Delete</a>
                                    </td>
                                </tr>

                                <?php $total += $order->total; ?>
                            @endforeach

                            @if (sizeof($newOrders) > 0)
                                <tr>
                                    <td colspan="4">
                                        <button class="ly_btn btn_blue btnPrintInvoiceOrder">Print Invoice</button>
                                    </td>

                                    <td colspan="3">${{ number_format($totals['new'], 2, '.', '') }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global all_order_status">
                                                    <option value="">Select Status</option>
                                                    <option value="{{ OrderStatus::$NEW_ORDER }}">New Orders</option>
                                                    <option value="{{ OrderStatus::$CONFIRM_ORDER }}">Confirmed Orders</option>
                                                    <option value="{{ OrderStatus::$PARTIALLY_SHIPPED_ORDER }}">Partially Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$FULLY_SHIPPED_ORDER }}">Fully Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$BACK_ORDER }}">Back Ordered</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_BUYER }}">Cancelled by Buyer</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_VENDOR }}">Cancelled by Vendor</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_AGREEMENT }}">Cancelled by Agreement</option>
                                                    <option value="{{ OrderStatus::$RETURNED }}">Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <div class="pagination_wrapper p10 pt_0">
                            <ul class="pagination">
                                <li><button class="ly_btn p1_first{{ $newOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $newOrders->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                                <li>
                                    <button class="ly_btn p1_prev{{ $newOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $newOrders->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                                </li>
                                <li>
                                    <div class="pagination_input">
                                        <input type="number" min="1" max="{{ $newOrders->lastPage() }}" class="form_global p1" value="{{ $newOrders->currentPage() }}"> of {{ $newOrders->lastPage() }}
                                    </div>
                                    <div class="pagination_btn">
                                        <button class="ly_btn switch_page">GO</button>
                                    </div>
                                </li>
                                <li><button class="ly_btn p1_next{{ $newOrders->currentPage() < $newOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $newOrders->currentPage() == $newOrders->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                                <li>
                                    <button class="ly_btn p1_last{{ $newOrders->currentPage() < $newOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $newOrders->currentPage() == $newOrders->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ly_accrodion">
        <div class="ly_accrodion_heading">
            <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#confirmOrder" class="accordion_heading" data-class="accordion">
                <span> Confirmed Orders</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="confirmOrder">
            <div class="ly-row">
                <div class="ly-12">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <div class="form-check custom_checkbox">
                                        <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-confirm-order">
                                        <label class="form-check-label" for="checkbox-confirm-order">&nbsp;</label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Order #</th>
                                <th>Customer Name</th>
                                <th>Amount</th>
                                <th>Shipping Method</th>
                                <th># of Orders</th>
                                <th>Status</th>
                                <th>Payment Type</th>
                                <th>Authorize</th>
                                <th>AVS</th>
                                <th>CVV</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($confirmOrders as $order)
                                <tr>
                                    <td>
                                        <div class="form-check custom_checkbox">
                                            <input class="form-check-input checkbox-order" type="checkbox" id="checkbox-order-{{ $order->id }}" data-id="{{ $order->id }}">
                                            <label class="form-check-label" for="checkbox-order-{{ $order->id }}">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>{{ date('m/d/Y', strtotime($order->created_at)) }}</td>
                                    <td><a class="text-primary" href="{{ route('admin_order_details', ['order' => $order->id]) }}">@if($order->user_id == 0) {{ $order->order_number }}(GO) @else {{ $order->order_number }} @endif</a></td>
                                    <td>{{ $order->name }}</td>
                                    <td>${{ sprintf('%0.2f', $order->total) }}</td>
                                    <td>@if(!empty($order->shipping_courier_name)) {{ $order->shipping_courier_name }} | @endif {{ $order->shipping_method_name }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global order_status" name="order_status" data-id="{{ $order->id }}">
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
                                    </td>
                                    <td>{{ $order->payment_type }}</td>
                                    <td>
                                        @if (@$order->aStatus)
                                            <span class="{{ $order->aStatus['status'] }}" title="{{$order->aStatus['message']}}">{{ $order->aStatus['status'] }}</span>
                                        @else
                                            <span class="" title="Not Authorized Yet">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['avs_code']))
                                                <span class="flag_icon {{$order->aStatus['avs_code']}}" title="{{ @$order->aStatus['avs_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['cvv_code']))
                                                <span class="flag_icon cvv {{$order->aStatus['cvv_code']}}" title="{{ @$order->aStatus['cvv_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td><a class="btnDelete link" data-id="{{ $order->id }}" role="button" style="color: red">Delete</a></td>
                                </tr>
                            @endforeach

                            @if (sizeof($confirmOrders) > 0)
                                <tr>
                                    <td colspan="4">
                                        <button class="ly_btn btn_blue btnPrintInvoiceOrder">Print Invoice</button>
                                    </td>

                                    <td colspan="3">${{ number_format($totals['confirm'], 2, '.', '') }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global all_order_status">
                                                    <option value="">Select Status</option>
                                                    <option value="{{ OrderStatus::$NEW_ORDER }}">New Orders</option>
                                                    <option value="{{ OrderStatus::$CONFIRM_ORDER }}">Confirmed Orders</option>
                                                    <option value="{{ OrderStatus::$PARTIALLY_SHIPPED_ORDER }}">Partially Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$FULLY_SHIPPED_ORDER }}">Fully Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$BACK_ORDER }}">Back Ordered</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_BUYER }}">Cancelled by Buyer</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_VENDOR }}">Cancelled by Vendor</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_AGREEMENT }}">Cancelled by Agreement</option>
                                                    <option value="{{ OrderStatus::$RETURNED }}">Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <div class="pagination_wrapper p10 pt_0">
                            <ul class="pagination">
                                <li><button class="ly_btn p2_first{{ $confirmOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $confirmOrders->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                                <li>
                                    <button class="ly_btn p2_prev{{ $confirmOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $confirmOrders->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                                </li>
                                <li>
                                    <div class="pagination_input">
                                        <input type="number" min="1" max="{{ $confirmOrders->lastPage() }}" class="form_global p2" value="{{ $confirmOrders->currentPage() }}"> of {{ $confirmOrders->lastPage() }}
                                    </div>
                                    <div class="pagination_btn">
                                        <button class="ly_btn switch_page">GO</button>
                                    </div>
                                </li>
                                <li><button class="ly_btn p2_next{{ $confirmOrders->currentPage() < $confirmOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $confirmOrders->currentPage() == $confirmOrders->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                                <li>
                                    <button class="ly_btn p2_last{{ $confirmOrders->currentPage() < $confirmOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $confirmOrders->currentPage() == $confirmOrders->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ly_accrodion">
        <div class="ly_accrodion_heading">
            <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#backOrder" class="accordion_heading" data-class="accordion">
                <span> Back Order</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="backOrder">
            <div class="ly-row">
                <div class="ly-12">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <div class="form-check custom_checkbox">
                                        <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-back-order">
                                        <label class="form-check-label" for="checkbox-back-order"></label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Order #</th>
                                <th>Customer Name</th>
                                <th>Amount</th>
                                <th>Shipping Method</th>
                                <th># of Orders</th>
                                <th>Status</th>
                                <th>Payment Type</th>
                                <th>Authorize</th>
                                <th>AVS</th>
                                <th>CVV</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($backOrders as $order)
                                <tr>
                                    <td>
                                        <div class="form-check custom_checkbox">
                                            <input class="form-check-input checkbox-order" type="checkbox" id="checkbox-order-{{ $order->id }}" data-id="{{ $order->id }}">
                                            <label class="form-check-label" for="checkbox-order-{{ $order->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ date('m/d/Y', strtotime($order->created_at)) }}</td>
                                    <td><a class="text-primary" href="{{ route('admin_order_details', ['order' => $order->id]) }}">@if($order->user_id == 0) {{ $order->order_number }}(GO) @else {{ $order->order_number }} @endif</a></td>
                                    <td>{{ $order->name }}</td>
                                    <td>${{ sprintf('%0.2f', $order->total) }}</td>
                                    <td>@if(!empty($order->shipping_courier_name)) {{ $order->shipping_courier_name }} | @endif {{ $order->shipping_method_name }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global order_status" name="order_status" data-id="{{ $order->id }}">
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
                                    </td>

                                    <td>{{ $order->payment_type }}</td>
                                    <td>
                                        @if (@$order->aStatus)
                                            <span class="{{ $order->aStatus['status'] }}" title="{{$order->aStatus['message']}}">{{ $order->aStatus['status'] }}</span>
                                        @else
                                            <span class="" title="Not Authorized Yet">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['avs_code']))
                                                <span class="flag_icon {{$order->aStatus['avs_code']}}" title="{{ @$order->aStatus['avs_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['cvv_code']))
                                                <span class="flag_icon cvv {{$order->aStatus['cvv_code']}}" title="{{ @$order->aStatus['cvv_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btnDelete link" data-id="{{ $order->id }}" role="button" style="color: red">Delete</a>
                                    </td>
                                </tr>
                            @endforeach

                            @if (sizeof($backOrders) > 0)
                                <tr>
                                    <td colspan="4">
                                        <button class="ly_btn btn_blue btnPrintInvoiceOrder">Print Invoice</button>
                                    </td>

                                    <td colspan="3">${{ number_format($totals['back'], 2, '.', '') }}</td>
                                    <td width="13%">
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global all_order_status">
                                                    <option value="">Select Status</option>
                                                    <option value="{{ OrderStatus::$NEW_ORDER }}">New Orders</option>
                                                    <option value="{{ OrderStatus::$CONFIRM_ORDER }}">Confirmed Orders</option>
                                                    <option value="{{ OrderStatus::$PARTIALLY_SHIPPED_ORDER }}">Partially Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$FULLY_SHIPPED_ORDER }}">Fully Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$BACK_ORDER }}">Back Ordered</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_BUYER }}">Cancelled by Buyer</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_VENDOR }}">Cancelled by Vendor</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_AGREEMENT }}">Cancelled by Agreement</option>
                                                    <option value="{{ OrderStatus::$RETURNED }}">Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <div class="pagination_wrapper p10 pt_0">
                            <ul class="pagination">
                                <li><button class="ly_btn p3_first{{ $backOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $backOrders->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                                <li>
                                    <button class="ly_btn p3_prev{{ $backOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $backOrders->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                                </li>
                                <li>
                                    <div class="pagination_input">
                                        <input type="number" min="1" max="{{ $backOrders->lastPage() }}" class="form_global p3" value="{{ $backOrders->currentPage() }}"> of {{ $backOrders->lastPage() }}
                                    </div>
                                    <div class="pagination_btn">
                                        <button class="ly_btn switch_page">GO</button>
                                    </div>
                                </li>
                                <li><button class="ly_btn p3_next{{ $backOrders->currentPage() < $backOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $backOrders->currentPage() == $backOrders->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                                <li>
                                    <button class="ly_btn p3_last{{ $backOrders->currentPage() < $backOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $backOrders->currentPage() == $backOrders->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ly_accrodion">
        <div class="ly_accrodion_heading">
            <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#shippedOrder" class="accordion_heading" data-class="accordion">
                <span> Shipped Orders</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="shippedOrder">
            <div class="ly-row">
                <div class="ly-12">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <div class="form-check custom_checkbox">
                                        <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-shipped-order">
                                        <label class="form-check-label" for="checkbox-shipped-order">&nbsp;</label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Order #</th>
                                <th>Customer Name</th>
                                <th>Amount</th>
                                <th>Shipping Method</th>
                                <th># of Orders</th>
                                <th>Status</th>
                                <th>Payment Type</th>
                                <th>Authorize</th>
                                <th>AVS</th>
                                <th>CVV</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($shippedOrders as $order)
                                <tr>
                                    <td>
                                        <div class="form-check custom_checkbox">
                                            <input class="form-check-input checkbox-order" type="checkbox" id="checkbox-order-{{ $order->id }}" data-id="{{ $order->id }}">
                                            <label class="form-check-label" for="checkbox-order-{{ $order->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ date('m/d/Y', strtotime($order->created_at)) }}</td>
                                    <td><a class="text-primary" href="{{ route('admin_order_details', ['order' => $order->id]) }}">@if($order->user_id == 0) {{ $order->order_number }}(GO) @else {{ $order->order_number }} @endif</a></td>
                                    <td>{{ $order->name }}</td>
                                    <td>${{ sprintf('%0.2f', $order->total) }}</td>
                                    <td>@if(!empty($order->shipping_courier_name)) {{ $order->shipping_courier_name }} | @endif {{ $order->shipping_method_name }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global order_status" name="order_status" data-id="{{ $order->id }}">
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
                                    </td>
                                    <td>{{ $order->payment_type }}</td>
                                    <td>
                                        @if (@$order->aStatus)
                                            <span class="{{ $order->aStatus['status'] }}" title="{{$order->aStatus['message']}}">{{ $order->aStatus['status'] }}</span>
                                        @else
                                            <span class="" title="Not Authorized Yet">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['avs_code']))
                                                <span class="flag_icon {{$order->aStatus['avs_code']}}" title="{{ @$order->aStatus['avs_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['cvv_code']))
                                                <span class="flag_icon cvv {{$order->aStatus['cvv_code']}}" title="{{ @$order->aStatus['cvv_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td><a class="btnDelete link" data-id="{{ $order->id }}" role="button" style="color: red">Delete</a></td>
                                </tr>
                            @endforeach

                            @if (sizeof($shippedOrders) > 0)
                                <tr>
                                    <td colspan="4">
                                        <button class="ly_btn btn_blue btnPrintInvoiceOrder">Print Invoice</button>
                                    </td>

                                    <td colspan="3">${{ number_format($totals['shipped'], 2, '.', '') }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global all_order_status">
                                                    <option value="">Select Status</option>
                                                    <option value="{{ OrderStatus::$NEW_ORDER }}">New Orders</option>
                                                    <option value="{{ OrderStatus::$CONFIRM_ORDER }}">Confirmed Orders</option>
                                                    <option value="{{ OrderStatus::$PARTIALLY_SHIPPED_ORDER }}">Partially Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$FULLY_SHIPPED_ORDER }}">Fully Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$BACK_ORDER }}">Back Ordered</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_BUYER }}">Cancelled by Buyer</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_VENDOR }}">Cancelled by Vendor</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_AGREEMENT }}">Cancelled by Agreement</option>
                                                    <option value="{{ OrderStatus::$RETURNED }}">Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <div class="pagination_wrapper p10 pt_0">
                            <ul class="pagination">
                                <li><button class="ly_btn p4_first{{ $shippedOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $shippedOrders->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                                <li>
                                    <button class="ly_btn p4_prev{{ $shippedOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $shippedOrders->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                                </li>
                                <li>
                                    <div class="pagination_input">
                                        <input type="number" min="1" max="{{ $shippedOrders->lastPage() }}" class="form_global p4" value="{{ $shippedOrders->currentPage() }}"> of {{ $shippedOrders->lastPage() }}
                                    </div>
                                    <div class="pagination_btn">
                                        <button class="ly_btn switch_page">GO</button>
                                    </div>
                                </li>
                                <li><button class="ly_btn p4_next{{ $shippedOrders->currentPage() < $shippedOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $shippedOrders->currentPage() == $shippedOrders->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                                <li>
                                    <button class="ly_btn p4_last{{ $shippedOrders->currentPage() < $shippedOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $shippedOrders->currentPage() == $shippedOrders->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ly_accrodion">
        <div class="ly_accrodion_heading">
            <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#cancelOrder" class="accordion_heading" data-class="accordion">
                <span> Cancel Orders</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="cancelOrder">
            <div class="ly-row">
                <div class="ly-12">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <div class="form-check custom_checkbox">
                                        <input class="form-check-input checkbox-all" type="checkbox" id="checkbox-canceled-order">
                                        <label class="form-check-label" for="checkbox-canceled-order">&nbsp;</label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Order #</th>
                                <th>Customer Name</th>
                                <th>Amount</th>
                                <th>Shipping Method</th>
                                <th># of Orders</th>
                                <th>Status</th>
                                <th>Payment Type</th>
                                <th>Authorize</th>
                                <th>AVS</th>
                                <th>CVV</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($cancelOrders as $order)
                                <tr>
                                    <td>
                                        <div class="form-check custom_checkbox">
                                            <input class="form-check-input checkbox-order" type="checkbox" id="checkbox-order-{{ $order->id }}" data-id="{{ $order->id }}">
                                            <label class="form-check-label" for="checkbox-order-{{ $order->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ date('m/d/Y', strtotime($order->created_at)) }}</td>
                                    <td><a class="text-primary" href="{{ route('admin_order_details', ['order' => $order->id]) }}">@if($order->user_id == 0) {{ $order->order_number }}(GO) @else {{ $order->order_number }} @endif</a></td>
                                    <td>{{ $order->name }}</td>
                                    <td>${{ sprintf('%0.2f', $order->total) }}</td>
                                    <td>@if(!empty($order->shipping_courier_name)) {{ $order->shipping_courier_name }} | @endif {{ $order->shipping_method_name }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global order_status" name="order_status" data-id="{{ $order->id }}">
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
                                    </td>
                                    <td>{{ $order->payment_type }}</td>
                                    <td>
                                        @if (@$order->aStatus)
                                            <span class="{{ $order->aStatus['status'] }}" title="{{$order->aStatus['message']}}">{{ $order->aStatus['status'] }}</span>
                                        @else
                                            <span class="" title="Not Authorized Yet">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['avs_code']))
                                                <span class="flag_icon {{$order->aStatus['avs_code']}}" title="{{ @$order->aStatus['avs_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (@$order->aStatus)
                                            @if (isset($order->aStatus['cvv_code']))
                                                <span class="flag_icon cvv {{$order->aStatus['cvv_code']}}" title="{{ @$order->aStatus['cvv_message'] }}">&nbsp;</span>
                                            @endif
                                        @else
                                            <span class="flag_icon white" title="Not Authorized Yet">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td><a class="btnDelete link" data-id="{{ $order->id }}" role="button" style="color: red">Delete</a></td>
                                </tr>
                            @endforeach

                            @if (sizeof($cancelOrders) > 0)
                                <tr>
                                    <td colspan="4">
                                        <button class="ly_btn btn_blue btnPrintInvoiceOrder">Print Invoice</button>
                                    </td>

                                    <td colspan="3">${{ number_format($totals['cancel'], 2, '.', '') }}</td>
                                    <td>
                                        <div class="display_inline width_200p">
                                            <div class="select">
                                                <select class="form_global all_order_status">
                                                    <option value="">Select Status</option>
                                                    <option value="{{ OrderStatus::$NEW_ORDER }}">New Orders</option>
                                                    <option value="{{ OrderStatus::$CONFIRM_ORDER }}">Confirmed Orders</option>
                                                    <option value="{{ OrderStatus::$PARTIALLY_SHIPPED_ORDER }}">Partially Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$FULLY_SHIPPED_ORDER }}">Fully Shipped Orders</option>
                                                    <option value="{{ OrderStatus::$BACK_ORDER }}">Back Ordered</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_BUYER }}">Cancelled by Buyer</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_VENDOR }}">Cancelled by Vendor</option>
                                                    <option value="{{ OrderStatus::$CANCEL_BY_AGREEMENT }}">Cancelled by Agreement</option>
                                                    <option value="{{ OrderStatus::$RETURNED }}">Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <div class="pagination_wrapper p10 pt_0">
                            <ul class="pagination">
                                <li><button class="ly_btn p5_first{{ $cancelOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $cancelOrders->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                                <li>
                                    <button class="ly_btn p5_prev{{ $cancelOrders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $cancelOrders->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                                </li>
                                <li>
                                    <div class="pagination_input">
                                        <input type="number" min="1" max="{{ $cancelOrders->lastPage() }}" class="form_global p5" value="{{ $cancelOrders->currentPage() }}"> of {{ $cancelOrders->lastPage() }}
                                    </div>
                                    <div class="pagination_btn">
                                        <button class="ly_btn switch_page">GO</button>
                                    </div>
                                </li>
                                <li><button class="ly_btn p5_next{{ $cancelOrders->currentPage() < $cancelOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $cancelOrders->currentPage() == $cancelOrders->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                                <li>
                                    <button class="ly_btn p5_last{{ $cancelOrders->currentPage() < $cancelOrders->lastPage() ?  ' btn_paginate' : ''}}"{{ $cancelOrders->currentPage() == $cancelOrders->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" data-modal="print-modal">
        <div class="modal_overlay" data-modal-close="print-modal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Print</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="print-modal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="display_table m15 text_center">
                                        <div>
                                            <a class="ly_btn btn_blue width_150p" href="" target="_blank" id="btnPrintWithImage">Print with Images</a><br><br>
                                            <a class="ly_btn btn_blue width_150p" href="" target="_blank" id="btnPrintWithoutImage">Print without Images</a>
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

    <div class="modal" data-modal="shipstation">
        <div class="modal_overlay" data-modal-close="shipstation"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_600p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Ship Station Order Sync Message</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="shipstation"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class=" m15">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Message</th>
                                            </tr>
                                            </thead>
                                            <tbody class="shipstation"></tbody>
                                        </table>
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
    <script type="text/javascript" src="{{ asset('plugins/moment/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/js/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var endDate = "{{ request()->get('startDate')}}";
            var startDate = "{{ request()->get('startDate')}}";

            $('#dateRange').daterangepicker({
                endDate: endDate ? endDate : moment(),
                maxDate: moment(),
                startDate: startDate ? startDate : moment().subtract(29, 'days'),
                autoApply  : false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'MM/DD/YYYY'
                },
                applyClass: "ly_btn btn_blue min_width_100p ",
                cancelClass: "ly_btn btn_danger min_width_100p ",
            });
            $('.daterangepicker ').hide();

            var selectedId;

            $('.btnDelete').click(function () {
                selectedId = $(this).data('id');
                var targeted_modal_class = 'deleteModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_delete_order') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });

            $('#selectOrderDate').change(function () {
                if ($(this).val() == '0') {
                    $('#date-range').removeClass('d_none');
                } else {
                    $('#date-range').addClass('d_none');
                }
            });

            $('#selectOrderDate').trigger('change');

            $('#btnApply').click(function () {
                var text = $('#inputText').val();
                var search = $('#searchItem').val();
                var ship = $('#selectShipStatus').val();
                var date = $('#selectOrderDate').val();
                var startDate = $('#dateRange').data('daterangepicker').startDate.format('MM/DD/YYYY');
                var endDate = $('#dateRange').data('daterangepicker').endDate.format('MM/DD/YYYY');

                var url = '{{ route('admin_all_orders') }}' + '?text=' + text + '&search=' + search + '&ship=' + ship +
                    '&date=' + date + '&startDate=' + startDate + '&endDate=' + endDate;
                window.location.replace(url);
            });

            $('#btnReset').click(function () {
                var url = '{{ route('admin_all_orders') }}';
                window.location.replace(url);
            });

            // Multiple Print
            var ids = [];
            $('.btnPrintInvoiceOrder').click(function () {
                ids = [];

                $(this).closest('tbody').find('.checkbox-order').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0) {
                    createOrderPdfUrl();
                    var targeted_modal_class = 'print-modal';
                    $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
                }
            });

            $('.btnPacklistOrder').click(function () {
                ids = [];

                $(this).closest('tbody').find('.checkbox-order').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0) {
                    var url = '{{ route('admin_print_packlist') }}' + '?order=' + ids.join(',');
                    window.open(url, '_blank');
                }
            });

            function createOrderPdfUrl() {
                var url = '{{ route('admin_print_pdf') }}' + '?order=' + ids.join(',');
                var urlWithoutImage = '{{ route('admin_print_pdf_without_image') }}' + '?order=' + ids.join(',');

                $('#btnPrintWithImage').attr('href', url);
                $('#btnPrintWithoutImage').attr('href', urlWithoutImage);
            }

            $('.checkbox-all').change(function () {
                if ($(this).is(':checked')) {
                    $(this).closest('table').find('.checkbox-order').each(function () {
                        $(this).prop('checked', true);
                    });
                } else {
                    $(this).closest('table').find('.checkbox-order').each(function () {
                        $(this).prop('checked', false);
                    });
                }
            });

            $('.order_status').change(function () {
                $('.outslider_loading').removeClass('d_none');
                var id = $(this).data('id');
                var status = $(this).val();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_change_order_status') }}",
                    data: { id: [id], status: status }
                }).done(function( msg ) {
                    location.reload();
                }).always(function () {
                    $('.outslider_loading').addClass('d_none');
                })
            });

            $("#sync_shipstation").click(function () {
                $.ajax({
                    method: "GET",
                    url: "{{ route('sync_ship_station') }}",
                }).done(function( msg ) {
                    if(msg.success === true)
                        toastr.success(msg.message);
                    else
                        toastr.warning(msg.message);
                    if(msg.result && msg.result.length > 0){
                        var data = '';
                        $.each( msg.result, function( key, value ) {
                            data += `<tr><td>${value.orderNumber}</td><td class="${value.success === true ? 'success' : 'fail'}">${value.errorMessage != null? value.errorMessage : 'Success' }</td>`
                        });
                        $(".shipstation").append(data)
                        $('[data-modal="shipstation"]').addClass('open_modal');
                    }

                });
            });
            $('.all_order_status').change(function () {
                var val = $(this).val();

                if (val != '') {
                    ids = [];

                    $(this).closest('tbody').find('.checkbox-order').each(function () {
                        if ($(this).is(':checked')) {
                            ids.push($(this).data('id'));
                        }
                    });

                    if (ids.length > 0) {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('admin_change_order_status') }}",
                            data: { id: ids, status: val }
                        }).done(function( msg ) {
                            location.reload();
                        });
                    }
                }
            });
            $('.switch_page').click(function() {
                if ($(this).closest('li').find('.p1').length > 0) {
                    paginate_change('p1', Number($('.p1').val()), 'new');
                } else if ($(this).closest('li').find('.p2').length > 0) {
                    paginate_change('p2', Number($('.p2').val()), 'confirm');
                } else if ($(this).closest('li').find('.p3').length > 0) {
                    paginate_change('p3', Number($('.p3').val()), 'back');
                } else if ($(this).closest('li').find('.p4').length > 0) {
                    paginate_change('p4', Number($('.p4').val()), 'ship');
                } else if ($(this).closest('li').find('.p5').length > 0) {
                    paginate_change('p5', Number($('.p5').val()), 'cancel');
                }
            });

            $('.p1_first').click(function() {
                paginate_change('p1', 1, 'new');
            });

            $('.p1_prev').click(function() {
                paginate_change('p1', Number('{{ $newOrders->currentPage() }}') - 1, 'new');
            });

            $('.p1_next').click(function() {
                paginate_change('p1', Number('{{ $newOrders->currentPage() }}') + 1, 'new');
            });

            $('.p1_last').click(function() {
                paginate_change('p1', Number('{{ $newOrders->lastPage() }}'), 'new');
            });

            $('.p2_first').click(function() {
                paginate_change('p2', 1, 'confirm');
            });

            $('.p2_prev').click(function() {
                paginate_change('p2', Number('{{ $confirmOrders->currentPage() }}') - 1, 'confirm');
            });

            $('.p2_next').click(function() {
                paginate_change('p2', Number('{{ $confirmOrders->currentPage() }}') + 1, 'confirm');
            });

            $('.p2_last').click(function() {
                paginate_change('p2', Number('{{ $confirmOrders->lastPage() }}'), 'confirm');
            });

            $('.p3_first').click(function() {
                paginate_change('p3', 1, 'back');
            });

            $('.p3_prev').click(function() {
                paginate_change('p3', Number('{{ $backOrders->currentPage() }}') - 1, 'back');
            });

            $('.p3_next').click(function() {
                paginate_change('p3', Number('{{ $backOrders->currentPage() }}') + 1, 'back');
            });

            $('.p3_last').click(function() {
                paginate_change('p3', Number('{{ $backOrders->lastPage() }}'), 'back');
            });

            $('.p4_first').click(function() {
                paginate_change('p4', 1, 'ship');
            });

            $('.p4_prev').click(function() {
                paginate_change('p4', Number('{{ $shippedOrders->currentPage() }}') - 1, 'ship');
            });

            $('.p4_next').click(function() {
                paginate_change('p4', Number('{{ $shippedOrders->currentPage() }}') + 1, 'ship');
            });

            $('.p4_last').click(function() {
                paginate_change('p4', Number('{{ $shippedOrders->lastPage() }}'), 'ship');
            });

            $('.p5_first').click(function() {
                paginate_change('p5', 1, 'cancel');
            });

            $('.p5_prev').click(function() {
                paginate_change('p5', Number('{{ $cancelOrders->currentPage() }}') - 1, 'cancel');
            });

            $('.p5_next').click(function() {
                paginate_change('p5', Number('{{ $cancelOrders->currentPage() }}') + 1, 'cancel');
            });

            $('.p5_last').click(function() {
                paginate_change('p5', Number('{{ $cancelOrders->lastPage() }}'), 'cancel');
            });
        });

        function paginate_change(key = '', value = 1, c = 'new') {
            var p1 = $('.p1').val();
            var p2 = $('.p2').val();
            var p3 = $('.p3').val();
            var p4 = $('.p4').val();
            var p5 = $('.p5').val();

            switch (key) {
                case 'p1':
                    p1 = value;
                    break;
                case 'p2':
                    p2 = value;
                    break;
                case 'p3':
                    p3 = value;
                    break;
                case 'p4':
                    p4 = value;
                    break;
                case 'p5':
                    p5 = value;
                    break;
            }

            var currentLocation = String(window.location);
            var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2 + '&p3=' + p3 + '&p4=' + p4 + '&p5=' + p5 + '&c=' + c;

            if((currentLocation.split('?')[1])) {

                if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                } else {
                    switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2 + '&p3=' + p3 + '&p4=' + p4 + '&p5=' + p5 + '&c=' + c;
                }
                if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                    switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2 + '&p3=' + p3 + '&p4=' + p4 + '&p5=' + p5 + '&c=' + c;
                }
            }
            window.location = switchPageUrl;
        }
    </script>
@stop
