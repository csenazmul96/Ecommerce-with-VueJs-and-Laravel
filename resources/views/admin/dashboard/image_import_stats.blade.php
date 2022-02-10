@extends('admin-v2.layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                <tr>
                    <th>Style No</th>
                    <th>Status</th>
                </tr>
                </thead>

                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item['styleno'] }}</td>
                        <td class="status">Pending</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('additionalJS')
    <script>
        var items = <?php echo json_encode($items) ?>;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function asyncFunction(item, i) {
            return $.ajax({
                url: '{{ route('admin_data_import_image') }}',
                type: 'POST',
                data : item
            }).then(function(data){
                if (data.success)
                    $('.status:eq('+i+')').html('<span class="color_green">'+data.message+'</span>');
                else
                    $('.status:eq('+i+')').html('<span class="color_red">'+data.message+'</span>');
            });
        }

        function sequence(arr, callback) {
            var i=0;

            var request = function(item) {
                return callback(item, i).then(function(){
                    if (i < arr.length-1)
                        return request(arr[++i]);
                });
            }

            return request(arr[i]);
        }

        sequence(items, asyncFunction).then(function(){
            console.log('ALl complete');
        });
    </script>
@stop