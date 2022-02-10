<?php
use App\Enumeration\Availability;
?>

@extends('admin.layouts.main')

@section('additionalCSS')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="ly_page_wrapper">
    <div class="item_list_search">
        <div class="item_list_search_checkbox">
            <div class="display_inline mr_50">
                <b class="font_16p fw_500">Search</b>
            </div>
            <div class="custom_checkbox mr_20">
                <input type="checkbox" id="searchStyleNo"
                    {{ (request()->get('style') == '1' || request()->get('style') == null) ? 'checked' : '' }}>
                <label for="searchStyleNo">Style No.</label>
            </div>
            <div class="custom_checkbox mr_20">
                <input type="checkbox" id="searchDescription"
                    {{ (request()->get('des') == '1') ? 'checked' : '' }}>
                <label for="searchDescription">Full Description</label>
            </div>
            <div class="custom_checkbox mr_20">
                <input type="checkbox" id="searchItemName"
                    {{ (request()->get('name') == '1') ? 'checked' : '' }}>
                <label for="searchItemName">Item Name</label>
            </div>
        </div>
        <div class="item_list_searchbox m15">
            <div class="display_inline width_350p">
                <input type="text" class="form_global" placeholder="(Use commas(,) for multiple style search)"
                    id="inputText" value="{{ request()->get('text') }}">
            </div>
            <div class="display_inline">
                <button class="ly_btn btn_blue width_100p toggle_item_search" id="btnSearch">Search</button>
            </div>

            <div class="display_inline float_right">
                <div class="cartGlobal">
                    @if($cart_items['total']['total_qty'] > 0)
                        <div class="cart_fixed_bottom">
                            <ul>
                                <li>
                                    <a href="{{route('show_admin_cart')}}"><img src="{{asset('/images/shopping_bag.svg')}}" alt="">
                                        <span>{{$cart_items['total']['total_qty']}}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="item_list_searchbox m15">
            <div class="label_inline width_150p align_top">
                Name Search
            </div>
            <div class="display_inline width_350p">
                <input type="text" id="userName" class="form_global ui-autocomplete-input" value="{{ $userData->first_name ?? ''}}" placeholder="(Use name for name search)" autocomplete="off">
                <input type="hidden" id="current_customer" >
            </div>
            <div class="label_inline width_150p align_top">
                Email Search
            </div>
            <div class="display_inline width_350p">
                <input type="text" id="userEmail" class="form_global" value="{{ $userData->email ?? ''}}" placeholder="(Use Email for Email search)">
            </div>
        </div>
    </div>
</div>
<br>

<div class="ly_accrodion active_item_list">
    <div class="ly_accrodion_heading display_table">
        <div class="ly_accrodion_title accordion_heading open_acc" data-toggle="accordion" data-target="#ActiveItems" data-class="accordion">
            <span>  Active Items - {{ sizeof($activeItems) }} Items</span>
        </div>
    </div>
    <div class="accordion_body default_accrodion open" id="ActiveItems">
        <div class="list_item_content">
            <div class="pagination_wrapper p10 pt_0">
                <div class="display_inline width_150p">
                    <div class="select">
                        <select class="form_global" id="selectSortActiveItems">
                            <option value="0" {{ request()->get('s1') == '0' ? 'selected' : '' }}>Sort Number</option>
                            <option value="3" {{ request()->get('s1') == '3' ? 'selected' : '' }}>Activation Date</option>
                            <option value="1" {{ request()->get('s1') == '1' ? 'selected' : '' }}>Last Update</option>
                            <option value="2" {{ request()->get('s1') == '2' ? 'selected' : '' }}>Upload Date</option>
                            <option value="4" {{ request()->get('s1') == '4' ? 'selected' : '' }}>Price Low to High</option>
                            <option value="5" {{ request()->get('s1') == '5' ? 'selected' : '' }}>Price High to Low</option>
                            <option value="6" {{ request()->get('s1') == '6' ? 'selected' : '' }}>Style No.</option>
                        </select>
                    </div>
                </div>

                <ul class="pagination">
                    <li><button class="ly_btn p1_first{{ $activeItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                    <li>
                        <button class="ly_btn p1_prev{{ $activeItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                    </li>
                    <li>
                        <div class="pagination_input">
                            <input type="number" min="1" max="{{ $activeItems->lastPage() }}" class="form_global p1" value="{{ $activeItems->currentPage() }}"> of {{ $activeItems->lastPage() }}
                        </div>
                        <div class="pagination_btn">
                            <button class="ly_btn switch_page">GO</button>
                        </div>
                    </li>
                    <li><button class="ly_btn p1_next{{ $activeItems->currentPage() < $activeItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == $activeItems->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                    <li>
                        <button class="ly_btn p1_last{{ $activeItems->currentPage() < $activeItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == $activeItems->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                    </li>
                </ul>
            </div>
            <hr>
            <div class="item_list_wrapper p10">
                @foreach($activeItems as $item)
                    <div class="item_list">
                        <div class="item_list_text">
                            <span class="single_img">
                                <a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">
                                    @if (sizeof($item->images) > 0)
                                        <img src="{{ Storage::url($item->images[0]->thumbs_image_path) }}" alt="{{ $item->style_no }}">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="{{ $item->style_no }}">
                                    @endif
                                </a>
                                <a href="{{ route('admin_edit_item', ['item' => $item->id]) }}" onclick="centeredmodal(this.href,'myWindow','1150','800','yes');return false" class="edit"><i class="fas fa-edit"></i></a>
                            </span>
                            <span class="item_list_desc">
                                <h2>@if(!empty($item->itemcategory[0])) {{ $item->itemcategory[0]->parent_category['name'] }} @endif</h2>
                                <h3><a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">{{ $item->style_no }}</a></h3>
                                <h2>
                                    @if ($item->orig_price != null)
                                        <del>${{ number_format($item->orig_price, 2, '.', '') }}</del>
                                    @endif
                                    ${{ number_format($item->price, 2, '.', '') }}
                                </h2>

                                <span class="single_product_desc">
                                    <h3 style="@if(!in_array($item->id ,$selected_items_list) || in_array($item->id ,$selected_items_list)) @endif"><a class="openCartModal link" data-toggle="modal" data-target="#cartModal" data-id="{{$item->id}}">Add To Cart</a></h3>
                                </span>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="modal" data-modal="cartModal">
    <div class="modal_overlay" data-modal-close="cartModal"></div>
    <div class="modal_inner">
        <div class="modal_wrapper modal_470p">
            <div class="item_list_popup">
                <div class="modal_header display_table">
                    <span class="modal_header_title">Add Color and Quantity</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="cartModal"></span>
                    </div>
                </div>
                <div class="modal_content">
                    <div class="ly-wrap-fluid">
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="form_row color_row">
                                    <div class="label_inline required width_150p">
                                        Color
                                    </div>
                                    <div class="form_inline">
                                        <div class="select" id="colorSelect">
                                        </div>
                                    </div>
                                </div>
                                <div class="form_row size_row">
                                    <div class="label_inline required width_150p">
                                        Size
                                    </div>
                                    <div class="form_inline">
                                        <div class="select" id="sizeSelect">
                                        </div>
                                    </div>
                                </div>
                                <div class="form_row">
                                    <div class="label_inline width_150p">
                                        Quantity
                                    </div>
                                    <div class="form_inline">
                                        <input type="text" class="form_global" placeholder="qty" name="qty">
                                    </div>
                                </div>
                            </div>
                            <div class="ly-12">
                                <div class="display_table m15">
                                    <div class="float_right">
                                        <button class="ly_btn btn_danger width_150p " data-modal-close="cartModal">Close</button>
                                        <input type="hidden" id="itemId">
                                        <button class="ly_btn btn_blue width_150p add_to_cart">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('additionalJS')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>

        $('#current_customer').val({{ $userData->id ?? '' }});

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            var itemId = null;

            $('.switch_page').click(function() {

                var p1 = $('.p1').val();
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);

                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;

                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;

                    }


                }

                window.location = switchPageUrl;

            });

            $('.p1_first').click(function() {

                var p1 = 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;

                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;

                    }


                }

                window.location = switchPageUrl;

            });

            $('.p1_prev').click(function() {

                var p1 = <?php echo $activeItems->currentPage(); ?> - 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;

                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;

                    }


                }
                window.location = switchPageUrl;

            });

            $('.p1_next').click(function() {

                var p1 = <?php echo $activeItems->currentPage(); ?> + 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;

                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;

                    }


                }
                window.location = switchPageUrl;

            });

            $('.p1_last').click(function() {

                var p1 = <?php echo $activeItems->lastPage(); ?>;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;

                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;

                    }


                }
                window.location = switchPageUrl;

            });

            $( "#userName" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url:"{{route('admin_name_autocomplete')}}",
                        type: 'post',
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                select: function (event, ui) {
                    $('#userName').val(ui.item.label);
                    $('#userEmail').val(ui.item.value);
                    $('#selectedCustomer').val(ui.item.user_id);
                    $('#current_customer').val(ui.item.user_id);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('set_session') }}",
                        data: {'id': ui.item.user_id},
                        success: function (response) {
                            location.reload();
                        }
                    });

                    return false;
                }
            });

            $( "#userEmail" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url:"{{route('admin_email_autocomplete')}}",
                        type: 'post',
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                select: function (event, ui) {
                    $('#userEmail').val(ui.item.label);
                    $('#userName').val(ui.item.value);
                    $('#selectedCustomer').val(ui.item.user_id);
                    $('#current_customer').val(ui.item.user_id);
                    return false;
                }
            });

            $('#selectSortActiveItems').change(function () {
                checkParameters();
            });

            $('#btnSearch').click(function () {
                search();
            });

            $('#inputText').keypress(function(e) {
                if(e.which == 13) {
                    search();
                }
            });

            $(document).on('click','.openCartModal',function(){
                var targeted_modal_class = 'cartModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');

                var id = $(this).data('id');
                $('#itemId').val(id);

                itemId =id;

                $.ajax({
                    method: "GET",
                    url: "{{ route('cart_item_color') }}",
                    data: {itemId: id }
                }).done(function(res) {
                    if (res.color.length == 0) {
                        $('.color_row').css('display' , 'none');
                    } else {
                        $('.color_row').css('display' , 'table');

                        var output = '<select class="form_global" name="color_id" id="color_id">';
                        output += '<option value="">Select Color</option>';
                        $.each( res.color, function( i, e ){
                            output += '<option value="'+e.color_id+'">'+e.name+'</option>';
                        });
                        output +='</select>';

                        $('#colorSelect').html(output);
                    }

                    if (res.size.length == 0) {
                        $('.size_row').css('display' , 'none');
                    } else {
                        $('.size_row').css('display' , 'table');

                        if (res.color.length == 0) {
                            var output = '<select class="form_global" name="size_id">';
                            output += '<option value="">Select Size</option>';
                            $.each( res.size, function( i, e ){
                                output += '<option value="'+e.size_id+'">'+e.name+'</option>';
                            });
                            output +='</select>';
                        } else {
                            var output = '<select class="form_global" name="size_id"><option value="">Select Size</option></select>';
                        }

                        $('#sizeSelect').html(output);
                    }
                });
            });

            $(document).on('change', '#color_id', function () {
                var colorId = $(this).val();

                $.ajax({
                    url:"{{route('cart_item_size')}}",
                    data: { colorId: colorId, itemId: itemId },
                    success: function( response ) {
                        var output = '<select class="form_global" name="size_id">';
                        output += '<option value="">Select Size</option>';
                        $.each( response, function( i, e ){
                            if (e.size)
                                output += '<option value="'+e.size.item_size+'">'+e.size.item_size+'</option>';
                        });
                        output +='</select>';

                        $('#sizeSelect').html(output);
                    }
                });
            });

            $('.add_to_cart').click(function (e) {
                e.preventDefault();
                var current_customer = $('#current_customer').val();
                if(document.getElementById('current_customer').value == ''){
                    alert('please select a customer');
                    return;
                }
                var actualId = $('#itemId').val();
                var colorId = $("select[name=color_id]").val();
                var sizeId = $("select[name=size_id]").val();
                // if(colorId == ''){
                //     alert('please select a color');
                // }

                // if(sizeId == ''){
                //     alert('please select a Size');
                // }

                var qty = $("input[name=qty]").val();

                if(qty == ''){
                    alert('please input quantity');
                }else{
                    $.ajax({
                        method: "POST",
                        url: "{{ route('cart_item_add') }}",
                        data: {itemId: actualId , qty : qty, colors:colorId, size:sizeId, current_customer:current_customer}
                    }).done(function (data) {
                        if (data.success) {
                            var th = `<div class="cart_fixed_bottom">
                                    <ul>
                                        <li><a href="{{route('show_admin_cart')}}"><img src="{{asset('/images/shopping_bag.svg')}}" alt=""> <span>` + data.count + `</span></a></li>

                                    </ul>

                                </div>`;
                            $('.cartGlobal').html(th);

                            $('#cartModal').hide();
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
                }
            });

            function checkParameters() {
                var s1 = $('#selectSortActiveItems').val();

                var parameters = <?php echo json_encode(request()->all()); ?>;
                var url = '{{ route('admin_new_order_create') }}' + '?s1=' + s1;

                $.each(parameters, function (key, value) {
                    if (key != 's1' && key != 'p1' && key != 'p2') {
                        var val = '';

                        if (value != null)
                            val = value;

                        url += '&' + key + '=' + val;
                    }
                });
                window.location.replace(url);
            }

            function search() {
                var s1 = $('#selectSortActiveItems').val();
                var text = $('#inputText').val();
                var searchStyleNo = ($('#searchStyleNo').is(':checked')) ? 1 : 0;
                var description = ($('#searchDescription').is(':checked')) ? 1 : 0;
                var name = ($('#searchItemName').is(':checked')) ? 1 : 0;

                var url = '{{ route('admin_new_order_create') }}' + '?s1=' + s1 + '&text=' + text + '&style=' + searchStyleNo +
                    '&des=' + description + '&name=' + name;
                window.location.replace(url);
            }
        });
    </script>
@stop
