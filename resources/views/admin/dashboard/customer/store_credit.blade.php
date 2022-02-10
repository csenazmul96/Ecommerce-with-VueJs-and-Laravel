@extends('admin.layouts.main')

@section('additionalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/themify/css/themify-icons.css') }}" />
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th width="25%">SI</th>
                    <th width="25%">Name</th>
                    <th width="25%">Email</th>
                    <th width="25%">Remaining Credit</th>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @foreach($storeCredits as $storeCredit)
            <div class="home_accordion">
                <div class="home_accordion_heading" data-toggle="accordion" data-target="#accordion_{{ $storeCredit->id }}" data-class="accordion">
                    <div class="ly-row">
                        <div class="ly-3">
                            {{ $loop->iteration }}
                        </div>

                        <div class="ly-3">
                            <a data-toggle="collapse" href="#collapse_{{ $storeCredit->id }}" role="button" aria-expanded="true" aria-controls="collapseSearch" class="btnShowHide collapsed">
                                @if(!empty($storeCredit->user))
                                    {{ $storeCredit->user->first_name.' '.$storeCredit->user->last_name }}
                                @endif
                            </a>
                        </div>

                        <div class="ly-3">
                            {{ $storeCredit->user->email }}
                        </div>

                        <div class="ly-3">
                            ${{ number_format($storeCredit->amount, 2, '.', '') }}
                        </div>
                    </div>
                </div>
                <div class="accordion_body default_accrodion" id="accordion_{{ $storeCredit->id }}" style="display: none;">
                    <table class="table">
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                        </tr>

                        @foreach($storeCredit->items as $item)
                            <tr>
                                <td>{{ date('F j, Y', strtotime($item->created_at)) }}</td>
                                <td>
                                    @if ($item->amount > 0)
                                        {{ $item->reason }}
                                    @endif
                                </td>

                                <td>
                                    @if(!empty($item->order->id))
                                        @if ($item->amount < 0)
                                        <a href="{{ route('admin_order_details', ['order' => $item->order->id]) }}" target="_blank">{{ $item->order->order_number }}</a>
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    @if ($item->amount > 0)
                                        <span class="text-success">${{ number_format($item->amount, 2, '.', '') }}</span>
                                    @else
                                        <span class="text-danger">-${{ number_format($item->amount * -1, 2, '.', '') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop

@section('additionalJS')
    <script>
        $(function () {
            $('.btnShowHide').click(function () {
                if ($(this).hasClass('collapsed')) {
                    $(this).closest('.col-md-3').find('.span_icon').html('<i class="ti-arrow-down"></i>');
                } else {
                    $(this).closest('.col-md-3').find('.span_icon').html('<i class="ti-arrow-right"></i>');
                }
            });

            $('.span_icon').click(function () {
                $(this).siblings('.btnShowHide').trigger('click');
            });
        });
    </script>
@stop