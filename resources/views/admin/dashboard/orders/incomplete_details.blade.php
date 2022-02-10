@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="ly-row">
        <div class="ly-12">
            <a class="ly_btn btn_blue" href="{{ url()->previous() }}">Back to List</a>
        </div>
    </div>

    <br>

    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th class="product-thumbnail"><b>Image</b></th>
                <th><b>Style No.</b></th>
                <th class="text-center"><b>Color</b></th>
                <th class="text-center"  ><b>Size</b></th>
                <th class="text-center"><b>Total Qty</b></th>
                <th class="text-center"><b>Unit Price</b></th>
                <th class="text-center"><b>Amount</b></th>
            </tr>

            <tbody>
            <?php
            $totalItem = 0;
            $total = 0;
            ?>
            @foreach($allItems as $item_id => $items)
                <tr>
                    <td rowspan="{{ count($items)+1  }}">
                        @if (count($items[0]->item->images) > 0)
                            <img src="{{ Storage::url($items[0]->item->images[0]->thumbs_image_path) }}" alt="Product" height="100px">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" alt="Product" height="100px">
                        @endif
                    </td>

                    <td rowspan="{{ count($items)+1 }}">
                        {{ $items[0]->item->style_no }}
                    </td>

                    <td class="text-center text-lg text-medium">
                        &nbsp;
                    </td>
                    <td>
                        &nbsp;
                    </td>

                    <td>
                        &nbsp;
                    </td>

                    <td>
                        &nbsp;
                    </td>

                    <td>
                        &nbsp;
                    </td>

                </tr>

                @foreach($items as $item)
                    <tr>
                        <td>
                            @if(!empty($item->color))
                                {{ $item->color->name }}
                            @endif
                        </td>
                            <td colspan="#">
                                @if(!empty($item->itemsize))
                                    {{ $item->itemsize->item_size }}
                                @endif
                            </td>
                        <td>
                        <?php $totalItem +=  $item->quantity; ?>
                            <span class="total_qty">{{  $item->quantity }}</span>
                        </td>

                        <td>
                            ${{ sprintf('%0.2f', $item->item->price) }}
                        </td>

                        <td>
                            <?php $total += $item->item->price *  $item->quantity; ?>
                            <span class="total_amount">${{ sprintf('%0.2f', $item->item->price *  $item->quantity) }}</span>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="ly-row" style="margin-top: 20px">
        <div class="ly-9"></div>
        <div class="ly-3">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Total Item</th>
                        <td>{{ $totalItem }}</td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <td>${{ number_format($total, 2, '.', '') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@stop
