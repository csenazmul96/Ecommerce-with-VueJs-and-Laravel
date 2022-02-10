<?php

namespace App\Http\Controllers;

use App\Enumeration\BannerType;
use App\Enumeration\PageEnumeration;
use App\Model\Banner;
use App\Model\Item;
use App\Model\Page;
use App\Model\Category;
use App\Model\BlogPost;
use App\Model\ItemImages;
use App\Model\ItemReview;
use App\Model\Meta;
use App\Model\Setting;
use App\Model\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class HomeController extends Controller
{
    public function paypalReturn(Request $request) {
        dd($request->all());
    }

    public function index(Request $request, $slug = null, $second_slug = null){
        $url = $request->url;
        $ip = $request->ip();
        $metas = [];
        $script = null;
        $price = null;
        $style = null;
        $ogtitle = null;
        $homeMetaObj = Meta::where('page', PageEnumeration::$HOME)->first();
        $title = $homeMetaObj ? $homeMetaObj->title : "Hologram";
        $ogtitle = $homeMetaObj ? $homeMetaObj->title : "Hologram";
        $description = $homeMetaObj ? $homeMetaObj->description : "Hologram";
        $ogdescription = $homeMetaObj ? $homeMetaObj->description : "Hologram";
        $url = url()->current() ; $image = null; $logo = null;
        $rate = null;

        $banner = Banner::where('type', BannerType::$MINBANNERDESKTOP)->orderBy('sort')->first();
        if($banner)
            $logo = asset($banner->image); $image = asset($banner->image);

        if(request()->route()->getName() == 'single_page'){ // if single page =================================================================
            $item = Item::where('slug', $slug)->with('images')->first();
            if($item){
                $image = count($item->images) > 0 ? asset($item->images[0]->thumbs_img) : null;
                $ogtitle = $item->name." Gel Polish";
                $title = $item->name."  Gel Polish | Hologram ";
                $description = "Let’s check ".strip_tags($item->name)." gel nail polish from Hologram Nails Inc, the most dedicated gel polish shop in the USA.";
                $ogdescription = $item->details ? strip_tags($item->details) : null;
                $item->url = $url;
                $price = $item->price;
                $style = $item->style_no;
                $script = $item;
                $rate = ItemReview::where('item_id', $item->id)->avg('rate');
                $rate = number_format((float)$rate, 2, '.', '');
            }

        }elseif ($slug == 'blog' || $slug == 'blog-details'){  // Blog page =================================================================================================
            $banner = null;
            if($slug == 'blog'){
                $banner = Banner::where('type', BannerType::$BLOGLISTBANNER)->first();
                $title = 'Blog - Hologram';
                $ogtitle = 'Blog - Hologram';
                $description = "Let's check the latest blog posts and updates from Hologram.";
                $ogdescription = "Let's check the latest blog posts and updates from Hologram.";
                if($banner)
                    $image = asset($banner->image);
            }
            else{
                $blog = BlogPost::where('slug', $second_slug)->first();

                if($blog){
                    $title = $blog->meta_title;
                    $ogtitle = $blog->meta_title;
                    $description = $blog->meta_description;
                    $ogdescription = $blog->meta_description;
                    $image = asset($blog->image);
                }
            }
        }else{ // others page =================================================================================================
            $pages = ['complete'];
            $meta = null;
            if($second_slug && !in_array($second_slug, $pages)){
                $meta = Category::where('slug', $second_slug)->with('meta')->first();
                if(!$meta) {
//                    $meta = Page::where('slug', $second_slug)->with('meta')->first();
                }else{
                    $title = $meta->meta->title;
                    $ogtitle = $meta->meta->title;
                    $description = $meta->meta->description ;
                    $ogdescription = $meta->meta->description ;
                    if($meta->image)
                        $image = asset($meta->image);
                }
            }else{
                $meta = Category::where('slug', $slug)->with('meta')->first();

                if(!$meta){ //if not category then its page =================================================================================================
                    $pages = ['about-us', 'contact-us', 'terms-conditions', 'privacy-policy', 'shipping-returns', 'faq', 'size-chart'];
                    $pageId = null;
                    if (in_array($slug, $pages)) {
                        // page meta
                        switch ($slug) {
                            case 'about-us':
                                $pageId = 2;
                                break;
                            case 'contact-us':
                                $pageId = 3;
                                break;
                            case 'privacy-policy':
                                $pageId = 4;
                                break;
                            case 'terms-conditions':
                                $pageId = 6;
                                break;
                            case 'shipping-returns':
                                $pageId = 7;
                                break;
                            case 'size-chart':
                                $pageId = 8;
                                break;
                            case 'faq':
                                $pageId = 16;
                                break;
                            default:
                                $pageId = null;
                                break;
                        }
                        $pageMeta = Meta::where('page', $pageId)->first();
                        $title = $pageMeta->title;
                        $ogtitle = $pageMeta->title;
                        $description = $pageMeta->description;
                        $ogdescription = $pageMeta->description;
                    }
                }else{ //Category meta =================================================================================================
                    if($meta){
                        $title = $meta->meta ? $meta->meta->title : null;
                        $ogtitle = $meta->meta ? $meta->meta->title : null;
                        $description = $meta->meta ? $meta->meta->description : null ;
                        $ogdescription = $meta->meta ? $meta->meta->description : null ;
                        $image = asset($meta->image );
                    }
                }
            }
        }

        $this->visitor_count($url, $ip, request()->route()->getName());
        $data = [
            'title'=>$title,
            'description'=>$description,
            'image'=>$image,
            'url'=>$url,
            'logo'=>$logo,
            'price'=>$price,
            'style'=>$style,
            'ogtitle'=>$ogtitle,
            'ogdescription'=>$ogdescription,
        ];

//        $metas = $this->metaGenerator($title, $description, $url, $image, $logo, $price);
        $metas = $this->metaGenerator($data);


        return view('layouts.frontend', compact('metas', 'script', 'rate'));
    }

    public function visitor_count($url = null, $ip = null, $route_name = null)
    {
        $visitor = Visitor::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->where('ip', $ip)
            ->first();

        if (!$visitor) {
            $visitor = new Visitor();
            $visitor->url = $url;
            $visitor->route_name = $route_name;
            $visitor->user_id = Auth::check() ? Auth::user()->id : null;
            $visitor->ip = $ip;
            $visitor->save();
        }
    }

    public function metaGenerator($data){
        return $metas = [
            [
                'name'=>'title',
                'content'=> $data['title']
            ],
            [
                'name'=>'description',
                'content'=> $data['description'],
            ],
            [
                'property'=>'fb:app_id',
                'content'=> env('FACEBOOK_ID'),
            ],
            [
                'property'=>'og:description',
                'content'=> $data['ogdescription'],
            ],
            [
                'property'=>'og:title',
                'content'=> $data['ogtitle'],
            ],
            [
                'property'=>'og:site_name',
                'content'=> 'Hologram',
            ],
            [
                'property'=>'twitter:card',
                'content'=> $data['title'],
            ],
            [
                'property'=>'og:type',
                'content'=> 'product',
            ],
            [
                'property'=>'og:url',
                'content'=> $data['url'],
            ],
            [
                'name'=> 'twitter:title',
                'content'=> $data['title'],
            ],
            [
                'name'=>'twitter:description',
                'content'=> $data['description'],
            ],
            [
                'property'=>'twitter:url',
                'content'=> $data['url'],
            ],
            [
                'rel' => 'canonical',
                'href'=> $data['url'],
            ],
            [
                'name' => 'twitter:image',
                'content'=> $data['image'],
            ],
            [
                'property' => 'product:price:amount',
                'content'=> $data['price'],
            ],
            [
                'property' => 'product:price:currency',
                'content'=> 'USD',
            ],
            [
                'property' => 'product:retailer_item_id',
                'content'=>  $data['style'],
            ],
            [
                'property' => 'og:image',
                'content'=> $data['image'],
            ]
        ];
    }


}
