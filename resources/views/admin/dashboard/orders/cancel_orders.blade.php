@extends('admin.layouts.main')
@section('additionalCSS')
    <link href="{{ asset('plugins/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet">
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

            <div class="ly-10">
                <button class="ly_btn btn_blue min_width_100p" id="btnApply">Apply</button>
                <button class="ly_btn btn_danger min_width_100p" id="btnReset">Reset All</button>
            </div>
        </div>
    </div>
</div>

<div class="ly_accrodion">
    <div class="ly_accrodion_heading">
        <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#newOrder" class="accordion_heading" data-class="accordion">
            <span>Cancel Orders</span>
        </div>
    </div>
    <div class="accordion_body default_accrodion open" id="newOrder">
        <div class="ly-row">
            <div class="ly-12">
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Order Date</th>
                                <th>Customer Name</th>
                                <th>Total</th>
                                <th>Shipping Method</th>
                                <th># of Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><a class="text-primary" href="{{ route('admin_order_details', ['order' => $order->id]) }}">{{ $order->order_number }}</a></td>
                                    <td>{{ date('F d, Y', strtotime($order->created_at)) }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td>${{ sprintf('%0.2f', $order->total) }}</td>
                                    <td>{{ $order->shipping }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>
                                        <a class="link btnDelete" data-id="{{ $order->id }}" role="button" style="color: red">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination_wrapper p10 pt_0">                        
                        <ul class="pagination">
                            <li><button class="ly_btn p1_first{{ $orders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $orders->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                            <li>
                                <button class="ly_btn p1_prev{{ $orders->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $orders->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                            </li>
                            <li>
                                <div class="pagination_input">
                                    <input type="number" min="1" max="{{ $orders->lastPage() }}" class="form_global p1" value="{{ $orders->currentPage() }}"> of {{ $orders->lastPage() }}
                                </div>
                                <div class="pagination_btn">
                                    <button class="ly_btn switch_page">GO</button>
                                </div>
                            </li>
                            <li><button class="ly_btn p1_next{{ $orders->currentPage() < $orders->lastPage() ?  ' btn_paginate' : ''}}"{{ $orders->currentPage() == $orders->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                            <li>
                                <button class="ly_btn p1_last{{ $orders->currentPage() < $orders->lastPage() ?  ' btn_paginate' : ''}}"{{ $orders->currentPage() == $orders->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                            </li>
                        </ul>
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
<script type="text/javascript" src="{{ asset('plugins/moment/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/js/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
          
            $('#dateRange').daterangepicker({
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

            // Filter 
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
                var type = 9;
                var ship = $('#selectShipStatus').val();
                var date = $('#selectOrderDate').val();
                var startDate = $('#dateRange').data('daterangepicker').startDate.format('MM/DD/YYYY');
                var endDate = $('#dateRange').data('daterangepicker').endDate.format('MM/DD/YYYY');

                var url = '{{ route('admin_orders_according_type') }}' + '?text=' + text + '&search=' + search + '&ship=' + ship +
                    '&date=' + date + '&startDate=' + startDate + '&endDate=' + endDate + '&type=' + type;
                window.location.replace(url);
            });

            $('#btnReset').click(function () {
                var url = '{{ route('admin_cancelled_orders') }}';
                window.location.replace(url);
            });

            $('.switch_page').click(function() {
                paginate_change('p1', Number($('.p1').val()))
            });

            $('.p1_first').click(function() {
                paginate_change('p1', 1);
            });

            $('.p1_prev').click(function() {
                paginate_change('p1', Number('{{ $orders->currentPage() }}') - 1);
            });

            $('.p1_next').click(function() {                
                paginate_change('p1', Number('{{ $orders->currentPage() }}') + 1);
            });

            $('.p1_last').click(function() {
                paginate_change('p1', Number('{{ $orders->lastPage() }}'));
            });
        });

        
        function paginate_change(key = '', value = 1) {     
            var p1 = $('.p1').val();

            switch (key) {
                case 'p1':
                    p1 = value;
                    break;           
            }            
                
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
        }
    </script>
@stop