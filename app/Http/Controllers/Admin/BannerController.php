<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\BannerType;
use App\Model\Banner;
use App\Model\Category;
use App\Model\Item;
use App\Model\SectionHeading;
use DB;
use File;
use Psy\Util\Json;
use Uuid;
use Carbon\Carbon;
use ImageOptimizer;
use App\Model\Setting;
use App\Model\VendorImage;
use Illuminate\Http\Request;
use App\Enumeration\PageEnumeration;
use App\Enumeration\VendorImageType;
use App\Http\Controllers\Controller;
use Image;

class BannerController extends Controller
{
    //Logo Start
    public function logo(Request $request) {

        $logoBig = DB::table('settings')->where('name', 'logo-big')->where('status',1)->first();
        $logoSmalls = DB::table('settings')->where('name', 'logo-small')->where('status',1)->first();
        $defaultImage = DB::table('settings')->where('name', 'default-item-image')->where('status',1)->first();

        return view('admin.dashboard.marketing_tools.logo.index', compact('logoBig', 'logoSmalls', 'defaultImage'))->with('page_title', 'Logo');
    }

    public function logoPost(Request $request) {
        $request->validate([
            'bigLogo' => 'nullable|mimes:jpeg,jpg,png,svg',
            'smallLogo' => 'nullable|mimes:jpeg,jpg,png,svg',

        ]);
        if ($request->bigLogo) {
            $file = $request->file('bigLogo');
            $this->uploadLogo($file, 'logo-big');
        }

        if ($request->smallLogo) {
            $file = $request->file('smallLogo');
            $this->uploadLogo($file, 'logo-small');
        }

        if ($request->default_image) {
            $file = $request->file('default_image');
            $this->uploadLogo($file, 'default-item-image');
        }

        return redirect()->route('admin_logo')->with('message', 'Successfully Added!.');
    }

    public function uploadLogo($file, $type) {
        $filename = Uuid::generate()->string;
        $ext = $file->getClientOriginalExtension();
        $destinationPath = 'images/logo';
        $file->move(public_path($destinationPath), $filename.".".$ext);
        $imagePath = $destinationPath."/".$filename.".".$ext;

        $logo = DB::table('settings')->where('name', $type)->first();

        if(!empty($logo)){
            DB::table('settings')->where('name', $type)->update(['value' => $imagePath,'status' => 1, 'created_at'=>Carbon::now()]);
        }else{
            DB::table('settings')->insert([
                [
                    'name' => $type,
                    'value' => $imagePath,
                    'status' => 1,
                    'created_at' => Carbon::now()
                ]
            ]);
        }
    }

    public function LogoRemove(Request $request) {

        Setting::where('id', $request->id)->update([
            'status' => 0,
        ]);
    }
    //Logo End

    //Main Banner Start
    public function mainBanner() {
        $bannerImages = Banner::where('type', BannerType::$MINBANNERDESKTOP)
            ->orderBy('sort')
            ->get();

        $images_mob = Banner::where('type', BannerType::$MINBANNERMOBILE)
            ->orderBy('sort')
            ->get();

        return view('admin.dashboard.banner.main_banner.index', compact('bannerImages','images_mob'))->with('page_title', 'Main Banner');
    }

    public function mainBannerAdd(Request $request) {
        if(!$request->bannerid) {
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png,webp',
                'type' => 'required',
            ]);
        }

        $imagepath = null;
        if($request->bannerid){
            $banner = Banner::find($request->bannerid);
            $imagepath = $banner->image;
            $oldimage = $banner->image;
        }else{
            $banner = new Banner();
        }

        if ($request->hasFile('image')) {
            $image = request()->file('image');
            $imageName = Uuid::generate()->string;
            $imagepath = '/images/banner/' . $imageName . '.webp';
            Image::make($image)->encode('webp')->save(public_path('/images/banner/' . $imageName . '.webp'));
            if($request->bannerid){
                if (\File::exists(public_path($oldimage))) {
                    \File::delete(public_path($oldimage));
                }
            }
        }

        $banner->image = $imagepath;
        $banner->link = $request->link;
        $banner->type = $request->type;
        $banner->save();

        return redirect()->route('admin_main_banner');
    }

    public function bannerSort(Request $request) {
        $sort = 1;
        foreach ($request->ids as $id) {
            Banner::where('id', $id)->where('type', $request->type)->update(['sort' => $sort]);
            $sort++;
        }
    }

    public function mainBannerUpdate(Request $request) {
        VendorImage::where('id', $request->id)->update([
            'url' => $request->url,
            'title' => $request->title,
            'details' => $request->description
        ]);
    }

    //Main Banner End

    //Section Two Banner Start
    public function sectionTwoBanner() {
        $sectionTwoBanners = VendorImage::where('type', VendorImageType::$SECTION_TWO_BANNER)
            ->orderBy('sort')
            ->get();

        return view('admin.dashboard.marketing_tools.section_two_banner.index', compact('sectionTwoBanners'))->with('page_title', 'Section Two Banner');
    }

    public function sectionTwoBannerAdd(Request $request) {
        $request->validate([
            'photo' => 'required|mimes:jpg,jpeg,mp4'
        ]);

        $bannerWidth = 400;
        switch ($request->type) {
            case VendorImageType::$SECTION_TWO_BANNER:
                $bannerWidth = 1920;
                $sort = VendorImage::where('type', VendorImageType::$SECTION_TWO_BANNER)->max('sort');
                break;
            default:
                $bannerWidth = 400;
                $sort = 999;
                break;
        }

        $filename = Uuid::generate()->string;
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();
        $compressedSavePath = null;

        if($ext == 'mp4'){
            $destinationPath = '/images/banner';
            $file->move(public_path($destinationPath), $filename.".".$ext);
            $savePath = $destinationPath."/".$filename.".".$ext;
            $compressedSavePath = $savePath;

        }else{

            $image = $request->file('photo');
            $tempImagePath = temporaryImageUpload($image);
            $allPaths = imageMove($tempImagePath, $folderName = 'banner', $imageStoreTypes = ['image_path', 'custom_compressed'], $bannerWidth);
            $savePath = $allPaths['image_path'];
            $compressedSavePath = $allPaths['custom_compressed'];

        }

        if ($sort == null || $sort == '')
            $sort = 0;

        $sort++;

        VendorImage::create([
            'type' => VendorImageType::$SECTION_TWO_BANNER,
            'image_path' => $compressedSavePath,
            'url' => $request->link,
            'title' => $request->title,
            'details' => $request->description,
            'status' => 1,
            'sort' => $sort
        ]);
        return redirect()->back()->with('message', 'Successfully Added!.');
    }

    public function sectionTwoBannerSort(Request $request) {
        $sort = 1;

        foreach ($request->ids as $id) {
            VendorImage::where('id', $id)->update(['sort' => $sort]);
            $sort++;
        }
    }

    public function sectionTwoBannerUpdate(Request $request) {
        VendorImage::where('id', $request->id)->update([
            'url' => $request->url,
            'title' => $request->title,
            'details' => $request->description
        ]);
    }

    public function sectionTwoBannerDelete(Request $request) {
        $image = VendorImage::where('id', $request->id)->first();

        if ($image->image_path != null)
            File::delete(public_path($image->image_path));

        $image->delete();
    }
    //Section Two Banner End


    public function menuBannerIndex()
    {
        $banners = Banner::where('type', BannerType::$MENU)->with('bannercategory')->orderBy('sort')->get();
        $categories = Category::where('parent', 0)->with('banners')->get();

        return view('admin.dashboard.banner.menubanner.index', compact('banners', 'categories'))->with('page_title', 'Menu Banners');
    }

    public function sortMenuBanner(Request $request)
    {
        $sort = 1;
        foreach ($request->ids as $id) {
            Banner::where('id', $id)->where('category', $request->cat)->update(['sort' => $sort]);
            $sort++;
        }
    }

    public function menuBannerAdd(Request $request)
    {
        if(!$request->bannerid) {
            $request->validate(
                [
                    'image' => 'required|image',
                    'link' => 'required',
                ]
            );
        }
        $request->validate([ 'link' => 'required',] );

        $imagepath = null;
//        if($request->oldimage)
//            $imagepath = $request->oldimage;

        $banner = '';
        if($request->bannerid){
            $banner = Banner::find($request->bannerid);
            $imagepath = $banner->image;
            $oldimage = $banner->image;
        }else{
            $banner = new Banner();
        }

        if ($request->hasFile('image')) {
            $image = request()->file('image');
            $imageName = Uuid::generate()->string;
            $imagepath = '/images/menu_image/' . $imageName . '.webp';
            Image::make($image)->encode('webp', 80)->resize(245, 245)->save(public_path('/images/menu_image/' . $imageName . '.webp'));
            if($request->bannerid){
                if (\File::exists(public_path($oldimage))) {
                    \File::delete(public_path($oldimage));
                }
            }
        }

        $banner->image = $imagepath;
        $banner->name = $request->name;
        $banner->category = $request->category;
        $banner->link = $request->link;
        $banner->description = $request->description;
        $banner->type = BannerType::$MENU;
        $banner->save();

        return redirect()->route('menu_banners');
    }

    public function mainBannerDelete(Request $request)
    {
        $id = $request->id;
        $banner = Banner::find($id);
        if (\File::exists(public_path($banner->image))) {
            \File::delete(public_path($banner->image));
        }
        $banner->delete();
    }

    public function deleteMenuBanner(Request $request)
    {
        $id = $request->id;
        $banner = Banner::find($id);
        if (\File::exists(public_path($banner->image))) {
            \File::delete(public_path($banner->image));
        }
        $banner->delete();
    }

    public function featureWidget()
    {
       $banners = Banner::where('type', BannerType::$FEATURE_WIDGET)->orderBy('sort')->get();
       $bottom_banners = Banner::where('type', BannerType::$FEATURE_WIDGET_BOTTOM)->orderBy('sort')->get();
        $section_heading1 = SectionHeading::where('section_name', 'home_first_custom_section')->first();
        $section_heading2 = SectionHeading::where('section_name', 'home_second_custom_section')->first();

       return view('admin.dashboard.banner.feature_widget.Index', compact('banners','bottom_banners', 'section_heading1', 'section_heading2'))->with('page_title', 'Feature Widget Banner');;
    }

    public function featureWidgetAdd(Request $request)
    {

        if(!$request->bannerid) {
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png,webp',
                'link' => 'required',
                'name' => 'required|string',
                'description' => 'required|string',
                'type' => 'required',
            ]);
        }

        $imagepath = null;
        if($request->bannerid){
            $banner = Banner::find($request->bannerid);
            $imagepath = $banner->image;
            $oldimage = $banner->image;
        }else{
            $banner = new Banner();
        }

        if ($request->hasFile('image')) {
            $image = request()->file('image');
            $imageName = Uuid::generate()->string;
            $imagepath = '/images/banner/' . $imageName . '.webp';
            Image::make($image)->encode('webp')->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/images/banner/' . $imageName . '.webp'));
            if($request->bannerid){
                if (\File::exists(public_path($oldimage))) {
                    \File::delete(public_path($oldimage));
                }
            }
        }

        $banner->image = $imagepath;
        $banner->name = $request->name;
        $banner->link = $request->link;
        $banner->type = $request->type;
        $banner->description = $request->description;
        $banner->save();

        $type = $request->type === '6' ? 'top' : 'bottom';

        return redirect()->route('feature_widget',['type'=>$type]);
    }

    public function homePageItems()
    {
        $activeItemsQuery = Item::query();
        $activeItemsQuery->where('status', 1)->with('category', 'images');
        $activeItemsQuery->orderBy('sorting', 'desc');
        $activeItems = $activeItemsQuery->paginate(20, ['*'], 'p1');
        $section_heading = SectionHeading::where('section_name', 'new_arrival_section')->first();

        $selectedItems = VendorImage::where('type', VendorImageType::$HOME_PAGE_ITEMS)->with('item','item_image')->orderBy('sort')->get();

        return view('admin.dashboard.marketing_tools.items.home_page_items', compact('activeItems','selectedItems', 'section_heading'))->with('page_title', 'Home Page Selected Items');;
    }

    public function homePageItemsAdd(Request $request)
    {
        $check = VendorImage::where('type', VendorImageType::$HOME_PAGE_ITEMS)->where('url', $request->id)->first();
        if($check)
            return response()->json(['success' => true, 'message'=> 'Already added.']);

        $sort = VendorImage::where('type', VendorImageType::$HOME_PAGE_ITEMS)->max('sort');

        $image = new VendorImage();
        $image->url = $request->id;
        $image->type = VendorImageType::$HOME_PAGE_ITEMS;
        $image->sort = $sort+1;
        $image->status = 1;
        $image->save();

        return response()->json(['success' => true, 'message'=> 'Item Successfully added.']);
    }

    public function homePageItemsSort(Request $request)
    {
        $sort = 1;
        foreach ($request->ids as $id) {
            VendorImage::where('id', $id)->where('type', VendorImageType::$HOME_PAGE_ITEMS)->update(['sort' => $sort]);
            $sort++;
        }
    }

    public function homePageItemsDelete(Request $request)
    {
        $image = VendorImage::where('id', $request->id)->delete();
    }
}
