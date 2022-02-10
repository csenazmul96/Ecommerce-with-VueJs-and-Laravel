@extends('admin.layouts.main')

@section('content')
    <div class="ly_card">
        <div class="ly_card_heading">
            <h1>My Performance</h1>
        </div>

        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly">
                        <div class="additional_stats_wrapper center_text">
                            <p>Last 30 Days</p>
                            <div class="additional_stats_content">
                                <div id="homePageVisitor">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Home Page Visitor </p>
                            </div>
                        </div>
                    </div>
                    <div class="ly">
                        <div class="additional_stats_wrapper center_text">
                            <p>Today(Unique)</p>
                            <div class="additional_stats_content">
                                <div id="homePageVisitorUnique">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Home Page Visitor </p>
                            </div>
                        </div>
                    </div>
                    <div class="ly">
                        <div class="additional_stats_wrapper center_text">
                            <p>Last 30 Days</p>
                            <div class="additional_stats_content">
                                <div id="totalSaleAmount">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Total Sale Amount</p>
                            </div>
                        </div>
                    </div>
                    <div class="ly">
                        <div class="additional_stats_wrapper center_text">
                            <p>Last 30 Days</p>
                            <div class="additional_stats_content">
                                <div id="totalPendingOrder">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Total Pending Order</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ly_card">
        <div class="ly_card_heading">
            <h1>Additional Stats</h1>
        </div>
        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div class="ly">
                        <div class="additional_stats_wrapper center_text">
                            <p>Today</p>
                            <div class="additional_stats_content">
                                <div id="todayOrderAmount">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Total Order Amt</p>
                            </div>
                        </div>
                    </div>
                    <div class="ly-5">
                        <div class="additional_stats_wrapper a_stats_two_row clearfix center_text">
                            <p>Yesterday</p>

                            <div class="additional_stats_content">
                                <div id="yesterdayVisitor">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Visitors</p>
                            </div>
                            <div class="additional_stats_content">
                                <div id="yesterdayOrderAmount">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Total Order Amt</p>
                            </div>
                        </div>
                    </div>
                    <div class="ly">
                        <div class="additional_stats_wrapper center_text">
                            <p>This Month</p>
                            <div class="additional_stats_content">
                                <div id="itemUploadedThisMonth">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <p>Items Uploaded</p>
                            </div>
                        </div>
                    </div>
                    <div class="ly">
                        <div class="additional_stats_wrapper no_border center_text">
                            <p>Total Customer</p>
                            <div class="additional_stats_content">
                                <div id="totalCustomer">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <a href="{{ route('admin_all_buyer') }}"><p>Total User</p></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ly_card">
        <div class="ly_card_heading">
            <h1>Top 6 Styles Sold</h1>
        </div>
        <div class="ly_card_body">
            <div class="ly-wrap-fluid">
                <div class="ly-row">
                    <div id="bestSellingItems" class="display_flex">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ly_card">
        <div class="ly_card_heading">
            <h1>Total Order</h1>
        </div>
        <div class="ly_card_body">

            <div class="ly-row">
                <div class="ly-6">
                    <h4>Total Order Count by Month</h4>
                    <hr>

                    <canvas id="orderCount" height="200">
                        <i class="fa fa-spinner fa-spin"></i>
                    </canvas>
                </div>

                <div class="ly-6">
                    <h4>Item Uploaded by Month</h4>
                    <hr>

                    <canvas id="itemUpload" height="200">
                        <i class="fa fa-spinner fa-spin"></i>
                    </canvas>
                </div>
            </div>
        </div>
    </div>
@stop


@section('additionalJS')
    <script src="{{ asset('plugins/Chart.js/Chart.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            DashBoardInfoData();
            function DashBoardInfoData(){
                $.ajax({
                    url: 'dashboard/info/data',
                    method: "GET",
                    data: {}
                }).done(function( data ) {
                    var totalSaleAmount = '';
                    var totalPendingOrder = '';
                    var todayOrderAmount = '';
                    var yesterdayOrderAmount = '';
                    var itemUploadedThisMonth = '';
                    var itemUploadedLastMonth = '';
                    var totalCustomer = '';
                    
                    var totalSaleAmountData = data.total_sale_amount;
                    var totalPendingOrderData = data.total_pending_order;
                    var todayOrderAmountData = data.today_order_amount;
                    var yesterdayOrderAmountData = data.yesterday_order_amount;
                    var itemUploadedThisMonthData = data.item_upload_this_month;
                    var itemUploadedLastMonthData = data.item_upload_last_month;
                    var totalCustomerData = data.total_customer;
                    
                    totalSaleAmount += '<h2>'+ '$' + totalSaleAmountData + '</h2>';
                    totalPendingOrder += '<h2>'+ '$' + totalPendingOrderData + '</h2>';
                    todayOrderAmount += '<h2>'+ '$' + todayOrderAmountData + '</h2>';
                    yesterdayOrderAmount += '<h2>'+ '$' + yesterdayOrderAmountData + '</h2>';
                    itemUploadedThisMonth += '<h2>' + itemUploadedThisMonthData + '</h2>';
                    itemUploadedLastMonth += '<h2>' + itemUploadedLastMonthData + '</h2>';
                    totalCustomer += '<h2>' + totalCustomerData + '</h2>';
    
                    $("#totalSaleAmount").empty();
                    $("#totalSaleAmount").append(totalSaleAmount)
                    
                    $("#totalPendingOrder").empty();
                    $("#totalPendingOrder").append(totalPendingOrder)
                    
                    $("#todayOrderAmount").empty();
                    $("#todayOrderAmount").append(todayOrderAmount)
                    
                    $("#yesterdayOrderAmount").empty();
                    $("#yesterdayOrderAmount").append(yesterdayOrderAmount)
                    
                    $("#itemUploadedThisMonth").empty();
                    $("#itemUploadedThisMonth").append(itemUploadedThisMonth)
                    
                    $("#itemUploadedLastMonth").empty();
                    $("#itemUploadedLastMonth").append(itemUploadedLastMonth)

                    $("#totalCustomer").empty();
                    $("#totalCustomer").append(totalCustomer)
                });
            }
            
            DashBoardTotalVisitor();
            function DashBoardTotalVisitor(){
                $.ajax({
                    url: 'dashboard/visitor/total',
                    method: "GET",
                    data: {}
                }).done(function( data ) {
                    var homePageVisitor = '';
                    
                    var homePageVisitorData = data;
                    
                    homePageVisitor += '<h2>' + homePageVisitorData + '</h2>';
                    
                    $("#homePageVisitor").empty();
                    $("#homePageVisitor").append(homePageVisitor)
                });
            }
            
            DashBoardUniqueVisitor();

            function DashBoardUniqueVisitor(){
                $.ajax({
                    url: 'dashboard/visitor/unique',
                    method: "GET",
                    data: {}
                }).done(function( data ) {
                    var homePageVisitorUnique = '';
                    
                    var homePageVisitorUniqueData = data;
                    
                    homePageVisitorUnique += '<h2>' + homePageVisitorUniqueData + '</h2>';
                    
                    
                    $("#homePageVisitorUnique").empty();
                    $("#homePageVisitorUnique").append(homePageVisitorUnique)
                
                });
            }
            
            DashBoardVisitorYesterday();

            function DashBoardVisitorYesterday(){
                $.ajax({
                    url: 'dashboard/visitor/yesterday',
                    method: "GET",
                    data: {}
                }).done(function( data ) {
                    var yesterdayVisitor = '';
                    
                    var yesterdayVisitorData = data;
                    
                    yesterdayVisitor += '<h2>' + yesterdayVisitorData + '</h2>';
                
                    $("#yesterdayVisitor").empty();
                    $("#yesterdayVisitor").append(yesterdayVisitor)
                });
            }
            
            BestSellingItem();

            function BestSellingItem(){
                $.ajax({
                    url: 'best/sale',
                    method: "GET",
                    data: {}
                }).done(function( data ) {
                    var url = "{{URL::to('/')}}";
                    var bestSale = '';

                    $.each(data, function (i, e) { 
                        var image_path = '';
 
                        if(e.images.length > 0){
                            image_path = url + '/storage/' + e.images[0].compressed_image_jpg_path; 
                        }else{
                            image_path = url + '/images/no-image.jpg'; 
                        }
                        
                        bestSale +=`<div class="ly-2">
                                        <div class="home_product">
                                            <a href="${url+'/admin/item/edit/'+e.id}"> 
                                                <img src="${image_path}" alt="" class="img_mx_width"> 
                                            </a>
                                        </div>
                                        <a href="${url+'/admin/item/edit/'+e.id}">${e.style_no}</a>
                                    </div>`  
                    });
                    $("#bestSellingItems").empty();
                    $("#bestSellingItems").append(bestSale)
                });
            }
            
            chartOrderCountData();
            function chartOrderCountData(){
                $.ajax({
                    url: 'chart/order/count',
                    method: "GET",
                    data: {}
                }).done(function( data ) {
                    var chartOrderCount = document.getElementById("orderCount").getContext('2d');
                    var chartOrderCount = new Chart(chartOrderCount, {
                        type: 'bar',
                        data: {
                            labels: data.order_count_level,
                            datasets: [{
                                label: 'New',
                                data: data.order_count,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor:  'rgba(255,99,132,1)',
                                borderWidth: 1
                            },
                                {
                                    label: 'Return',
                                    data: data.return_order,
                                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                    borderColor: 'rgba(255, 206, 86, 1)',
                                    borderWidth: 1
                                }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true,
                                        max: Math.max(Math.max(...data.order_count) + 5, Math.max(...data.return_order) + 5),
                                    }
                                }]
                            }
                        }
                    });
                });
            }
            
            itemUploadData();
            function itemUploadData(){
                $.ajax({
                    url: 'chart/item/upload',
                    method: "GET",
                    data: {}
                }).done(function( data ) {
                        var ctx = document.getElementById("itemUpload").getContext('2d');
                        var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.order_count_level,
                            datasets: [{
                                data: data.upload_count,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor:  'rgba(255,99,132,1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true,
                                        max: Math.max(...data.upload_count) + 5
                                    }
                                }]
                            }
                        }
                    });
                });
            }
        });
    </script>
@stop
