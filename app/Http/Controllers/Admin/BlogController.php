<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\BannerType;
use App\Model\Banner;
use File;
use Illuminate\Support\Facades\Storage;
use Uuid;
use Image;
use App\Model\BlogPost;
use App\Model\BlogComment;
use App\Model\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(Request $request) {
        $allPosts = BlogPost::orderBy('created_at','desc')->with('categories')->paginate(10);

        $categories = BlogCategory::where('status', '=', 1)->get();

        return view('admin.dashboard.blog.index',compact('allPosts','categories'))->with('page_title', 'All Post');
    }

    public function addPost(){
        $categories = BlogCategory::where('status', '=', 1)->get();
        return view('admin.dashboard.blog.create', compact('categories'));
    }

    public function addPostSave(Request $request){
        $request->validate([
            'post_title' => 'required',
            'post_description' => 'required',
            'post_category' => 'required',
        ]);

        $title = $request->post_title;
        $string = utf8_encode($title);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace('/[^a-z0-9- ]/i', '', $string);
        $string = str_replace(' ', '-', $string);
        $string = trim($string, '-');
        $slug = strtolower($string);

        $slugCheck = BlogPost::where('slug', $slug)->first();

        if ( $slugCheck != null ) {
            $duplicateNameCounter = BlogPost::where('title', $title)->count();
            $slug .= '-' . ($duplicateNameCounter + 1);
        }

        $imagePath = null;
        $thumb = null;

        if ($request->post_image) {
            $filename = \Illuminate\Support\Str::uuid();
            $compressImgPath = 'blog/original/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;
            $thumbsImgPath = 'blog/thumbs/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;

            $thumbsImgWebp = Image::make($request->post_image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->encode('webp');
            Storage::put($thumbsImgPath.'.webp', $thumbsImgWebp);

            $compressedImgWebp = Image::make($request->post_image)->resize(900, 900)->encode('webp');
            Storage::put($compressImgPath.'.webp', $compressedImgWebp);

            $imagePath = $compressImgPath.'.webp';
            $thumb = $thumbsImgPath.'.webp';
        }

        BlogPost::create([
            'status' => $request->status,
            'title' => $request->post_title,
            'description' => $request->post_description,
            'image' => $imagePath,
            'thumb' => $thumb,
            'image_alt' => $request->image_alt,
            'tags' => $request->post_tags,
            'category_id' => $request->post_category,
            'slug' => $slug,
            'status' => $request->statusCategory,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description
        ]);

        return redirect()->route('admin_blog')->with('message', 'Post Added!');
    }

    public function editPost(Request $request, $id){
        $categories = BlogCategory::where('status', '=', 1)->get();
        $post = BlogPost::where('id', $id)->first();
        return view('admin.dashboard.blog.edit',compact('post','categories'))->with('page_title', 'Edit Post');
    }

    public function updatePost(Request $request, $id){

        $request->validate([
            'post_title' => 'required',
            'post_slug' => 'required',
            'post_description' => 'required',
            'post_category' => 'required',
        ]);

        $post = BlogPost::where('id', $id)->first();

        if ($request->post_image) {
            if ($post->image != null){
                if (Storage::exists($post->image))
                    Storage::delete($post->image);
                if (Storage::exists($post->thumb))
                    Storage::delete($post->thumb);
            }

            $filename = \Illuminate\Support\Str::uuid();
            $compressImgPath = 'blog/original/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;
            $thumbsImgPath = 'blog/thumbs/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;

            $thumbsImgWebp = Image::make($request->post_image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->encode('webp');
            Storage::put($thumbsImgPath.'.webp', $thumbsImgWebp);

            $compressedImgWebp = Image::make($request->post_image)->resize(900, 900)->encode('webp');
            Storage::put($compressImgPath.'.webp', $compressedImgWebp);

            $post->image = $compressImgPath.'.webp';
            $post->thumb = $thumbsImgPath.'.webp';
        }

        if($request->post_slug){
            $slug = $request->post_slug;
        }else{
            $slug = $post->slug;
        }

        if($request->update_date){
            $updatedAt = $request->update_date . $request->update_time;
        }else{
            $updatedAt = $post->updated_at;
        }

        $post->status = $request->statusPost;
        $post->title = $request->post_title;
        $post->slug = $slug;
        $post->image_alt = $request->image_alt;
        $post->description = $request->post_description;
        $post->tags = $request->post_tags;
        $post->category_id = $request->post_category;
        $post->updated_at = $updatedAt;
        $post->meta_title = $request->meta_title;
        $post->meta_description = $request->meta_description;

        $post->save();

        return redirect()->back()->with('message', 'Post Updated!');
    }

    public function deletePost(Request $request){
        $post = BlogPost::where('id', $request->id)->first();
        $post->delete();
    }

    public function changePostStatus(Request $request){
        $post = BlogPost::where('id', $request->id)->first();
        $post->status = $request->status;
        $post->save();
    }

    public function blogCategory(){
        $categories = BlogCategory::where('status',1)->get();
        return view('admin.dashboard.blog.category',compact('categories'))->with('page_title','Blog Category');
    }

    public function addBlogCategory(Request $request){

        $request->validate([
            'category_name' => 'required',
        ]);

        $categoryName = $request->category_name;

        $slug = str_replace('/', '-', str_replace(' ', '-', str_replace('&', '', str_replace('?', '', strtolower($categoryName)))));

        $slugCheck = BlogCategory::where('slug', $slug)->first();

        if ( $slugCheck != null ) {
            $duplicateNameCounter = BlogCategory::where('name', $categoryName)->count();
            $slug .= '-' . ($duplicateNameCounter + 1);
        }

        $sort = 1;
        $category = BlogCategory::orderBy('sort', 'desc')->first();

        if ($category)
            $sort = $category->sort + 1;

        $category = BlogCategory::create([
            'name' => $request->category_name,
            'slug' => $slug,
            'sort' => $sort,
        ]);
        return redirect()->back()->with('message', 'Category Added.');
    }

    public function updateBlogCategory(Request $request){

        $category = BlogCategory::where('id', $request->id)->first();

        // Create slug from categoryname
        if($category->name != $request->category_name){

            $categoryName = $request->category_name;

            $string = trim($categoryName);
            $string = preg_replace('/[^\w-]/', '', $string);
            $string = str_replace(' ', '-', $string);
            $slug = strtolower($string);

            $slugCheck = BlogCategory::where('slug', $slug)->first();

            if ( $slugCheck != null ) {
                $duplicateNameCounter = BlogCategory::where('name', $categoryName)->count();
                $slug .= '-' . ($duplicateNameCounter + 1);
            }
        }else{
            $slug = $category->slug;
        }

        $category->name = $request->category_name;
        $category->slug = $slug;
        $category->status = 1;
        $category->save();

        return redirect()->back()->with('message', 'Category Updated!');
    }

    public function deleteBlogCategory(Request $request){
        $category = BlogCategory::where('id', $request->id)->first();
        $category->delete();
    }

    public function allComment(){
        $comments = BlogComment::orderBy('created_at','desc')->paginate(10);
        return view('admin.dashboard.blog.comments',compact('comments'))->with('page_title', 'Blog Comments');
    }

    public function changeCommentStatus(Request $request){
        $comment = BlogComment::where('id', $request->id)->first();
        $comment->status = $request->status;
        $comment->save();
    }

    public function commentDetails(Request $request){
        $comments = BlogComment::where('id', $request->id)->first();
        return $comments;
    }

    public function commentDelete(Request $request){
        $comment = BlogComment::where('id', $request->id)->first();
        $comment->delete();
    }

    public function blogBanner()
    {
      $bloglistbanner = Banner::where('type', BannerType::$BLOGLISTBANNER)->first();
      $blogsinglebanner = Banner::where('type', BannerType::$BLOGSINGLEBANNER)->first();

      return view('admin.dashboard.blog.banner',compact('bloglistbanner', 'blogsinglebanner'))->with('page_title', 'Blog Banners');
    }

    public function blogBannerUpdate(Request $request)
    {
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png,webp',
            ]);
            $bloglistbanner = Banner::where('type', BannerType::$BLOGLISTBANNER)->first();
            $image = request()->file('image');
            $imageName = Uuid::generate()->string;
            $imagepath = '/images/banner/' . $imageName . '.webp';
            Image::make($image)->encode('webp', 60)->save(public_path('/images/banner/' . $imageName . '.webp'), 60);
            if(!$bloglistbanner){
                $bloglistbanner = new Banner();
            }else{
                if (\File::exists(public_path($bloglistbanner->image))) {
                    \File::delete(public_path($bloglistbanner->image));
                }
            }
            $bloglistbanner->image = $imagepath;
            $bloglistbanner->type = BannerType::$BLOGLISTBANNER;
            $bloglistbanner->save();
        }
        if ($request->hasFile('banner')) {
            $request->validate([
                'banner' => 'required|mimes:jpg,jpeg,png,webp',
            ]);
            $blogsinglebanner = Banner::where('type', BannerType::$BLOGSINGLEBANNER)->first();
            $image = request()->file('banner');
            $imageName = Uuid::generate()->string;
            $banner = '/images/banner/' . $imageName . '.webp';
            Image::make($image)->encode('webp', 60)->save(public_path('/images/banner/' . $imageName . '.webp'), 60);
            if(!$blogsinglebanner){
                $blogsinglebanner = new Banner();
            }else{
                if (\File::exists(public_path($blogsinglebanner->image))) {
                    \File::delete(public_path($blogsinglebanner->image));
                }
            }
            $blogsinglebanner->image = $banner;
            $blogsinglebanner->type = BannerType::$BLOGSINGLEBANNER;
            $blogsinglebanner->save();
        }

        return redirect()->route('blog_banner');
    }
}
