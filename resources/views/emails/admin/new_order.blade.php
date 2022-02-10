<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
</style>
<body>
<div style="margin: 20px auto; max-width: 750px;font-family: 'Karla', sans-serif;">
    <table style="table-layout: fixed;">
        <tr>
            <td style="text-align: center;">
                @if(!empty($logo))
                    <img src="{{ $logo }}" alt="" style="width: 250px;">
                @endif
                <h1 style="margin-top: 30px; margin-bottom: 40px; font-size: 30px; text-transform: uppercase; font-weight: 500;">Order Confirmation</h1>
                <p style="text-align: left; font-weight: 400;"> <span style="font-weight: 300;">Dear</span>  Admin !!<br>
                    You've got an order from Hologram. Order number is ( #{{ $order ? $order->order_number : '' }}). Please deliver the order as soon as possible. </p>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <h2 style="text-align: center; position: relative; width: 100%; padding-bottom: 10px; border-bottom: 2px solid #000; margin-top: 10px; margin-bottom: 0; font-size: 25px;">ORDER SUMMARY <span style="position: absolute; right: 0; font-weight: 300; font-size: 14px; top: 5px;"> #{{ $order->order_number }} </span></h2>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <table style="width: 100%; margin-bottom: 30px;">
                    <tr>
                        <td style="width: 33.3333%; padding: 0px 20px 0px 10px; vertical-align: top;">
                            <p style="margin-bottom: 3px; font-weight: 300;">Shipping To :</p>
                            <p style="font-weight: 400; margin-top: 0;">{{ $order->user->first_name }} {{ $order->user->last_name }}
                                {{ $order->billing_address }},
                                @if ($order->billing_unit && $order->billing_unit != '')
                                    #{{ $order->billing_unit }},
                                @endif

                                {{ $order->billing_city }}, <br>
                                @if(!empty($order->billingState->state->code)){{ $order->billingState->state->code }} @else {{ $order->billing_state }} @endif - {{ $order->billing_zip }},
                                {{ $order->billing_country }}
                            </p>
                        </td>

                        <td style="width: 33.3333%; padding: 0px 20px 0px 10px; vertical-align: top;">
                            <p style="margin-bottom: 3px; font-weight: 300;">Billing Address</p>
                            <p style="font-weight: 400; margin-top: 0;">{{ $order->user->first_name }} {{ $order->user->last_name }}
                                {{ $order->billing_address }},
                                @if ($order->billing_unit && $order->billing_unit != '')
                                    #{{ $order->billing_unit }},
                                @endif

                                {{ $order->billing_city }}, <br>
                                @if(!empty($order->billingState->state->code)){{ $order->billingState->state->code }} @else {{ $order->billing_state }} @endif - {{ $order->billing_zip }},
                                {{ $order->billing_country }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <table style="font-weight: 300; width: 100%; text-align: left;">
                    <tr>
                        <th style="border-bottom:1px solid #EFEFEE; padding-bottom: 10px; font-weight: 300; width: 10%;">Image</th>
                        <th style="border-bottom:1px solid #EFEFEE; padding-bottom: 10px; font-weight: 300; width: 40%;">Name</th>
                        <th style="border-bottom:1px solid #EFEFEE; padding-bottom: 10px; font-weight: 300; width: 10%;">Style No</th>
                        <th style="border-bottom:1px solid #EFEFEE; padding-bottom: 10px; font-weight: 300; width: 20%;">Qty</th>
                        <th style="border-bottom:1px solid #EFEFEE; padding-bottom: 10px; font-weight: 300;  width: 20%; text-align: right; padding-right: 25px;">Price</th>
                    </tr>

                    @foreach($order->items as $item)
                        <?php
                        $thumb = null;

                        for($i=0; $i < count($item->item->images); $i++) {

                            if ($item->item->images[$i]->color_id != null) {
                                if ($item->item->images[$i]->color->name == $item->color_name) {
                                    $thumb = $item->item->images[$i];
                                    break;
                                }else{
                                    $thumb = $item->item->images[0];
                                }
                            }else{
                                $thumb = $item->item->images[0];
                            }
                        }
                        ?>
                        <tr>
                            <td style="border-bottom:1px solid #EFEFEE; ">
                                <div class="product_img">
                                    @if ($thumb)
                                        <img src="{{ Storage::url($thumb->thumbs_image_path) }}" alt="" style="float: left; width: 70px; margin-right: 15px;">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="" style="float: left; width: 70px; margin-right: 15px;">
                                    @endif
                                </div>
                            </td>

                            <td style="border-bottom:1px solid #EFEFEE; ">{{ $item->item->name }}</td>
                            <td style="border-bottom:1px solid #EFEFEE; ">{{ $item->style_no }}</td>
                            <td style="border-bottom:1px solid #EFEFEE; ">{{ $item->qty }}</td>
                            <td style="border-bottom:1px solid #EFEFEE; text-align: right; padding-right: 25px;">${{ sprintf('%0.2f', $item->per_unit_price) }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            <table style="width: 100%; font-weight: 300;">
                                <tr>
                                    <td style="width: 70%;">Subtotal:</td>
                                    <td style="width: 30%; text-align: right; padding-right: 25px;">${{ sprintf('%0.2f', $order->subtotal) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                    <tr>
                                        <td style="width: 70%;">Discount:</td>
                                        <td style="width: 30%; text-align: right; padding-right: 25px; color: red;">-${{ sprintf('%0.2f', $order->discount) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="width: 70%;">Shipping Fee:</td>
                                    <td style="width: 30%; text-align: right; padding-right: 25px;">${{ sprintf('%0.2f', $order->shipping_cost) }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 70%;">Total:</td>
                                    <td style="width: 30%; text-align: right; padding-right: 25px; font-weight: 600;">${{ sprintf('%0.2f', $order->total) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-weight: 300; border-top: 2px solid #000; padding-top: 20px;">Best Regards</p>
                <p style="font-weight: 300; margin-bottom: 30px;">Hologram Nails Inc.  </p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
