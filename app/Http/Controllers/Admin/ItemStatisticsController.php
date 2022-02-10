<?php

namespace App\Http\Controllers\Admin;

use DB;
use DateTime;
use Carbon\Carbon;
use App\Model\Item;
use App\Model\Order;
use App\Model\CartItem;
use App\Model\Category;
use App\Model\ItemView;
use App\Model\CartTotal;
use App\Model\OrderItem;
use App\Model\OrderTotal;
use Illuminate\Http\Request;
use App\Enumeration\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemStatisticsController extends Controller
{
    public function index(){

        $categories = Category::where('parent', 0)->orderBy('sort')->orderBy('name')->get();

        return view('admin.dashboard.statistics.index',compact('categories'))->with('page_title', 'Item Statistics');
    }

    public function filter(Request $request){
        $perPage = 15;
        $itemIds = [];
        $totalItems = Item::count();

        // Item Filters
        $where = [];

        if ($request->status != 'all') {
            $where[] = ['status', '=', $request->status];
        }

        if($request->style_no){
            $where[] = ['style_no', '=', $request->style_no];
        }

        // Period Calculation
        $startDate = null;
        $endDate = null;

        if ($request->period != '') {
            $date = $this->getDateFromPeriod($request->period);
            $startDate = $date['start'];
            $endDate = $date['end'];
        } else {
            if ($request->start_date != '') {
                $startDate = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
            }

            if ($request->end_date != '') {
                $endDate = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
            }
        }

        // Filter
        if ($request->type === 'item_activation') {
            if ($startDate) {
                $where[] = ['activated_at', '>=', $startDate];
            }

            if ($endDate) {
                $where[] = ['activated_at', '<=', $endDate];
            }
        }

        $query = Item::with(['itemcategory', 'colors', 'images', 
        'itemOrders' => function($q) use ($request, $startDate, $endDate) {
            if ($request->type === 'order') {
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
    
                if ($endDate) {
                    $q->where('created_at', '<=', $endDate);
                }
            }
        }, 
        'carts' => function($q) use ($request, $startDate, $endDate) {
            if($request->type === 'cart'){
                if ($startDate) {
                    $q->where('updated_at', '>=', $startDate);
                }
    
                if ($endDate) {
                    $q->where('updated_at', '<=', $endDate);
                }
            }
        }, 
        'views' => function ($q) use ($request, $startDate, $endDate) {
            if ($request->type === 'view') {
                if ($startDate) {
                    $q->where('created_at', '>=', $startDate);
                }
    
                if ($endDate) {
                    $q->where('created_at', '<=', $endDate);
                }
            }
        }])->where($where);

        if ($request->category != 'all') {
            $query->whereHas('itemcategories', function($q) use ($request) {
                $q->where('default_parent_category', $request->category);
            });
        }

        $items = $query->get();

        $data = collect([]);

        foreach($items as $item) {
            
            $data->push([
                'id' => $item->id,
                'image' => count($item->images) ? Storage::url($item->images[0]->thumbs_image_path) : null,
                'style_no' => $item->style_no,
                'activation_date' => $item->activated_at,
                'views' => count($item->views),
                'carts' => $item->carts->sum('quantity'),
                'total_quantity' => $item->itemOrders->sum('qty'),
                'total_amount' => $item->itemOrders->sum('amount')
            ]);
        }

        // Sort
        if ($request->sort == '0') {
            $data = $data->sortByDesc('total_amount');
        } elseif($request->sort == '1'){
            $data = $data->sortBy('total_amount');
        } elseif($request->sort == '2'){
            $data = $data->sortByDesc('total_quantity');
        } elseif($request->sort == '3'){
            $data = $data->sortBy('total_quantity');
        } elseif($request->sort == '4'){
            $data = $data->sortByDesc('activation_date');
        } elseif($request->sort == '5'){
            $data = $data->sortBy('activation_date');
        } elseif($request->sort == '6'){
            $data = $data->sortByDesc('views');
        } elseif($request->sort == '7'){
            $data = $data->sortBy('views');
        } elseif($request->sort == '8'){
            $data = $data->sortByDesc('carts');
        } elseif($request->sort == '9'){
            $data = $data->sortBy('carts');
        }
    
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $data = $data->splice(($currentPage-1)*$perPage)->take($perPage)->values();

        $paginatedSearchResults= new LengthAwarePaginator($data, count($items), $perPage, $currentPage);

        $paginatedSearchResults->defaultView('admin.dashboard.others.pagination');

        $paginationView = $paginatedSearchResults->links()->toHtml();

        return ['items' => $paginatedSearchResults, 'pagination' => $paginationView];
    }

    public function getDateFromPeriod($period) {
        if ($period === 'yesterday') {
            return [
                'start' => Carbon::now()->subDays(1)->startOfDay()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->subDays(1)->endOfDay()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'this_week'){
            return [
                'start' => Carbon::parse('last monday')->startOfDay()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'this_month'){
            return [
                'start' => Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'this_year'){
            return [
                'start' => Carbon::now()->startOfYear()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'last_week'){
            return [
                'start' => Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'last_month'){
            return [
                'start' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'last_year'){
            return [
                'start' => Carbon::now()->subYear()->startOfYear()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->subYear()->endOfYear()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'last_7_days'){
            return [
                'start' => Carbon::now()->subDay(7)->startOfDay()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'last_30_days'){
            return [
                'start' => Carbon::now()->subDay(30)->startOfDay()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'last_90_days'){
            return [
                'start' => Carbon::now()->subDay(90)->startOfDay()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
            ];
        }

        if($period === 'last_365_days'){
            return [
                'start' => Carbon::now()->subDay(365)->startOfDay()->format('Y-m-d H:i:s'),
                'end' => Carbon::now()->endOfDay()->format('Y-m-d H:i:s'),
            ];
        }
    }

    function get_quantity_of_color_product(Request $request){
        $specification = Item::where('id',$request->item_id)->select('specification')->first();
        $data = null;
        if($specification->specification == 2){
            $data = OrderItem::with('order')->has('order')->where('item_id',$request->item_id)->groupBy('color')->get();
        }elseif($specification->specification == 3){
            $data = OrderItem::with('order')->has('order')->where('item_id',$request->item_id)->groupBy('size')->get();
        }elseif($specification->specification == 1){
            $data = OrderItem::with('order')->has('order')->where('item_id',$request->item_id)->groupBy('color','size')->get();
        }elseif($specification->specification == 4){
            $data = OrderItem::with('order')->has('order')->where('item_id',$request->item_id)->get();
        }
        $items = [];
        $OrderItems =[];
        foreach ($data as $key => $value) {
            $total_qty = 0;
            $amount = 0;
            $data = null;
            if($specification->specification == 2){
                $OrderItems = OrderItem::with('statistic_color')->has('order')->where('color', $value->color)->where('item_id',$request->item_id)->get();
            }elseif($specification->specification == 3){
                $OrderItems = OrderItem::with('statistic_size')->has('order')->where('size', $value->size)->where('item_id',$request->item_id)->get();
            }elseif($specification->specification == 1){
                $OrderItems = OrderItem::with('statistic_color','statistic_size')->has('order')->where('color', $value->color)->where('size', $value->size)->where('item_id',$request->item_id)->get();
            }elseif($specification->specification == 4){
                $OrderItems = OrderItem::has('order')->where('item_id',$request->item_id)->get();
            }

            foreach ($OrderItems as $item) {
                $total_qty +=  $item->total_qty;
                $amount +=  $item->amount;
            }
            $items [] = [
                'color' =>  $OrderItems[0]->statistic_color ? $OrderItems[0]->statistic_color->name : null,
                'size' => $OrderItems[0]->statistic_size ? $OrderItems[0]->statistic_size->item_size : null,
                'total_qty' => $total_qty,
                'amount' =>  sprintf('%0.2f', $amount)
            ];
        }

        return $items;
    }

    public function stylenoSearch(Request $request){

        $search = $request->search;
        $items = Item::where('style_no', 'like', '%' .$search . '%')->get();
        $total = $items->count();
        $response = [];
        foreach($items as $item){
            $response[] = ["value"=>$item->style_no , 'total'=>$total];
        }
        echo json_encode($response);
    }
}
