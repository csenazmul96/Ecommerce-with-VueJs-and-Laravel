<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\PageEnumeration;
use App\Enumeration\SliderType;
use App\Enumeration\VendorImageType;
use App\Model\Category;
use App\Model\Item;
use App\Model\SliderItem;
use App\Model\TopBanner;
use App\Model\VendorImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Uuid;
use File;
use DB;

class NotificationBannerController extends Controller
{
    public function index(Request $request) {
        $categories = Category::where('parent', 0)->get();
        $banners = TopBanner::all();
        $records = VendorImage::where('type','9')->get();

        return view('admin.dashboard.marketing_tools.notification_banner.index', compact('categories','banners','records'))->with('page_title', 'Notification Banner');
    }

    public function addPost(Request $request)
    {
        $status = $request->status;

        $records= VendorImage::where('type','9')->get();
        if(count($records) != 0)
        {
            $records = json_decode($records);
            $id = $records[0]->id;
            if ($request->photo)
            {
                $file = $request->file('photo');
                $filename = Uuid::generate()->string;
                $ext = $file->getClientOriginalExtension();

                $destinationPath = '/images/notification/banner';
                $file->move(public_path($destinationPath), $filename.".".$ext);
                $imagePath = $destinationPath."/".$filename.".".$ext;

                VendorImage::where('id',$id)->update([
                    'type' => VendorImageType::$NOTIFICATION_BANNER,
                    'image_path' => $imagePath,
                    'status' => $status,
                    'details' => $request->link
                ]);
            }
            else
            {
                VendorImage::where('id',$id)->update([
                    'type' => VendorImageType::$NOTIFICATION_BANNER,
                    'image_path' => "",
                    'status' => $status,
                    'details' => $request->link
                ]);
            }
        }
        else
        {
            if ($request->photo)
            {
                $file = $request->file('photo');
                $filename = Uuid::generate()->string;
                $ext = $file->getClientOriginalExtension();

                $destinationPath = '/images/notification/banner';
                $file->move(public_path($destinationPath), $filename.".".$ext);
                $imagePath = $destinationPath."/".$filename.".".$ext;

                VendorImage::create([
                    'type' => VendorImageType::$NOTIFICATION_BANNER,
                    'image_path' => $imagePath,
                    'status' => 1,
                    'details' => $request->link
                ]);
            }
            else
            {
                 VendorImage::create([
                    'type' => VendorImageType::$NOTIFICATION_BANNER,
                    'image_path' => "",
                    'status' => 1,
                    'details' => $request->link
                ]);
            }
        }

        return redirect()->back()->with('message', 'Successfully Added!');
    }

    public function delete(Request $request) {
        $image = VendorImage::where('id', $request->id)->first();
        File::delete(public_path($image->image_path));
        $image->delete();
    }

    public function active(Request $request) {
        VendorImage::where('type', $request->type)->update(['status' => 0]);
        VendorImage::where('id', $request->id)->update(['status' => 1]);
    }

    public function uploadFile($file, $type,$details) {
        $filename = Uuid::generate()->string;
        $ext = $file->getClientOriginalExtension();

        $destinationPath = '/images/notification/banner';
        $file->move(public_path($destinationPath), $filename.".".$ext);
        $imagePath = $destinationPath."/".$filename.".".$ext;

        VendorImage::create([
            'type' => $type,
            'image_path' => $imagePath,
            'status' => 1,
            'details' => $details
        ]);
    }

    public function bannerItems() {
        $items = Item::where('status', 1)
            ->get();

        $mainSliderItems = SliderItem::where('type', SliderType::$MAIN_SLIDER)
            ->orderBy('sort')
            ->with('item')
            ->get();

        $categoryTopSliderItems = SliderItem::where('type', SliderType::$CATEGORY_TOP_SLIDER)
            ->orderBy('sort')
            ->with('item')
            ->get();

        $categorySecondSliderItems = SliderItem::where('type', SliderType::$CATEGORY_SECOND_SLIDER)
            ->orderBy('sort')
            ->with('item')
            ->get();

        $newTopSliderItems = SliderItem::where('type', SliderType::$NEW_ARRIVAL_TOP_SLIDER)
            ->orderBy('sort')
            ->with('item')
            ->get();

        $newSecondSliderItems = SliderItem::where('type', SliderType::$NEW_ARRIVAL_SECOND_SLIDER)
            ->orderBy('sort')
            ->with('item')
            ->get();

        return view('admin.dashboard.marketing_tools.banner_items.index',
            compact('items', 'mainSliderItems', 'categoryTopSliderItems', 'categorySecondSliderItems', 'newTopSliderItems', 'newSecondSliderItems'))
            ->with('page_title', 'Banner Items');
    }

    public function bannerItemAdd(Request $request) {
        $query = SliderItem::query();

        if ($request->type == SliderType::$MAIN_SLIDER) {
            $query->where('type', SliderType::$MAIN_SLIDER);
            $count = $query->count();

            if ($count >= 8)
                return response()->json(['success' => false, 'message' => 'Already added 8 items']);
            else {
                $item = SliderItem::where([
                    ['item_id', $request->id],
                    ['type', SliderType::$MAIN_SLIDER]
                ])->first();

                if ($item)
                    return response()->json(['success' => false, 'message' => 'Already added this item']);

                $maxSort = SliderItem::where([
                    ['type', SliderType::$MAIN_SLIDER]
                ])->max('sort');

                if (!$maxSort)
                    $maxSort = 0;

                SliderItem::create([
                    'item_id' => $request->id,
                    'sort' => (int) $maxSort + 1,
                    'type' => SliderType::$MAIN_SLIDER
                ]);

                return response()->json(['success' => true]);
            }
        }
        else if ($request->type == SliderType::$CATEGORY_TOP_SLIDER) {
            $query->where('type', SliderType::$CATEGORY_TOP_SLIDER);
            $count = $query->count();

            if ($count >= 6)
                return response()->json(['success' => false, 'message' => 'Already added 6 items']);
            else {
                $item = SliderItem::where([
                    ['item_id', $request->id],
                    ['type', SliderType::$CATEGORY_TOP_SLIDER]
                ])->first();

                if ($item)
                    return response()->json(['success' => false, 'message' => 'Already added this item']);

                $maxSort = SliderItem::where([
                    ['type', SliderType::$CATEGORY_TOP_SLIDER]
                ])->max('sort');

                if (!$maxSort)
                    $maxSort = 0;

                SliderItem::create([
                    'item_id' => $request->id,
                    'sort' => (int) $maxSort + 1,
                    'type' => SliderType::$CATEGORY_TOP_SLIDER
                ]);

                return response()->json(['success' => true]);
            }
        }
        else if ($request->type == SliderType::$CATEGORY_SECOND_SLIDER) {
            $query->where('type', SliderType::$CATEGORY_SECOND_SLIDER);
            $count = $query->count();

            if ($count >= 6)
                return response()->json(['success' => false, 'message' => 'Already added 6 items']);
            else {
                $item = SliderItem::where([
                    ['item_id', $request->id],
                    ['type', SliderType::$CATEGORY_SECOND_SLIDER]
                ])->first();

                if ($item)
                    return response()->json(['success' => false, 'message' => 'Already added this item']);

                $maxSort = SliderItem::where([
                    ['type', SliderType::$CATEGORY_SECOND_SLIDER]
                ])->max('sort');

                if (!$maxSort)
                    $maxSort = 0;

                SliderItem::create([
                    'item_id' => $request->id,
                    'sort' => (int) $maxSort + 1,
                    'type' => SliderType::$CATEGORY_SECOND_SLIDER
                ]);

                return response()->json(['success' => true]);
            }
        }
        else if ($request->type == SliderType::$NEW_ARRIVAL_TOP_SLIDER) {
            $query->where('type', SliderType::$NEW_ARRIVAL_TOP_SLIDER);
            $count = $query->count();

            if ($count >= 6)
                return response()->json(['success' => false, 'message' => 'Already added 6 items']);
            else {
                $item = SliderItem::where([
                    ['item_id', $request->id],
                    ['type', SliderType::$NEW_ARRIVAL_TOP_SLIDER]
                ])->first();

                if ($item)
                    return response()->json(['success' => false, 'message' => 'Already added this item']);

                $maxSort = SliderItem::where([
                    ['type', SliderType::$NEW_ARRIVAL_TOP_SLIDER]
                ])->max('sort');

                if (!$maxSort)
                    $maxSort = 0;

                SliderItem::create([
                    'item_id' => $request->id,
                    'sort' => (int) $maxSort + 1,
                    'type' => SliderType::$NEW_ARRIVAL_TOP_SLIDER
                ]);

                return response()->json(['success' => true]);
            }
        }
        else if ($request->type == SliderType::$NEW_ARRIVAL_SECOND_SLIDER) {
            $query->where('type', SliderType::$NEW_ARRIVAL_SECOND_SLIDER);
            $count = $query->count();

            if ($count >= 6)
                return response()->json(['success' => false, 'message' => 'Already added 6 items']);
            else {
                $item = SliderItem::where([
                    ['item_id', $request->id],
                    ['type', SliderType::$NEW_ARRIVAL_SECOND_SLIDER]
                ])->first();

                if ($item)
                    return response()->json(['success' => false, 'message' => 'Already added this item']);

                $maxSort = SliderItem::where([
                    ['type', SliderType::$NEW_ARRIVAL_SECOND_SLIDER]
                ])->max('sort');

                if (!$maxSort)
                    $maxSort = 0;

                SliderItem::create([
                    'item_id' => $request->id,
                    'sort' => (int) $maxSort + 1,
                    'type' => SliderType::$NEW_ARRIVAL_SECOND_SLIDER
                ]);

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false, 'message' => '']);
    }

    public function bannerItemRemove(Request $request) {
        SliderItem::where('id', $request->id)->delete();
    }

    public function bannerItemsSort(Request $request) {
        $sort = 1;

        foreach ($request->ids as $id) {
            SliderItem::where('id', $id)->update(['sort' => $sort]);
            $sort++;
        }
    }

    public function mainSliderItems() {
        $images = VendorImage::where('type', VendorImageType::$MAIN_SLIDER)
            ->orderBy('sort')
            ->get();

        return view('admin.dashboard.marketing_tools.main_slider.index', compact('images'))->with('page_title', 'Main Slider');
    }

    public function mainSliderItemAdd(Request $request) {
        $request->validate([
            'photo' => 'required|mimes:jpg,jpeg,mp4,gif',
            'link' => 'required',
        ]);

        $filename = Uuid::generate()->string;
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();

        $destinationPath = '/images/banner';
        $file->move(public_path($destinationPath), $filename.".".$ext);
        $imagePath = $destinationPath."/".$filename.".".$ext;

        $sort = VendorImage::where('type', VendorImageType::$MAIN_SLIDER)->max('sort');

        if ($sort == null || $sort == '')
            $sort = 0;

        $sort++;

        VendorImage::create([
            'type' => VendorImageType::$MAIN_SLIDER,
            'image_path' => $imagePath,
            'status' => 1,
            'url' => $request->link,
            'sort' => $sort,
            'color' => $request->color
        ]);


        return redirect()->back()->with('message', 'Successfully Added!.');
    }

    public function mainSliderItemsSort(Request $request) {
        $sort = 1;

        foreach ($request->ids as $id) {
            VendorImage::where('id', $id)->update(['sort' => $sort]);
            $sort++;
        }
    }

    public function mainSliderItemDelete(Request $request) {
        $image = VendorImage::where('id', $request->id)->first();

        if ($image->image_path != null)
            File::delete(public_path($image->image_path));

        $image->delete();
    }

    public function frontPageBannerItems() {
        $images = VendorImage::where('type', VendorImageType::$FRONT_PAGE_BANNER)
            ->orderBy('sort')
            ->get();

        return view('admin.dashboard.marketing_tools.front_page_banner.index', compact('images'))->with('page_title', 'Front Page Banner');
    }

    public function frontPageBannerItemAdd(Request $request) {
        $request->validate([
            'photo' => 'required|mimes:jpg,jpeg',
            'link' => 'required',
        ]);

        $filename = Uuid::generate()->string;
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();

        $destinationPath = '/images/banner';
        $file->move(public_path($destinationPath), $filename.".".$ext);
        $imagePath = $destinationPath."/".$filename.".".$ext;

        $sort = VendorImage::where('type', VendorImageType::$FRONT_PAGE_BANNER)->max('sort');

        if ($sort == null || $sort == '')
            $sort = 0;

        $sort++;

        VendorImage::create([
            'type' => VendorImageType::$FRONT_PAGE_BANNER,
            'image_path' => $imagePath,
            'url' => $request->link,
            'status' => 1,
            'sort' => $sort
        ]);


        return redirect()->back()->with('message', 'Successfully Added!.');
    }

    public function editPost(Request $request) {
        VendorImage::where('id', $request->id)->update([
            'url' => $request->url,
            'color' => $request->color,
        ]);
    }

    public function topBanner() {
        $categories = Category::where('parent', 0)->get();
        $banners = TopBanner::all();

        return view('admin.dashboard.marketing_tools.top_banner.index', compact('categories', 'banners'))->with('page_title', 'Top Banner');
    }

    public function topBannerAdd(Request $request) {
        $request->validate([
            'photo' => 'required|mimes:jpg,jpeg',
            'link' => 'required',
            'page' => 'required'
        ]);

        $filename = Uuid::generate()->string;
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();

        $destinationPath = '/images/banner';
        $file->move(public_path($destinationPath), $filename.".".$ext);
        $imagePath = $destinationPath."/".$filename.".".$ext;

        $page = null;
        $category = null;
        $previous = null;

        if ($request->page == '-1') {
            $page = PageEnumeration::$NEW_ARRIVAL;
            $previous = TopBanner::where('page', $page)->first();
        } else if ($request->page == '-2') {
            $page = PageEnumeration::$BEST_SELLER;

            $previous = TopBanner::where('page', $page)->first();
        } else {
            $category = $request->page;

            $previous = TopBanner::where('category_id', $category)->first();
        }

        if ($previous) {
            unlink(ltrim($previous->image_path, '/'));
            $previous->delete();
        }

        TopBanner::create([
            'page' => $page,
            'category_id' => $category,
            'url' => $request->link,
            'image_path' => $imagePath
        ]);

        return redirect()->back();
    }

    public function topBannerDelete(Request $request) {
        TopBanner::where('id', $request->id)->delete();
    }

    public function topBannerEditPost(Request $request) {
        TopBanner::where('id', $request->id)->update([
            'url' => $request->url
        ]);
    }

    public function logoPost(Request $request) {
        $request->validate([
            'logo' => 'nullable|mimes:jpeg,jpg,png,svg',
            'logo2' => 'nullable|mimes:jpeg,jpg,png,svg',
            'logo3' => 'nullable|mimes:jpeg,jpg,png,svg',

        ]);
        if ($request->logo) {
            $file = $request->file('logo');
            $this->uploadLogo($file, 'logo-white');
        }

        if ($request->logo2) {
            $file = $request->file('logo2');
            $this->uploadLogo($file, 'logo-black');
        }
        if ($request->logo3) {
            $file = $request->file('logo3');
            $this->uploadLogo($file, 'default-item-image');
        }
        return redirect()->route('admin_banner')->with('message', 'Successfully Added!');
    }

    public function uploadLogo($file, $type) {
        $filename = Uuid::generate()->string;
        $ext = $file->getClientOriginalExtension();
        $destinationPath = 'images/logo';
        $file->move(public_path($destinationPath), $filename.".".$ext);
        $imagePath = $destinationPath."/".$filename.".".$ext;

        DB::table('settings')->where('name', $type)->update(['value' => $imagePath]);
    }

    public function footer_index(Request $request) {
        $categories = Category::where('parent', 0)->get();
        $banners = TopBanner::all();
        $records = DB::table('meta_settings')->where('meta_key','footer')->get();
        $data = array();
        if(count($records) == 0)
        {
            $data['text_editor_1'] = "";
            $data['text_editor_2'] = "";
        }
        else
        {
            $data['text_editor_1'] = $records[0]->meta_value;
            $data['text_editor_2'] = $records[1]->meta_value;
        }

        return view('admin.dashboard.marketing_tools.footer_banner.index', compact('categories','banners','data'))->with('page_title', 'Footer Banner');
    }

    public function footer_addPost(Request $request)
    {
        $records = DB::table('meta_settings')->where('meta_key', 'footer')->get();
        if(count($records) == 0)
        {
            $data = array();
            $data['meta_key'] = 'footer';
            $data['meta_value'] = ($request->page_editor_1 != '' ) ? $request->page_editor_1 : '';
            DB::table('meta_settings')->insert( $data ); 

            $data = array();
            $data['meta_key'] = 'footer';
            $data['meta_value'] = ($request->page_editor_2 != '' ) ? $request->page_editor_2 : '';
            DB::table('meta_settings')->insert( $data );
            return redirect()->back();
        }
        else
        {
            DB::table('meta_settings')->where('meta_key', 'footer')->delete();
            $data = array();
            $data['meta_key'] = 'footer';
            $data['meta_value'] = ($request->page_editor_1 != '' ) ? $request->page_editor_1 : '';
            DB::table('meta_settings')->insert( $data ); 

            $data = array();
            $data['meta_key'] = 'footer';
            $data['meta_value'] = ($request->page_editor_2 != '' ) ? $request->page_editor_2 : '';
            DB::table('meta_settings')->insert( $data );
            return redirect()->back()->with('message', 'Successfully Added!');
        }
    }
}
