<?php use App\Enumeration\OrderStatus; ?>

@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet">
@stop

@section('content')


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
                            @foreach($res as $order)

                            @endif
                            </tbody>
                        </table>


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
        });
    </script>
@stop
