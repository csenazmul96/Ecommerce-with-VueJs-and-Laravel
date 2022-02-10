<!DOCTYPE html>
<html>
<head>
    <title></title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .table-bordered, .table-bordered th, .table-bordered td {
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
    </style>
</head>

<body style="width: 100%; height: 500px;">
    <div class="top">
        <div class="top-right" style="width: 348px; display: inline-block; vertical-align: top;">
            <table style="font-size: 13px;text-align: left; border: 1px solid black; width: 100%">
                @foreach($orders as $order)
                <tr>
                    <th style="background:#efefef;">Order No.</th>
                    <td>{{ $order }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    @foreach($items as $category => $products)
        <div class="items" style="width: 700px; margin-top: 5px;">
            <table class="table-bordered" style="width: 100%; text-align: center">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Style No</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($products as $product)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ sizeof($products) }}">{{ $category }}</td>
                            @endif
                            <td>{{ $product['style_no'] }}</td>
                            <td>{{ $product['price'] }}</td>
                            <td>{{ $product['quantity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>