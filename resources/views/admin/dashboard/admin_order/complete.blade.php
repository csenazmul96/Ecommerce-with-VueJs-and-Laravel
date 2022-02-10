@extends('admin.layouts.main')

@section('content')
    <section class="shipping_cart_area">
        <h3>Order is Completed</h3>
        <p>
            <b>Order Number is: </b> {{ $order->order_number }}&nbsp;
        </p>
    </section>
@stop