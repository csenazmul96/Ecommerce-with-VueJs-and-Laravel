<!DOCTYPE html>
<html>
    <head>
        <title>Without Image</title>

        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
            }
            .table-bordered, .table-bordered th, .table-bordered > td {
                border: 1px solid black;
                border-collapse: collapse;
                font-size: 12px;
            }

            .table-bordered th, .table-bordered td {
                padding: 3px;
            }

            .page-break {
                page-break-after: always;
            }

            .style_availabledate p{
                font-size:8px;
                padding:0px;
                margin:0px;
            }
        </style>
    </head>
    <body style="width: 100%; height: 500px;">
        @foreach($orders as $order)
            <div class="top">
                <div class="top-left" style="width: 348px; display: inline-block">
                    <div class="logo" style="padding:0; margin:0;">
                        @if ($logo_paths[$loop->index] != '')
                            <img src="{{ $logo_paths[$loop->index] }}" width="230"> <br><br>
                        @endif
                    </div>
                    <div class="compaddress" style="font-size:13px;">
                        {{ $order->vendor->billing_address }}<br>
                        {{ ' '.$order->vendor->billing_city }}
                        @if ($order->vendor->billingState == null)
                            {{ $order->vendor->billing_state }},
                        @else
                            {{ $order->vendor->billingState->name }},
                        @endif
                        {{ $order->vendor->billingCountry->name.' - '.$order->vendor->billing_zip }}
                    </div>
                </div>

                <div class="top-right" style="width: 348px; display: inline-block; vertical-align: top; margin-bottom:10px">
                    <table style="font-size: 13px;text-align: left; border: 1px solid black; width: 100%">
                        <tr>
                            <th style="background:#efefef;">Order No.</th>
                            <td>{{ $order->order_number }}</td>
                        </tr>

                        <tr>
                            <th style="background: #efefef;">Order Date</th>
                            <td>{{ date('F d, Y h:i:s a', strtotime($order->created_at)) }}</td>
                        </tr>

                        <tr>
                            <th style="background:#efefef;">Invoice No.</th>
                            <td>{{ $order->invoice_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <br>

            <div class="address" style="margin-top: 10px;">
                <div class="billing" style="width: 348px; display: inline-block">
                    <div class="header" style="border: 1px solid black; padding: 5px;font-size:15px; background:#efefef;">
                        Billing Information
                    </div>

                    <div class="address-body" style="font-size:12px; border: 1px solid black; padding: 5px; border-top: none">
                        <table style="width: 100%; text-align: left">
                            <tr>
                                <td>Contract:</td>
                                <td>{{ $order->b_user_name }}</td>
                            </tr>

                            <tr>
                                <td>Address:</td>
                                <td>
                                    @if($order->billing_location)
                                        {{ $order->billing_location }},
                                    @endif

                                    @if($order->billing_address && $order->billing_state && $order->billing_city  && $order->billing_zip)
                                        {{ $order->billing_address }},
                                    @endif

                                    @if ($order->billing_unit && $order->billing_unit != '')
                                        #{{ $order->billing_unit }},
                                    @endif

                                    @if($order->billing_city)
                                        {{ $order->billing_city }}, <br>
                                    @endif

                                    @if($order->billing_state && $order->billing_zip)
                                    {{ $order->billing_state }} - {{ $order->billing_zip }},
                                    @endif

                                    @if($order->billing_country)
                                        {{ $order->billing_country }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>Phone:</td>
                                <td>{{ $order->billing_phone }}</td>
                            </tr>

                            <tr>
                                <td>Email:</td>
                                <td>{{ $order->email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="billing" style="width: 348px; display: inline-block">
                    <div class="header" style="border: 1px solid black; padding: 5px;font-size:15px; background:#efefef;">
                        Shipping Information
                    </div>

                    <div class="address-body" style="font-size:12px;border: 1px solid black; padding: 5px; border-top: none">
                        <table style="width: 100%; text-align: left">

                            <tr>
                                <td>Contract:</td>
                                <td>{{ $order->s_user_name }}</td>
                            </tr>

                            <tr>
                                <td>Address:</td>
                                <td>
                                    @if($order->shipping_location)
                                        {{ $order->shipping_location }},
                                    @endif

                                    @if($order->shipping_address && $order->shipping_state && $order->shipping_city  && $order->shipping_zip)
                                        {{ $order->shipping_address }},
                                    @endif

                                    @if ($order->shipping_unit && $order->shipping_unit != '')
                                        #{{ $order->shipping_unit }},
                                    @endif

                                    @if($order->shipping_city)
                                        {{ $order->shipping_city }}, <br>
                                    @endif

                                    @if($order->shipping_state && $order->shipping_zip)
                                    {{ $order->shipping_state }} - {{ $order->shipping_zip }},
                                    @endif

                                    @if($order->shipping_country)
                                        {{ $order->shipping_country }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>Phone:</td>
                                <td>{{ $order->shipping_phone }}</td>
                            </tr>

                            <tr>
                                <td>Email:</td>
                                <td>{{ $order->email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="payment" style="width: 700px;">
                <table style="width: 100%" class="table-bordered">
                    <tr>
                        <th>PAYMENT TERMS</th>
                        <th>SHIP VIA</th>
                        <th>TRACKING NO.</th>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            @if(empty($order->payment_id))
                                @if (strlen($order->card_number) > 5)
                                    {{ str_repeat("*", (strlen($order->card_number) - 4)).substr($order->card_number,-4,4) }}
                                @endif
                            @else
                                Captured From Paypal
                            @endif
                        </td>
                        <td style="text-align: center">{{ $order->shipping }}</td>
                        <td style="text-align: center">{{ $order->tracking_number }}</td>
                    </tr>
                </table>
            </div>

            <div class="items" style="width: 700px; margin-top: 5px;">
                <table class="table-bordered" style="width: 100%; text-align: center">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Style No.</th>
                            <th class="text-center">Total Qty</th>
                            <th class="text-center">Unit Price</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($all_items[$loop->index] as $item_id => $items)
                            <tr>
                                <td class="style_availabledate">
                                    {{ $items[0]->item->name }} <br>
                                </td>
                                <td class="style_availabledate">
                                    {{ $items[0]->item->style_no }} <br>

                                    @if($items[0]->item->available_on != null)
                                        <p>   Available On : {{ $items[0]->item->available_on }} </p>
                                    @endif
                                </td>
                                <td>
                                    <table style="width: 100%; text-align: center">
                                        <tbody>
                                            @foreach($items as $item)
                                                <tr>
                                                    <td>
                                                        <span class="total_qty">{{ $item->total_qty }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>

                                <td>
                                    <table style="width: 100%; text-align: center">
                                        <tbody>
                                            @foreach($items as $item)
                                                <tr>
                                                    <td>
                                                        ${{ sprintf('%0.2f', $item->per_unit_price) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>

                                <td>
                                    <table style="width: 100%; text-align: center">
                                        <tbody>
                                            @foreach($items as $item)
                                                <tr>
                                                    <td>
                                                        <span>${{ sprintf('%0.2f', $item->amount) }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="calculation" style="width: 698px; margin-top: 5px; border: 1px solid black">
                <table style="font-size:12px;margin-left: 390px">
                    <tr>
                        <th style="width: 100px;text-align: right;padding-right:15px;">Subtotal:</th>
                        <td style="width: 50px">${{ sprintf('%0.2f', $order->subtotal) }}</td>
                    </tr>

                    @if($order->discount > 0)
                        <tr>
                            <th style="width: 100px;text-align: right;padding-right:15px;">Discount:</th>
                            <td style="width: 50px">${{ sprintf('%0.2f', $order->discount) }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th style="width: 100px;text-align: right;padding-right:15px;">Shipping Cost:</th>
                        <td style="width: 50px">${{ sprintf('%0.2f', $order->shipping_cost) }}</td>
                    </tr>

                    <tr>
                        <th style="width: 100px;text-align: right;padding-right:15px;">Total:</th>
                        <td style="width: 50px">${{ sprintf('%0.2f', $order->total) }}</td>
                    </tr>
                </table>
            </div>
            <div class="returnpolicy" style="width: 698px;margin-top:30px;border: 1px solid black;">
                <div class="returntitle" style="padding:5px 10px;background: #efefef;border-bottom: 1px solid black;">Return policy</div>
                <div calss="policy" style="padding-left:10px;">
                    <p style="font-size:11px; padding:10px;">{!! $privacy_policy  !!}</p>
                </div>
            </div>

            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </body>
</html>
