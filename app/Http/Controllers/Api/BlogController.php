<?php

namespace App\Http\Controllers\Api;
use App\Model\BlogPost;
use App\Model\BlogComment;
use App\Model\BlogCategory;
use App\Model\BlogView;
use Illuminate\Support\Facades\Storage;
use Validator;
use Response;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    //
    public function index($slug=null, Request $request){
        $category = null;
        $blogs = BlogPost::query()->with('categories','comments');
        if(isset($request->s) && !empty($request->s)){
            $blogs->Where('title', 'like', '%' . $request->s . '%');
            $blogs->orWhere('description', 'like', '%' . $request->s . '%');
            $blogs->orWhere('tags', 'like', '%' . $request->s . '%');
        }
        if(isset($slug) && !empty($slug)){
            $category = BlogCategory::where('slug', '=', $slug)->first();
            if($category){
                $blogs->where('category_id',$category->id);
            }
        }
        $blogs->orderBy('created_at','desc');
        $allPosts = $blogs->paginate(6);
        foreach($allPosts as $post) {
            $string = strip_tags($post->description);
            $string = str_replace('&nbsp;', ' ', $string);
            $string = str_replace('&rsquo;', ' ', $string);

            if (strlen($string) > 350) {
                $stringCut = substr($string, 0, 350);
            }else{
                $stringCut = $string;
            }

            $post->image = Storage::url($post->image);
            $post->thumb = Storage::url($post->thumb);

            $post->description = $stringCut." [...]";
        }
        return response()->json(['success' => true, 'message' => 'Success','blogs'=>$allPosts,'category'=>$category]);
    }

    public function blogSingle($slug,Request $request){
        $data = [];
        $details = BlogPost::where('slug',$slug)->with('categories','comments')->first();
         if(!$details){
             return 'not found';
         }
        // Visitor Blog view count
        $visitor_url = $request->ip();
        $PostView = BlogView::where('post_id',$details->id)->where('ip',$visitor_url)->count();
        if(!$PostView){
            BlogView::Create([
                'post_id'=>$details->id,
                'ip'=>$visitor_url,
                'view'=>1,
            ]);
        }
        $details->view = BlogView::where('post_id',$details->id)->count();
        $details->save();
        $details->image = Storage::url($details->image);

        // Previous and Next item id
        $prevItemId=null;
        $nextItemId=null;
        $queryItems = BlogPost::query();
        $itemIds = $queryItems->pluck('id')->toArray();
        $itemIndex = array_search($details->id, $itemIds);
        switch ($itemIndex) {
            case -1:
                $prevItemId=null;
                $nextItemId=null;
                break;
            case 0:
                $prevItemId=null;
                $nextItemId=count($itemIds)>1 ? $itemIds[1] : null;
                break;
            case count($itemIds) - 1:
                $prevItemId=count($itemIds) == 1 ? null : $itemIds[$itemIndex-1];
                $nextItemId=null;
                break;
            default:
                $prevItemId=$itemIds[$itemIndex-1];
                $nextItemId=$itemIds[$itemIndex+1];
                break;
        }
        $prevItemId=BlogPost::where('id',$prevItemId)->first();
        $nextItemId=BlogPost::where('id',$nextItemId)->first();

        // Blog comment fetch
        $comments = BlogComment::where('post_id',$details->id)->where('parent_comment',0)->where('status',1)->orderBy('created_at','desc')->get();
        foreach($comments as &$comment){
            $level_2comment = BlogComment::where('post_id',$details->id)->where('parent_comment',$comment->id)->where('comment_level',2)->where('status',1)->orderBy('replay_comment_id','asc')->get();
            $comment->replay = $level_2comment ;
        }

        $tags = explode(',',$details->tags);

        $data=[
            'blog'          => $details,
            'nextblog'      => $nextItemId,
            'previousblog'  => $prevItemId,
            'comments'      => $comments,
            'tags'          => $tags
        ];
        return response()->json(['success' => true, 'message' => 'Success','data'=>$data]);
    }

    public function sidebarContent(){
        $categories = BlogCategory::where('status', '=', 1)->get();
        foreach($categories as &$cat){
            $count = BlogPost::where('category_id', $cat->id)->count();
            $cat->total = $count;
        }
        $featuredPosts = BlogPost::where('status', 1)->orderBy('view', 'desc')->take(5)->get();
        $archivePost = BlogPost::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->orderBy('created_at','desc')->limit(5)->get();
        foreach ($featuredPosts as $post){
            $post->thumb = Storage::url($post->thumb);
        }
        $data = [
            'categories' => $categories,
            'topitems' => $featuredPosts,
            'archivePost' => $archivePost
        ];
        return response()->json(['success' => true, 'message' => 'Success','content'=>$data]);
    }

    public function commentPost(Request $request){

        if($request->blog_id){
            $rules['name'] = 'required|string|max:191';
            $rules['email'] = 'required|email|max:191';
            $rules['comment'] = 'required|string|max:1000';
            $validator = Validator::make($request->all(), $rules);
           if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Fail','errors'=>$validator->getMessageBag()->toArray()]);
            }


            $parent = 0;
            $replay = 0;
            $level = 1;
            if(isset($request->comment_parent_id)){
                $parent = $request->comment_parent_id;
                $level = 2;
            }
            if(isset($request->comment_replay_id)){
                $replay = $request->comment_replay_id;
            }

           $comment= BlogComment::create([
                'post_id' => $request->blog_id,
                'name' => $request->name,
                'email' => $request->email,
                'comment' => $request->comment,
                'parent_comment' => $parent,
                'replay_comment_id' => $replay,
                'comment_level' => $level,
                'status' => 1,
            ]);
            if($comment){
                $comments = BlogComment::where('post_id',$request->blog_id)->where('parent_comment',0)->where('status',1)->orderBy('created_at','desc')->get();
                foreach($comments as &$comment){
                    $level_2comment = BlogComment::where('post_id',$request->blog_id)->where('parent_comment',$comment->id)->where('comment_level',2)->where('status',1)->orderBy('replay_comment_id','asc')->get();
                    $comment->replay = $level_2comment ;
                }
                return response()->json(['success' => true, 'message' => 'Success','comments'=>$comments]);
            }
        }
    }
}
