<?php use App\Enumeration\OrderStatus; ?>

@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="Statistics-up">
    <div class="Statistics-content">
        <span class="remove-Statistics"></span>
        <table class="table">
            <thead>
                <tr>
                    <th  class="width-150 cursor-pointer">Color <i  class="fg-icon"></i></th>
                    <th  class="width-150 cursor-pointer">Size <i  class="fg-icon"></i></th>
                    <th  class="width-60 cursor-pointer">Qty <i  class="fg-icon"></i></th>
                    <th  class="width-80 cursor-pointer">Amount <i  class="fg-icon"></i></th>
                </tr>
            </thead>
            <tbody id="qty_popup">

            </tbody>
        </table>
    </div>
    <div class="Statistics-overlay"></div>
 </div>
<div class="ly_card">
    <div class="ly_card_heading">
        <h5 class="mb-0"> Filters</h5>
    </div>
    <div class="ly_card_body">
        <div class="ly-wrap">
            <div class="ly-row">
                <div class="ly-12">

                    <form id="search-form">
                        <div class="ly-row" style="margin-bottom: 25px;">
                            <div class="ly-12">
                                <div class="form-group ly-row item_list_search">
                                    <div class="ly-2">
                                        <label><b>Search By</b></label>
                                        <select class="form_global" id="type" name="type">
                                            <option value="" selected >Select Option</option>
                                            <option value="item_activation" >Item Activation</option>
                                            <option value="view">View</option>
                                            <option value="cart" >Cart</option>
                                            <option value="order" >Order(Check Out)</option>
                                        </select>
                                    </div>
                                    <div class="ly-2">
                                        <label><b>Period</b></label>
                                        <select class="form_global" name="period" id="period">
                                            <option value="" selected>Input Period</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="this_week" >This Week</option>
                                            <option value="this_month" >This Month</option>
                                            <option value="this_year" >This Year</option>
                                            <option value="last_week" >Last Week</option>
                                            <option value="last_month" >Last Month</option>
                                            <option value="last_year" >Last Year</option>
                                            <option value="last_7_days" >Last 7 Days</option>
                                            <option value="last_30_days" >Last 30 Days</option>
                                            <option value="last_90_days" >Last 90 Days</option>
                                            <option value="last_365_days" >Last 365 Days</option>
                                        </select>
                                    </div>
                                    <div class="ly-4">
                                        <label><b>Custom Period</b></label>
                                        <div class="filter_statistic_padding date-data">
                                            <input type="text" name="start_date" id="start_date" placeholder="MM/DD/YYYY">
                                            <label>to</label>
                                            <input type="text" name="end_date" id="end_date" placeholder="MM/DD/YYYY">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly-row">
                            <div class="ly-2">
                                <label><b>Category</b></label>
                                <select class="form_global" id="category" name="category">
                                    <option value="all" selected>All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @if (sizeof($category->subCategories) > 0)
                                            @foreach($category->subCategories as $sub)
                                                <option value="{{ $sub->id }}">- {{ $sub->name }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="ly-2">
                                <label><b>Style No.</b></label>
                                <input type="text" class="form_global" placeholder="Enter Style No." id="style_no" name="style_no" value="">
                            </div>
                            <div class="ly-2">
                                <label><b>Items</b></label>
                                <select class="form_global" id="status" name="status">
                                    <option value="all" selected>All</option>
                                    <option value="1">Active</option>
                                    <option value="0" >Inactive</option>
                                </select>
                            </div>
                            <div class="ly-2 mt_15">
                                <div class="filter_statistic_padding">
                                    <button class="ly_btn btn_blue" id="btnApply">Apply</button>
                                </div>
                            </div>
                            <div class="ly-4 mt_15">
                                <div class="filter_statistic_padding text_right ">
                                    <a href="" id="reset_form" class="ly_btn btn_blue">Reset All</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ly_card">
    <div class="ly_card_heading">
        <h5 class="mb-0"> Item Statistics</h5>
    </div>
    <div class="ly_card_body">
        <div class="ly-wrap">
            <div class="ly-row">
                <div class="ly-12">
                    <div class="pagination_wrapper p10 pt_0">
                        <label class="mr-2">Sort By :</label>
                        <div class="display_inline width_250p">
                            <input type="hidden" id="sort" value="0">
                            <div class="select">
                                <select class="form_global" autocomplete="off" id="sorting">
                                    <option value="0" selected>Total Amount High to Low</option>
                                    <option value="1">Total Amount Low to High</option>
                                    <option value="2">Total Quantity High to Low</option>
                                    <option value="3">Total Quantity Low to High</option>
                                    <option value="4">Activate Newest</option>
                                    <option value="5">Activate Oldest</option>
                                    <option value="6">Views High</option>
                                    <option value="7">Views Low</option>
                                    <option value="8">Cart High</option>
                                    <option value="9">Cart Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                </div>
                <div class="ly-12">
                    <table class="table table-bordered statistics-table">
                        <thead>
                            <tr class="list_item">
                                <th>#</th>
                                <th>Image</th>
                                <th>Style No</th>
                                <th id="total_activation_sort"><span>Activation Date</span> </th>
                                <th id="total_view_sort"><span>Views</span></th>
                                <th id="total_cart_sort"> <span>In Cart</span></th>
                                <th>Color</th>
                                <th id="total_quantity_sort"> <span>Total Quantity</span></th>
                                <th id="total_amount_sort" class="active show_arrow"> <span>Total Amount</span> </th>
                            </tr>
                        </thead>

                        <tbody id="st-data">

                        </tbody>
                    </table>

                    <div class="pagination_wrapper p10 pt_0">
                        <ul class="pagination">
                            <li><button class="ly_btn p1_first" onclick="changePage('first')">| <i class="fas fa-chevron-left"></i></button></li>
                            <li>
                                <button class="ly_btn p1_prev" onclick="changePage('prev')"> <i class="fas fa-chevron-left"></i> PREV</button>
                            </li>
                            <li>
                                <div class="pagination_input">
                                    <input type="number" min="1" max="1" class="form_global p1" value="1"> of <span class="p1_total">1</span>
                                </div>
                                <div class="pagination_btn">
                                    <button class="ly_btn switch_page" onclick="changePage('page')">GO</button>
                                </div>
                            </li>
                            <li><button class="ly_btn p1_next" onclick="changePage('next')">  NEXT <i class="fas fa-chevron-right"></i></button></li>
                            <li>
                                <button class="ly_btn p1_last" onclick="changePage('last')"> <i class="fas fa-chevron-right"></i> |</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/moment/js/moment.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/js/daterangepicker.js') }}"></script>
    <script type="text/javascript">
        var base_url =  "{{URL::to('/')}}";
        var page = 1;
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var url = window.location
            page = getURLParameter(url, 'page');
            itemFilter(page);

            $('#start_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: "bottom left"
            });

            $('#end_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: "bottom left"
            });

            $('#period').change(function (e) {
                if($(this).val() != ''){
                    $('.date-data').css('display','none');
                }else{
                    $('.date-data').css('display','block');
                }
            });

            $('#reset_form').click(function (e) {
                $('#search-form').reset();
                e.preventDefault();
            });

            $('#btnApply').click(function (e) {
                itemFilter(page);
                e.preventDefault();
            });

            var asort = 1;
            $('#total_amount_sort').click(function (e) {
                e.preventDefault();
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                $(this).siblings().removeClass('show_arrow');
                if(asort == 1){
                    $('#sort').val(1);
                    asort = 0;
                    $(this).removeClass('show_arrow');
                }else{
                    $('#sort').val(0);
                    asort = 1;
                    $(this).addClass('show_arrow');
                }
                itemFilter(page);
            });

            var tqsort = 1;
            $('#total_quantity_sort').click(function (e) {
                e.preventDefault();
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                $(this).siblings().removeClass('show_arrow');
                if(tqsort == 1){
                    $('#sort').val(2);
                    tqsort = 0;
                    $(this).addClass('show_arrow');
                }else{
                    $('#sort').val(3);
                    tqsort = 1;
                    $(this).removeClass('show_arrow');
                }
                itemFilter(page);
            });

            var acsort = 1;
            $('#total_activation_sort').click(function (e) {
                e.preventDefault();
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                $(this).siblings().removeClass('show_arrow');
                if(acsort == 1){
                    $('#sort').val(4);
                    acsort = 0;
                    $(this).addClass('show_arrow');
                }else{
                    $('#sort').val(5);
                    acsort = 1;
                    $(this).removeClass('show_arrow');
                }
                itemFilter(page);
            });

            var tvsort = 1;
            $('#total_view_sort').click(function (e) {
                e.preventDefault();
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                $(this).siblings().removeClass('show_arrow');
                if(tvsort == 1){
                    $('#sort').val(6);
                    tvsort = 0;
                    $(this).addClass('show_arrow');
                }else{
                    $('#sort').val(7);
                    tvsort = 1;
                    $(this).removeClass('show_arrow');
                }
                itemFilter(page);
            });

            var tcsort = 1;
            $('#total_cart_sort').click(function (e) {
                e.preventDefault();
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                $(this).siblings().removeClass('show_arrow');
                if(tcsort == 1){
                    $('#sort').val(8);
                    tcsort = 0;
                    $(this).addClass('show_arrow');
                }else{
                    $('#sort').val(9);
                    tcsort = 1;
                    $(this).removeClass('show_arrow');
                }
                itemFilter(page);
            });

            $('#sorting').change(function (e) {
                $('#sort').val($(this).val());
                itemFilter(page);
                e.preventDefault();
            });

            // pagination
            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                page = getURLParameter(url, 'page');

                itemFilter(page);
            });

            function getURLParameter(url, name) {
                return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
            }

            $( "#style_no" ).autocomplete({
                source: function( request, response ) {
                $.ajax({
                    url:"{{route('stylenoSearch')}}",
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
                    $('#style_no').val(ui.item.label);
                    return false;
                }
            });

        });

        $(document).on('click','.col-img img', function (e) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: "{{ route('qty_c_products') }}",
                data: {item_id:id},
                success: function (response) {
                    $('#qty_popup').html('');
                    $.each(response, function (i, res) {
                        if(res.color && res.size){
                            var data ='<tr><td>'+res.color+'</td><td>'+res.size+'</td><td>'+res.total_qty+'</td><td>'+res.amount+'</td></tr>';
                        }else if(res.color){
                            var data ='<tr><td>'+res.color+'</td><td>-</td><td>'+res.total_qty+'</td><td>'+res.amount+'</td></tr>';
                        }else if(res.size){
                            var data ='<tr><td>-</td><td>'+res.size+'</td><td>'+res.total_qty+'</td><td>'+res.amount+'</td></tr>';
                        }else{
                            var data ='<tr><td>-</td><td>-</td><td>'+res.total_qty+'</td><td>'+res.amount+'</td></tr>';
                        }
                        $('#qty_popup').append(data);
                    });
                }
            });
            $(".Statistics-up").addClass("open-Statistics");
        });

        $(".remove-Statistics").click(function() {
                $(".Statistics-up").removeClass("open-Statistics");
            });
        $(".Statistics-overlay").click(function() {
            $(".Statistics-up").removeClass("open-Statistics");
        });


        function changePagination(paginateData) {
            $('.p1').val(paginateData.current_page);
            $('.p1').attr('max', paginateData.last_page);
            $('.p1_total').html(paginateData.last_page);

            if(paginateData.current_page > 1) {
                $('.p1_first').addClass('btn_paginate');
                $('.p1_prev').addClass('btn_paginate');
            }

            if(paginateData.current_page == 1) {
                $('.p1_first').attr('disabled', true);
                $('.p1_prev').attr('disabled', true);
            } else {
                $('.p1_first').attr('disabled', false);
                $('.p1_prev').attr('disabled', false);
            }

            if(paginateData.current_page < paginateData.last_page) {
                $('.p1_next').addClass('btn_paginate');
                $('.p1_last').addClass('btn_paginate');
            }

            if(paginateData.current_page == paginateData.last_page) {
                $('.p1_next').attr('disabled', true);
                $('.p1_last').attr('disabled', true);
            } else {
                $('.p1_next').attr('disabled', false);
                $('.p1_last').attr('disabled', false);
            }
        }

        function changePage(type = 'page') {
            var page = Number($('.p1').val());
            var total = Number($('.p1_total').html());
            switch (type) {
                case 'first':
                    page = 1;
                    break;
                case 'prev':
                    page = page - 1;
                    break;
                case 'next':
                    page = page + 1;
                    break;
                case 'last':
                    page = total;
                    break;
            }
            // window.location.search = 'page='+page;
            itemFilter(page);
        }

        function itemFilter(page) {
            var sl = 1;
            var type = $('#type option:selected').val();
            var period = $('#period').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var category = $('#category').val();
            var style_no = $('#style_no').val();
            var status = $('#status').val();
            var sort = $('#sort').val();
            if((type.length == 0) && (period.length > 0 || start_date.length>0 || end_date.length>0)){
                $('#type').val("item_activation");
                type = 'item_activation';
            }

            $.ajax({
                type: "POST",
                url: "{{ route('item_statistics_filter') }}",
                data:  { type:type, period:period, start_date:start_date, end_date:end_date, category:category, style_no:style_no, status:status,
                        sort:sort,page: page },
                success: function (response) {

                    $('#st-data').html('');

                    page = typeof page !== 'undefined' ? page : 1;

                    changePagination(response.items);

                    if(response.items.data){
                        var table_datas = response.items.data;
                    }

                    $.each(table_datas, function (i, table_content) {

                        var activation_date ='';
                        var image_path = (table_content.image) ? table_content.image : '';
                        var views = (table_content.views != null) ? table_content.views : 0;
                        var incart = (table_content.carts != null) ? table_content.carts : 0;
                        var total_qty = (table_content.total_quantity != null) ? table_content.total_quantity : 0;
                        var amount = (table_content.total_amount != null) ? table_content.total_amount : 0;

                        var row = '<tr>' +
                            '<td class="sl">'+(sl++)+'</td>' +
                            '<td class="image"><img src="'+ image_path +'"></td>' +
                            '<td class="style"><a target="_blank" href="'+base_url+'/admin/item/edit/'+table_content.id +'">'+table_content.style_no+'</a></td>'+
                            '<td class="ac-date"> '+table_content.activation_date+'</td>'+
                            '<td class="v-count">'+views+'</td>'+
                            '<td class="incart">'+incart+'</td>'+
                            '<td class="col-img">'+(total_qty > 0 ? '<img src="'+base_url+'/images/color.png" id='+table_content.id+'>': '') +'</td>' +
                            '<td class="t-qty">'+total_qty+'</td>' +
                            ' <td class="amount">'+parseFloat(amount).toFixed(2)+'</td>'+
                            '</tr>';
                        $('#st-data').append(row);
                    });

                }
            });
        }
    </script>
@stop
