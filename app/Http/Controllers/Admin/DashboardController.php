<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\OrderStatus;
use App\Model\Item;
use App\Model\ItemImages;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Visitor;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index() {
        // $homePageVisitor = Visitor::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))->distinct('ip')->count('ip');


        // $homePageVisitorUnique = Visitor::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('today')))
        // ->distinct('ip')->count('ip');

        // $totalSaleAmount = Order::whereIn('status', [OrderStatus::$FULLY_SHIPPED_ORDER])
        //     ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
        //     ->sum('total');

        // $totalPendingOrder = Order::whereIn('status', [OrderStatus::$PARTIALLY_SHIPPED_ORDER, OrderStatus::$BACK_ORDER, OrderStatus::$CONFIRM_ORDER, OrderStatus::$NEW_ORDER])
        //     ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
        //     ->sum('total');

        // $todayOrderAmount = Order::where('status', '!=', OrderStatus::$INIT)
        //     ->whereDate('created_at', date('Y-m-d'))
        //     ->sum('total');
        // $total_customer = 0;

        // $total_customer = User::with('buyer')->where('active',1)->count();

        // $yesterdayVisitor = Visitor::whereDate('created_at', date('Y-m-d', strtotime('-1 days')))
        //     ->distinct()
        //     ->count('ip');

        // $yesterdayOrderAmount = Order::where('status', '!=', OrderStatus::$INIT)
        //     ->whereDate('created_at', date('Y-m-d', strtotime('-1 days')))
        //     ->sum('total');

        // $itemUploadedThisMonth = Item::where('status', 1)
        //     ->where('created_at', '>=', Carbon::now()->startOfMonth())
        //     ->count();

        // $itemUploadedLastMonth = Item::where('status', 1)
        //     ->where('created_at', '>=', new Carbon('first day of last month'))
        //     ->where('created_at', '<=', new Carbon('last day of last month'))
        //     ->count();

        // // Best Selling Items
        // $ids = OrderItem::groupBy('item_id')
        //             ->selectRaw('sum(amount) as sum, item_id')
        //             ->orderBy('sum', 'desc')
        //             ->pluck('item_id')->toarray();

        //  $bestItems = Item::limit(6)->orderBy('total_amount','desc')->whereIn('id', $ids)->with('images')->get();

        // foreach ($bestItems as &$item) {
        //     $image = ItemImages::where('item_id', $item->id)
        //         ->orderBy('sort')
        //         ->first();

        //     $imagePath = asset('images/no-image.png');

        //     if ($image)
        //         $imagePath = asset($image->image_path);

        //     $item->image_path = $imagePath;
        // }

        // // Order Count By Month
        // $startDate = [];
        // $endDate = [];
        // $orderCountLabel = [];
        // $orderCount = [];
        // $returnOrder = [];

        // for($i=5; $i >= 0; $i--) {
        //     $orderCountLabel[] = date('Y/m', strtotime("-$i month"));
        //     $startDate[] = date('Y-m-01', strtotime("-$i month"));
        //     $endDate[] = date('Y-m-t', strtotime("-$i month"));
        // }

        // for($i=0; $i < 6; $i++) {
        //     $orderCount[] = Order::where('status', '!=', OrderStatus::$INIT)
        //         ->where('status', '!=', OrderStatus::$RETURNED)
        //         ->where('status', '!=', OrderStatus::$CANCEL_BY_VENDOR)
        //         ->where('status', '!=', OrderStatus::$CANCEL_BY_BUYER)
        //         ->where('status', '!=', OrderStatus::$CANCEL_BY_AGREEMENT)
        //         ->where('created_at', '>=', $startDate[$i])
        //         ->where('created_at', '<=', $endDate[$i])
        //         ->count();

        //     $returnOrder[] = Order::where('status', '=', OrderStatus::$RETURNED)
        //         ->where('created_at', '>=', $startDate[$i])
        //         ->where('created_at', '<=', $endDate[$i])
        //         ->count();
        // }

        // // Item Upload chart
        // $uploadCount = [];

        // for($i=0; $i < 6; $i++) {
        //     $uploadCount[] = Item::where('status', 1)
        //         ->where('created_at', '>=', $startDate[$i])
        //         ->where('created_at', '<=', $endDate[$i])
        //         ->count();
        // }

        // $data = [
        //     'homePageVisitor' => $homePageVisitor,
        //     'homePageVisitorUnique' => $homePageVisitorUnique,
        //     'totalSaleAmount' => $totalSaleAmount,
        //     'totalPendingOrder' => $totalPendingOrder,
        //     'todayOrderAmount' => $todayOrderAmount,
        //     'yesterdayVisitor' => $yesterdayVisitor,
        //     'yesterdayOrderAmount' => $yesterdayOrderAmount,
        //     'itemUploadedThisMonth' => $itemUploadedThisMonth,
        //     'itemUploadedLastMonth' => $itemUploadedLastMonth,
        //     'orderCountLabel' => json_encode($orderCountLabel),
        //     'orderCount' => json_encode($orderCount),
        //     'returnOrder' => json_encode($returnOrder),
        //     'uploadCount' => json_encode($uploadCount),
        //     'total_customer'=> $total_customer,
        //     // 'orders' => $orders
        // ];

        return view('admin.dashboard.index')->with('page_title', 'Dashboard');
    }

    public function dashboardVisitorTotal(){
        
        $content= [];
        
        $homePageVisitor = DB::table('visitors')->selectRaw('COUNT(DISTINCT ip) as c')
            ->where('route_name', 'home')
            ->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->first();   
        
        $content['home_page_visitor']= $homePageVisitor->c;

        $content = $content['home_page_visitor'];
        
        return number_format($content);
    }

    public function dashboardVisitorUnique(){
        
        $content= [];
        
        $homePageVisitorUnique = DB::table('visitors')->selectRaw('COUNT(DISTINCT ip) as c')
            ->where('route_name', 'home')
            ->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime('today')))
            ->first();
        
        $content['home_page_visitor_unique']= $homePageVisitorUnique->c;

        $content = $content['home_page_visitor_unique'];

        return number_format($content);
    }

    public function dashboardVisitorYesterday(){
        
        $content= [];
        
        $yesterdayVisitor =DB::table('visitors')->selectRaw('COUNT(DISTINCT ip) as c')
            ->where('route_name', 'home')
            ->whereDate('created_at', date('Y-m-d', strtotime('-1 days')))
            ->first();
        
        $content['yesterday_visitor']= $yesterdayVisitor->c;

        $content = $content['yesterday_visitor'];

        return number_format($content);
    }

    public function dashboardInfoData(){
        
        $content= [];

        $totalSaleAmount = Order::select('id', 'status', 'total', 'created_at')->whereIn('status', [OrderStatus::$NEW_ORDER, OrderStatus::$CONFIRM_ORDER, OrderStatus::$BACK_ORDER, OrderStatus::$PARTIALLY_SHIPPED_ORDER, OrderStatus::$FULLY_SHIPPED_ORDER])
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->sum('total');

        $totalPendingOrder = Order::select('id', 'status', 'total', 'created_at')->whereIn('status', [OrderStatus::$PARTIALLY_SHIPPED_ORDER, OrderStatus::$BACK_ORDER, OrderStatus::$CONFIRM_ORDER, OrderStatus::$NEW_ORDER])
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->sum('total');

        $todayOrderAmount = Order::select('id', 'status', 'total', 'created_at')->where('status', '!=', OrderStatus::$INIT)
            ->whereDate('created_at', Carbon::today())
            ->sum('total');
        
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        
        $yesterdayOrderAmount = Order::select('id', 'status', 'total', 'created_at')->where('status', '!=', OrderStatus::$INIT)
                ->whereDate('created_at', $yesterday)
                ->sum('total');

        $itemUploadedThisMonth = Item::select('id', 'status', 'created_at')->where('status', 1)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $itemUploadedLastMonth = Item::select('id', 'status', 'created_at')->where('status', 1)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();
        
        $totalCustomer = User::where('role', '!=', 1)->count();

        $totalSaleAmountData = number_format($totalSaleAmount, 2, '.', ',');
        $totalPendingOrderData = number_format($totalPendingOrder, 2, '.', ',');
        $todayOrderAmountData = number_format($todayOrderAmount, 2, '.', ',');
        $yesterdayOrderAmountData = number_format($yesterdayOrderAmount, 2, '.', ',');
        $itemUploadedThisMonthData = number_format($itemUploadedThisMonth);
        $itemUploadedLastMonthData = number_format($itemUploadedLastMonth);

        $content['total_sale_amount']= $totalSaleAmountData;
        $content['total_pending_order']= $totalPendingOrderData;
        $content['today_order_amount']= $todayOrderAmountData;
        $content['yesterday_order_amount']= $yesterdayOrderAmountData;
        $content['item_upload_this_month']= $itemUploadedThisMonthData;
        $content['item_upload_last_month']= $itemUploadedLastMonthData;
        $content['total_customer']= $totalCustomer;
        
        return $content;
    }

    public function bestSale(){

        $bestSellingItemsSql = "SELECT items.id
            FROM items
            LEFT JOIN (SELECT item_id, SUM(qty) count FROM order_items GROUP BY item_id) t ON items.id = t.item_id
            WHERE items.status = 1
            AND items.activated_at >= (DATE(NOW()) - INTERVAL 30 DAY)
            AND count != 0
            ORDER BY count DESC
            LIMIT 6";

        $bestSellingItemsResult = DB::select($bestSellingItemsSql); 

        $bestSellingItemsIds = []; 
        foreach ($bestSellingItemsResult as $item)
            $bestSellingItemsIds[] = $item->id;

        $bestSellingItemsIdsString = implode(",", $bestSellingItemsIds);
        $bestSellingProductsQuery = Item::query()->select('id', 'style_no');
        $bestSellingProductsQuery->whereIn('id', $bestSellingItemsIds);

        if (count($bestSellingItemsIds) > 0)
            $bestSellingProductsQuery->orderByRaw('FIELD(id,'.$bestSellingItemsIdsString.')');

        $bestSellingProductsQuery->with('images');
        $bestSellingProducts = $bestSellingProductsQuery->get();  
        
        return $bestSellingProducts;
    }

    public function chartOrderCount(){
        
        // Order Count By Month
        $content= [];
        $startDate = [];
        $endDate = [];
        $orderCountLabel = [];
        $returnOrder = [];

        for($i=5; $i >= 0; $i--) {
            $orderCountLabel[] = date('Y/m', strtotime("-$i month"));
            $startDate[] = date('Y-m-01', strtotime("-$i month"));
            $endDate[] = date('Y-m-t', strtotime("-$i month"));
        }

        for($i=0; $i < 6; $i++) {
            $orderCount[] = Order::select('id', 'status', 'created_at')->where('status', '!=', OrderStatus::$INIT)
                ->where('status', '!=', OrderStatus::$RETURNED)
                ->where('status', '!=', OrderStatus::$CANCEL_BY_VENDOR)
                ->where('status', '!=', OrderStatus::$CANCEL_BY_BUYER)
                ->where('status', '!=', OrderStatus::$CANCEL_BY_AGREEMENT)
                ->where('created_at', '>=', $startDate[$i])
                ->where('created_at', '<=', $endDate[$i])
                ->count();

            $returnOrder[] = Order::select('id', 'status', 'created_at')->where('status', '=', OrderStatus::$RETURNED)
                ->where('created_at', '>=', $startDate[$i])
                ->where('created_at', '<=', $endDate[$i])
                ->count();
        }

        
        $content['order_count']= $orderCount;
        $content['order_count_level']= $orderCountLabel;
        $content['return_order']= $returnOrder;
        return $content;
    }

    public function chartItemUpload(){
        
        // Order Count By Month
        $content= [];
        $startDate = [];
        $endDate = [];
        $orderCountLabel = [];
        $uploadCount = [];

        for($i=5; $i >= 0; $i--) {
            $orderCountLabel[] = date('Y/m', strtotime("-$i month"));
            $startDate[] = date('Y-m-01', strtotime("-$i month"));
            $endDate[] = date('Y-m-t', strtotime("-$i month"));
        }

        for($i=0; $i < 6; $i++) {
            $uploadCount[] = Item::select('id', 'status', 'created_at')
                ->where('status', 1)
                ->where('created_at', '>=', $startDate[$i])
                ->where('created_at', '<=', $endDate[$i])
                ->count();
        }

        $content['order_count_level']= $orderCountLabel;
        $content['upload_count']= $uploadCount;
        return $content;
    }
}
