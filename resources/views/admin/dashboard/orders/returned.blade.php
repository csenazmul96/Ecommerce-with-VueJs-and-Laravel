@extends('admin.layouts.main')
@section('additionalCSS')
    <link href="{{ asset('plugins/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet">
    <style>
        
    </style>
@stop
@section('content')
<div class="ly_accrodion">
    <div class="ly_accrodion_heading">
        <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#newOrder" class="accordion_heading" data-class="accordion">
            <span> Returned Orders</span>
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
                    $('#date-range').removeClass('d-none');
                } else {
                    $('#date-range').addClass('d-none');
                }
            });

            $('#selectOrderDate').trigger('change');

            $('#btnApply').click(function () {
                var text = $('#inputText').val();
                var search = $('#searchItem').val();
                var type = 10;
                var ship = $('#selectShipStatus').val();
                var date = $('#selectOrderDate').val();
                var startDate = $('#dateRange').data('daterangepicker').startDate.format('MM/DD/YYYY');
                var endDate = $('#dateRange').data('daterangepicker').endDate.format('MM/DD/YYYY');

                var url = '{{ route('admin_orders_according_type') }}' + '?text=' + text + '&search=' + search + '&ship=' + ship +
                    '&date=' + date + '&startDate=' + startDate + '&endDate=' + endDate + '&type=' + type;
                window.location.replace(url);
            });

            $('#btnReset').click(function () {
                var url = '{{ route('admin_returned_orders') }}';
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