@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
    </style>
@stop

@section('content')
    <div class="ly-row">
        <div class="ly-1">
            <label>Mail Content</label>
        </div>

        <div class="ly-11">
            <textarea name="page_editor" id="page_editor" rows="10" cols="10">
                <div style="margin-left: auto; margin-right: auto; max-width: 750px;font-family: 'Karla', sans-serif;">
                    <table style="table-layout: fixed;">
                        <tr>
                            <td style="text-align: center;">
                                @if(!empty($black_logo_path))
                                    <img src="{{ $black_logo_path }}" alt="" style="width: 250px;">
                                @endif
                                <h2 style="margin-top: 30px; margin-bottom: 10px; font-size: 20px; text-transform: uppercase; font-weight: 500;">Incomplete Order</h2>
                            </td>
                        </tr>
            
                        <tr>
                            <td style="width: 100%;padding-left: 20px;">
                                <p style="text-align: left; font-weight: 500; margin-top: 40px; margin-bottom: 10px;"> <span style="font-weight: 300;">Dear</span>   {{ $cartUser->user->first_name }} {{ $cartUser->user->last_name }},</p>
                                <h1 style="margin-top: 0px; font-size: 20px; font-weight: 500;">Do you still want these?</h1>
                                <p style="font-weight: 300;">If you have any questions, feel free to contact us.</p>
                                <p style="font-weight: 500; margin-bottom: 30px;"><span style="font-weight: 300;">Best,</span>  <br> Shop Hologram </p>
                            </td>
                        </tr>
        
                        <tr>
                            <td style="width: 100%;">
                                <h2 style="text-align: center; width: 100%; padding-top: 20px; padding-bottom: 15px; border-top: 2px solid #000; margin-top: 10px; margin-bottom: 0; font-size: 20px; ">Best Sellers</h2>
                                <table style="width: 100%; margin-bottom: 0px;">
                                    @if(count($cartItems) > 0)
                                        @php
                                            $chunkCartItems = array_chunk($cartItems, 3, true);
                                        @endphp

                                        @foreach($chunkCartItems as $chunkItem)
                                            <tr>
                                                @if(count($chunkItem) > 0)
                                                    @foreach($chunkItem as $item)
                                                        <td style="width: 25%; padding: 0px 5px; text-align: center;">
                                                            @if (count($item['item']['images']) > 0)
                                                                <img src="{{ Storage::url($item['item']['images'][0]['compressed_image_jpg_path']) }}" alt="" style="width:100%">
                                                            @else
                                                                <img src="{{ asset('images/no-image.png') }}" alt="" style="width:100%">
                                                            @endif
        
                                                            <p style="margin-top: 0; font-weight: 300;">{{ $item['item']['style_no'] }}</p>
                                                            <p style="margin-top: 0; font-weight: 300;">${{ sprintf('%0.2f', $item['item']['price']) }}</p>
                                                        </td>
                                                    @endforeach
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                <p style="font-weight: 300; margin-bottom: 20px; border-top: 2px solid #000; padding-top: 50px;">Contact Us</p>
                                <p style="font-weight: 300; margin-bottom: 10px;"> <span style="margin-right: 10px;">Shop Hologram</span> 3761 S Hill St #1, Los Angeles, CA 90007, USA</p>
                                <p style="font-weight: 300;"> COPYRIGHT © 2021 SHOPHOLOGRAM • SITE BY SHOPHOLOGRAM.COM</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </textarea>
        </div>
    </div>

    <br>

    <div class="ly-row">
        <div class="ly-1">
            <label class="col-form-label">Sender</label>
        </div>

        <div class="ly-11">
            <input type="email" id="sender" value="info@shophologram.com" name="sender" class="form_global">
        </div>
    </div>

    <br>
    <div class="ly-row">
        <div class="ly-1">
            <label class="col-form-label">Recipient</label>
        </div>
        <div class="ly-11">
            <input type="email" id="recipient" value="{{ $cartUser->user->email }}" name="recipient" class="form_global">
        </div>
    </div>

    <br>
    <div class="ly-row">
        <div class="ly-1">
            <label class="col-form-label">Subject</label>
        </div>
        <div class="ly-11">
            <input name="subject" id="subject" value="[SHOPHOLOGRAM] You have items in your cart." class="form_global">
        </div>
    </div>

    <input type="hidden" name="user_id" id="userId" value="{{ $cartUser->user_id }}">

    <br>

    <div class="row">
        <div class="ly-12 text_right">
            <a class="ly_btn btn_blue" href="{{ url()->previous() }}">Back to List</a>
            <button class="ly_btn btn_blue" id="btnSentNotification">Send</button>
        </div>
    </div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            var options = {
                filebrowserImageBrowseUrl: '{{ url('laravel-filemanager') }}?type=Images',
                filebrowserImageUploadUrl: '{{ url('laravel-filemanager') }}type=Images&_token=',
                filebrowserBrowseUrl: '{{ url('laravel-filemanager') }}?type=Files',
                filebrowserUploadUrl: '{{ url('laravel-filemanager') }}?type=Files&_token='
            };

            CKEDITOR.config.extraAllowedContent = '*{*}';

            CKEDITOR.replace( 'page_editor', {
                height: 500,
            } );

            $('#btnSentNotification').click(function () {
                var userId =  $('#userId').val();
                var recipient =  $('#recipient').val();
                var subject =  $('#subject').val();
                var sender =  $('#sender').val();
                var mailbody =   CKEDITOR.instances['page_editor'].getData();
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_incomplete_order_send_mail') }}",
                    data: {sender: sender,recipient:recipient,subject:subject,mailbody:mailbody, userId: userId}
                }).done(function (msg) {
                    toastr.success('Successfully Sent');
                });
            });
        });
    </script>
@stop