<?php

namespace App\Http\Controllers\Api;

use App\Model\Faq;
use App\Model\SectionHeading;
use App\Model\Statistic;
use DB;
use phpDocumentor\Reflection\Types\Collection;
use Session;
use Carbon\Carbon;
use App\Model\Item;
use App\Model\Meta;
use App\Model\Page;
use App\Model\Size;
use App\Model\Color;
use App\Model\Banner;
use App\Model\ItemInv;
use GuzzleHttp\Client;
use App\Model\Setting;
use App\Model\Category;
use App\Model\HomePage;
use App\Model\ItemSize;
use App\Model\ItemView;
use App\Model\ItemValue;
use App\Rules\Recaptcha;
use App\Enumeration\Role;
use App\Model\ItemImages;
use App\Model\ItemReview;
use App\Model\MetaVendor;
use App\Model\MasterColor;
use App\Model\SocialLinks;
use App\Model\VendorImage;
use App\Mail\ContactUsMail;
use App\Model\ItemCategory;
use App\Model\Notification;
use App\Model\WishListItem;
use App\Model\PrivacyNotice;
use Illuminate\Http\Request;
use App\Enumeration\BannerType;
use App\Enumeration\PageEnumeration;
use App\Enumeration\VendorImageType;
use App\Http\Controllers\Controller;
use App\Model\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    //
    public function HeaderDefaultContent(){
        $contents = [];
        $big_logo_path = '';
        $small_logo_path = '';
        $big = DB::table('settings')->where('name', 'logo-big')->where('status', 1)->first();
        $small = DB::table('settings')->where('name', 'logo-small')->where('status', 1)->first();
        $topNotification = DB::table('settings')->where('name', 'top_notification')->where('status', 1)->first();

        if ($big){
            $big_logo_path = asset($big->value);
        }
        if ($small){
            $small_logo_path = asset($small->value);
        }

        $contents['logo']= $big_logo_path;
        $contents['small_logo']= $small_logo_path;
        $contents['top_notification']= str_replace('<p', '<p class="mb-0"', $topNotification->value);
        $contents['top_notification_color']= $topNotification->desc;

        return response()->json(['content'=>$contents],200);
    }
    public function FooterDefaultContent(){
        $contents = [];
        $black_logo_path = '';
        $black = DB::table('settings')->where('name', 'logo-black')->first();
        $socialLinks = SocialLinks::orderBy('created_at', 'desc')->first();
        if ($black){
            $black_logo_path = asset($black->value);
        }
        $contents['logo']= $black_logo_path;
        return response()->json(['content'=>$contents, 'socialLinks' => $socialLinks],200);
    }

    public function MasterColors()
    {
        $colors = MasterColor::whereHas('colors', function($q) {
            $q->has('items');
        })->get();

        return $colors;
    }

    public function MasterColorsItems($id)
    {
        $items = Item::whereHas('colors', function($q) use ($id){
            $q->where('master_color_id', $id);
        })->with('images', 'brand', 'values', 'colors')->latest()->take(4)->get();

        return $items;
    }

    public function StaticPage($id){

        $content = '';
        $meta = Meta::where('page', $id)->first();
        $black = DB::table('settings')->where('name', 'logo-black')->first();
        $metas = [
            'title'=>$meta ? $meta->title : null,
            'description'=>$meta ? $meta->description : null,
            'image'=> $black ? asset($black->value) : null,
        ];

        $page = Page::where('page_id', $id)->first();

        if ($page) {
            $content = $page->content;
        }

        return response()->json(['content'=>$content, 'metas' => $metas],200);

    }

    public function HomePage(){
        $meta = Meta::where('page', PageEnumeration::$HOME)->first();
        $black = DB::table('settings')->where('name', 'logo-black')->first();
        $content = '';
        $metas = [
            'title'=>$meta ? $meta->title : null,
            'description'=>$meta ? $meta->description : null,
            'image'=> $black ? asset($black->value) : null,
        ];
        $sectionOne = HomePage::where('section_id',1)->first();
        $sectionThree = HomePage::where('section_id',3)->first();
        $sectionFour = HomePage::where('section_id',4)->first();
        $sectionFive = HomePage::where('section_id',5)->first();
        $latestItems = [];
        $ids = VendorImage::where('type', VendorImageType::$HOME_PAGE_ITEMS)->orderBy('sort')->pluck('url')->take(10)->toarray();
        foreach ($ids as $id)
            $latestItems[] = Item::where('id', $id)->with('images')->first();

        $welcomeMsg = null;
        if(!isset($_COOKIE['welcome_popup_fame'])) {
            $setting = Setting::where('name', 'welcome_notification')->first();
            if ($setting && $setting->value != null)
                $welcomeMsg = $setting->value;
            setcookie("welcome_popup_fame", 'sessionexists', time()+3600*24);
        }
        $default_img = Setting::where('name', 'default-item-image')->first();
        $sections = SectionHeading::all();

        $content = [
            'sectionOne' => $sectionOne,
            'sectionThree' => $sectionThree,
            'sectionFour' => $sectionFour,
            'sectionFive' => $sectionFive,
            'latestItems' => $latestItems,
            'welcomeMsg' => $welcomeMsg,
            'metas' => $metas,
            'defaultImage' => $default_img,
            'sectionHeadings' => $sections,
        ];

        return response()->json(['content'=>$content],200);

    }

    public function getInstagramFeeds(Request $request){

        $social_feeds = DB::table('social_feeds')->select('access_token')->where('type','instagram')->get();

        if(count($social_feeds) == 0){
            return $social_feeds = [];
        }
        else if($social_feeds[0]->access_token == ''){
            return $social_feeds = [];
        }else{

            try{
                $access_token = $social_feeds[0]->access_token;
                $client = new Client();

                $longLiveToken = $access_token;

                $instafeedFeed = DB::table('social_feeds')->where('type','instagram')->first();
                $createdDate = $instafeedFeed->created_at;
                $endDate = Carbon::now();

                $to = Carbon::createFromFormat('Y-m-d H:s:i', $createdDate);
                $from = Carbon::createFromFormat('Y-m-d H:s:i', $endDate);
                $diffInDays = $to->diffInDays($from);

                if($diffInDays >= 55) {

                    $longLiveTokenRefresh = $client->get('https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$longLiveToken);
                    $longLiveTokenRefreshRes = json_decode($longLiveTokenRefresh->getBody(), true);

                    $longLiveToken = $longLiveTokenRefreshRes['access_token'];

                    DB::table('social_feeds')->where('type', 'instagram')->update(['created_at' => Carbon::now(), 'access_token' => $longLiveToken]);
                }

                $userRes = $client->get('https://graph.instagram.com/me?fields=id,username,media_count,account_type&access_token='.$longLiveToken);
                $userRes = json_decode($userRes->getBody(), true);

                $mediaRes = $client->get('https://graph.instagram.com/'.$userRes['id'].'/media?fields=id,caption,media_type,children{media_url,media_type,thumbnail_url},media_url,permalink&access_token='.$longLiveToken);

                $social_feeds = json_decode($mediaRes->getBody(), true);

                if(!empty($social_feeds)){
                    Session::put('social_feeds', $social_feeds);
                }

                return $social_feeds;
            }
            catch(Exception $e){
                return $social_feeds =  Session::get('social_feeds');
            }
        }
    }

    public function getValues()
    {
        $values = ItemValue::all();

        return $values;
    }

    public function getSliders()
    {
        $data = [];
        $desktop = Banner::where('type', BannerType::$MINBANNERDESKTOP)->orderBy('sort')->get();
        $small = Banner::where('type', BannerType::$MINBANNERMOBILE)->orderBy('sort')->get();
        if(count($small) == 0)
            $small = $desktop;

        $data['mobile'] = $small;
        $data['desktop']  = $desktop;

        return $data;

    }

    public function getBlogBanner()
    {
        $data['list'] = Banner::where('type', BannerType::$BLOGLISTBANNER)->first();
        $data['single'] = Banner::where('type', BannerType::$BLOGSINGLEBANNER)->first();

        return $data;
    }

    public function getFeatureWidget()
    {
        $data = [];
        $data['top'] = Banner::where('type', BannerType::$FEATURE_WIDGET)->orderBy('sort')->get();
        $data['bottom'] = Banner::where('type', BannerType::$FEATURE_WIDGET_BOTTOM)->orderBy('sort')->get();
        return $data;
    }

    public function getReturnsAndShipping()
    {
        $meta = MetaVendor::where('id', 1)->first();
        return $meta;
    }
    public function privacyNotice()
    {
        $notices = PrivacyNotice::where('status', 1)->get();

        return $notices;
    }
    public function newsLetter()
    {
        $section = SectionHeading::where('section_name', 'great_offer')->first();

        return $section;
    }

    public function HomePageDefaultContent(){
        $HomeMainBanner = VendorImage::where('type', VendorImageType::$MAIN_BANNER)
            ->where('status', 1)
            ->orderBy('sort')
            ->get();
        $ourpicks = VendorImage::where('type', VendorImageType::$SECTION_TWO_BANNER)
            ->where('status', 1)
            ->orderBy('sort')
            ->get();
        $aboutus = Page::where('page_id', PageEnumeration::$ABOUT_US)->first();
        $custom_section = Page::where('page_id', PageEnumeration::$HOME_PAGE_CUSTOM_SECTION)->first();
        $topnotification = Setting::where('name', 'top_notification')->first();
        $sections = SectionHeading::all();
        return response()->json(['mainslider' => $HomeMainBanner, 'ourpicks' => $ourpicks, 'aboutus' => $aboutus, 'sections'=>$sections, 'topnotification' => $topnotification, 'custom_section' => $custom_section], 200);
    }

    public function SearchBlogPost( ){
        $keyword = \Request::get('s');
        if($keyword != NULL){
            $items = Item::with('images')
                ->where("name","LIKE","%$keyword%")
                ->orWhere("style_no","LIKE","%$keyword%")
                ->orWhere("details","LIKE","%$keyword%")
                ->get();
            return response()->json(['items_count' => count($items), 'items' => $items], 200);
        }else{
            return $this->ProductPage();
        }
    }
    public function homenewin(){
        $newArrivalItems = Item::where('status', 1)
            ->orderBy('activated_at', 'desc')
            ->with('images','brand')
            ->limit(10)
            ->get();

        return response()->json(['newin'=>$newArrivalItems,],200);
    }
    public function CategoryPage($catlist){
        $catlist = explode(',', $catlist);
        $parentCategory='';
        $query = Item::query();
        if(isset($catlist[0])){
            $parentCategory = Category::where('slug',$catlist[0])->first();
            $item_category = ItemCategory::where('default_parent_category',$parentCategory->id)->select('item_id')->get();
            $item_id=[];
            foreach($item_category as $cat){
                $item_id[]=$cat->item_id;
            }
        }
        $items = $query->with(  'images' )->where('status',1)->get();

        return response()->json(['items'=>$items],200);
    }
    public function ProductPage(){
        $query = Item::query();
        $items = $query->with(  'images' )->where('status',1)->orderBy('sorting','asc')->get();
        return response()->json(['items_count' => count($items), 'items' => $items], 200);
    }
    public function ProductSingleInfo(Request $request, $slug){
        $userid='';

        if ($this->guard()->check() && $this->guard()->user()->role == Role::$BUYER) {$userid = $this->guard()->id();;}else{$userid = null; }

        $slugCheck = Item::where('slug', $slug)->with('categories', 'images', 'brand', 'values' )->first();
        if (!$slugCheck) {
            return response()->json(['notfound'=> '404' ], 200);
        }
        // setReview
        $totalReviews = ItemReview::where('item_id', $slugCheck->id)->count();
        $rate = ItemReview::where('item_id', $slugCheck->id)->avg('rate');
        $rate = number_format((float)$rate, 2, '.', '');
        $slugCheck->totalReviews = $totalReviews;
        $slugCheck->rate = $rate;
        $firstcolor =   ItemImages::where('sort',1)->where('item_id',$slugCheck->id)->first();
        if(!empty($firstcolor)){
            $firstcolor =   Color::where('id',$firstcolor->color_id)->first();
        }else{
            $firstcolor = null;
        }


        // Breadcrumbs
        $breadcrumbs = [
            [
                'name' => str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($slugCheck->categories[0]->parent_category->name)))),
                'url' => '/'.$slugCheck->categories[0]->parent_category->slug
            ]
        ];

        if ($slugCheck->categories[0]->second_category) {
            $breadcrumbs[] = [
                'name' => str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($slugCheck->categories[0]->second_category->name)))),
                'url' => '/'.$slugCheck->categories[0]->parent_category->slug.'/'.$slugCheck->categories[0]->second_category->slug
            ];
        }

        if ($slugCheck->categories[0]->third_category) {
            $breadcrumbs[] = [
                'name' => str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($slugCheck->categories[0]->third_category->name)))),
                'url' => '/'.$slugCheck->categories[0]->parent_category->slug.'/'.$slugCheck->categories[0]->second_category->slug.'/'.$slugCheck->categories[0]->third_category->slug
            ];
        }

        $slugCheck->capitalizeName = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($slugCheck->name))));
        // $itemsize =     DB::table('item_size')
        //                 ->join('sizes','item_size.size_id','sizes.id')
        //                 ->where('item_size.item_id',$slugCheck->id)
        //                 ->get();

        $itemsize =     DB::table('item_inv')
            ->join('sizes','item_inv.itemsize','sizes.id')
            ->where('item_inv.item_id',$slugCheck->id)
            ->orderBy('item_inv.sort')
            ->get();

        $sizes =     DB::table('item_inv')
            ->join('sizes','item_inv.itemsize','sizes.id')
            ->where('item_inv.item_id',$slugCheck->id)
            ->orderBy('item_inv.sort')
            ->get();

        // $itemcolor =     DB::table('color_item')
        //                 ->join('colors','color_item.color_id','colors.id')
        //                 ->where('color_item.item_id',$slugCheck->id)
        //                 ->get();

        $itemcolor =     DB::table('item_inv')
            ->select(DB::raw('item_inv.color_name, colors.id, group_concat(itemsize SEPARATOR ",") as size_ids'))
            ->join('colors','item_inv.color_id','colors.id')
            ->where('item_inv.item_id',$slugCheck->id)
            ->groupBy('item_inv.color_id')
            ->orderBy('item_inv.sort')
            ->get();

        foreach($itemcolor as $ic) {
            $ic->size_ids = explode(',', $ic->size_ids);
        }

        // if(!empty($firstcolor)){
        //     $itemimages =   ItemImages::where('item_id',$slugCheck->id)->where('color_id',$firstcolor->id)->orderBy('sort')->get();
        // }else{
        //     $itemimages =   ItemImages::where('item_id',$slugCheck->id)->orderBy('sort')->get();
        // }

        $itemimages =   ItemImages::where('item_id',$slugCheck->id)->orderBy('sort')->get();

        $relatedItem = array();

        //$sizechart = Page::where('page_id', PageEnumeration::$SIZE_GUIDE)->first();
        $sizeChart = MetaVendor::first()->size_chart;

        //$returnpage = Page::where('page_id', PageEnumeration::$RETURN_INFO)->first();
        $returnpage = MetaVendor::first()->order_notice;
        $topnotification = Setting::where('name', 'top_notification')->first();

        $ItemCategory = ItemCategory::where('item_id',$slugCheck->id)->first();
        $categoryId = $ItemCategory->default_parent_category;
        $itemsQuery = Item::query();
        $itemsQuery->join('item_categories', 'items.id', '=', 'item_categories.item_id')
            ->select('items.*', 'item_categories.item_id')
            ->with('categories', 'images', 'brand', 'values' )
            ->where('status', 1)
            ->where('items.id', '<>', $slugCheck->id)
            ->groupby('item_categories.item_id')
            ->limit(10);
        $itemsQuery->where('item_categories.default_parent_category', $categoryId);
        $relatedItem = $itemsQuery->get();
        $i=0;
        foreach($relatedItem as $item){
            $relatedItem[$i]['sizes'] =  \DB::table('item_size')
                ->join('sizes','sizes.id','item_size.size_id')
                ->where('item_size.item_id',$item->id)
                ->get();
            $item['colors'] = $item->colors;
            $i++;
        }


        $wishlist  = WishListItem::where([
            ['user_id',$userid],
            ['item_id', $slugCheck->id]
        ])->first();

        $brand = '';
        $brand = \DB::table('brands')->where('id',$slugCheck->brand_id)->first();
        if($slugCheck){
            foreach($slugCheck->images as $image){
                if(!empty($image->compressed_image_path)){
                    $slugCheck->metaimage = asset($image->compressed_image_path);
                    break;
                }
            }
        }
        if($slugCheck->video)
            $slugCheck->video = asset($slugCheck->video);

        $user_id = $this->guard()->id();
        if(!$user_id)
            $user_id = $request->guest_id;

        $statistic = Statistic::where('item_id', $slugCheck->id)->where('user_id', $user_id)->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->first();

        if($statistic) {
            $statistic = new Statistic();
            $statistic->status = 'view';
            $statistic->user_id = $user_id;
            $statistic->item_id = $slugCheck->id;
            $statistic->save();
        }
        return response()->json([
            'product' => $slugCheck,
            'firstcolor' => $firstcolor,
            'colors' => $itemcolor,
            'brand' => $brand,
            'itemsize' => $itemsize,
            'sizes' => $sizes,
            'sizechart' => $sizeChart,
            'images' => $itemimages,
            'returns' => $returnpage,
            'wishlist' => $wishlist,
            'relatedItem' => $relatedItem,
            'default_img' => Setting::where('name', 'default-item-image')->first(),
            'topnotification' => $topnotification,
            'breadcrumbs' => $breadcrumbs
        ], 200);
    }
    public function productView(Request $request, $slug){
        if ($this->guard()->check()) {
            $user_id = $this->guard()->id();
            $user_ip = $request->ip();

            $item = Item::where('slug', $slug)->first();

            if ($item) {
                ItemView::create([
                    'item_id' => $item->id,
                    'user_id' => $user_id,
                    'ip' => $user_ip
                ]);
            }

            return response()->json(200);
        }
    }
    public function recentlyViewed(Request $request){
        $user_id = $this->guard()->id();
        $user_ip = $request->ip();

        $itemIds = ItemView::where('user_id', $user_id)->orWhere('ip', $user_ip)->take(10)->orderBy('created_at', 'desc')->groupBy('item_id')->get()->pluck('item_id')->toArray();

        if(count($itemIds) < 10) {
            $totalCount = count($itemIds);
            $newTake = 10 - $totalCount;
            $cartItemIds = CartItem::take($newTake)->groupBy('item_id')->get()->pluck('item_id')->toArray();
            $itemIds = array_merge($itemIds, $cartItemIds);
        }
        if(count($itemIds) < 10) {
            $totalCount = count($itemIds);
            $newTake = 10 - $totalCount;
            $randomItemIds = Item::take($newTake)->where('status', 1)->groupBy('id')->get()->pluck('id')->toArray();
            $itemIds = array_merge($itemIds, $randomItemIds);
        }
        $recentlyViewed = Item::whereIn('id', $itemIds)->with('categories', 'images', 'sizes', 'brand', 'values', 'colors' )->where('status', 1)->get();
        return response()->json([
            'recentlyViewed' => $recentlyViewed,
        ], 200);
    }

    public function productValues(){
        $productValues = \App\Model\ItemValue::where('status', 1)->orderBy('updated_at', 'desc')->get();
        return response()->json(['productValues' => $productValues],200);
    }

    public function productBrands(){
        $productBrands = \App\Model\Brand::where('status', 1)->orderBy('updated_at', 'desc')->get();
        return response()->json(['productBrands' => $productBrands],200);
    }
    public function guard(){
        return Auth::Guard('api');
    }


    public function submitContactForm(Request $request, Recaptcha $recaptcha){

        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'subject'=>'required',
            'message'=>'required',
            'recaptcha' => ['required', $recaptcha],
        ]);

        $messageData = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'body' => $request->message
        ];

        Mail::to(config('mail.to.admin'))->send(new ContactUsMail($messageData));
        return response()->json(['status' => true, 'message' => 'Success'], 200);
    }

    public function faqContent()
    {
       $faqs = Faq::all();
       return $faqs;
    }

}
