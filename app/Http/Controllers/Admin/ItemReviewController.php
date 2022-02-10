<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ItemReview;
use File;
class ItemReviewController extends Controller
{
    public function index()
    {
        $reviews = ItemReview::with('item','user')->latest()->paginate(20);

        return view('admin.dashboard.item_review.index', compact('reviews'))->with('page_title', 'Item Review');
    }

    public function destroy(Request $request)
    {
        $review = ItemReview::with('images')->where('id', $request->id)->first();

        if(count($review->images)){

            foreach ($review->images as $image){
                if (\File::exists(public_path().$image->image_path)) {
                    \File::delete(public_path().$image->image_path);
                }

                if (\File::exists(public_path().$image->compressed_image_path)) {
                    \File::delete(public_path().$image->compressed_image_path);
                }

                if (\File::exists(public_path().$image->thumbs_image_path)) {
                    \File::delete(public_path().$image->thumbs_image_path);
                }
                $image->delete();
            }
        }

        $review->delete();

    }
}
