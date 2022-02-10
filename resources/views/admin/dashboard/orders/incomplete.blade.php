@extends('admin.layouts.main')

@section('content')
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th class="width_200p">Last Updated</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Details</th>
                <th>Notification</th>
                <th>Amount</th>
            </tr>
            </thead>

            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ date('F d, Y g:i:s a', strtotime($order['updated_at'])) }}</td>
                    <td>{{ $order['name']}}  </td>
                    <td>{{ $order['email']}}  </td>
                    <td> 
                        @if($order['user_id'])
                            <a class="text-primary" href="{{ route('admin_incomplete_order_detail', ['order' => $order['user_id'] ]) }}">View Detail</a>
                        @endif
                    </td>
                    
                    <td>
                        @if(!empty($order['registered_user']))
                            <a  href="{{ route('admin_incomplete_order_notification', $order['registered_user']) }}" data-userid="{{ $order['registered_user'] }}  @if($order['status'] == 1) style="color:red" @else style="color:#007bff" @endif>
                                <i class="fa fa-bell"></i>
                                @if($order['status'] == 1) Sent @else Send @endif
                            </a>
                        @endif
                    </td>

                    <td>${{ sprintf('%0.2f', $order['total']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination_wrapper p10 pt_0">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $result->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $result->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $result->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $result->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $result->lastPage() }}" class="form_global p1" value="{{ $result->currentPage() }}"> of {{ $result->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $result->currentPage() < $result->lastPage() ?  ' btn_paginate' : ''}}"{{ $result->currentPage() == $result->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $result->currentPage() < $result->lastPage() ?  ' btn_paginate' : ''}}"{{ $result->currentPage() == $result->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>
@stop

@section('additionalJS')
    <script>
        $(function () {
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $('.switch_page').click(function() {
                paginate_change('p1', Number($('.p1').val()))
            });

            $('.p1_first').click(function() {
                paginate_change('p1', 1);
            });

            $('.p1_prev').click(function() {
                paginate_change('p1', Number('{{ $result->currentPage() }}') - 1);
            });

            $('.p1_next').click(function() {
                paginate_change('p1', Number('{{ $result->currentPage() }}') + 1);
            });

            $('.p1_last').click(function() {
                paginate_change('p1', Number('{{ $result->lastPage() }}'));
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
