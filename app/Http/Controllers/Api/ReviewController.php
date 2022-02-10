<?php

namespace App\Http\Controllers\Api;

use App\Model\ItemReviewFeedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ItemReview;
use App\Model\ItemReviewImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function makeReview(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'item_id' => 'required|numeric|exists:items,id',
            'review' => 'required|string|max:350',
            'title' => 'nullable|string|max:191',
            'fixedRate' => 'required|numeric|min:1|max:5',
            'reviewImages' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' =>false , 'errors'=>$validator->messages()], 403);
        }

        $validatedData = $validator->validate();
        $review = ItemReview::create([
            'item_id' => $validatedData['item_id'],
            'user_id' => auth()->id() ?? null,
            'title' => $validatedData['title'],
            'review' => $validatedData['review'],
            'rate' => $validatedData['fixedRate'],
        ]);
        foreach ($validatedData['reviewImages'] as $image) {

            $originalSize = null;
            $custom_compressed = null;
            $thumbs = null;

            if ($image) {
                $colorImage = imageMove($image, $folderName = 'styleImage', $imageStoreTypes = ['originalSize', 'custom_compressed', 'thumbs'], $customWidth = 300);
                $originalSize = $colorImage['originalSize'];
                $custom_compressed = $colorImage['custom_compressed'];
                $thumbs = $colorImage['thumbs'];
            }
            ItemReviewImage::create([
                'review_id' => $review->id,
                'image_path' => $originalSize,
                'compressed_image_path' => $custom_compressed,
                'thumbs_image_path' => $thumbs,
            ]);
        }
        $review->load('images');
        return response()->json(['success' => true, 'review' => $review], 200);
    }
    public function getReviews(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'item_id' => 'required|numeric|exists:items,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' =>false , 'errors'=>$validator->messages()], 403);
        }

        $validatedData = $validator->validate();
        $reviews = ItemReview::with('user', 'images')->where('item_id', $validatedData['item_id'])->orderBy('created_at', 'desc')->paginate(3);

        foreach ($reviews as $review) {
            $review->isLiked = false;
            $review->isDisliked = false;


            if (Auth::guard('api')->check()) {
                $exist = ItemReviewFeedback::where('user_id', Auth::guard('api')->user()->id)->where('item_review_id', $review->id)->first();

                if ($exist) {
                    if ($exist->like)
                        $review->isLiked = true;
                    else
                        $review->isDisliked = true;
                }
            }
        }

        $totalReviews = ItemReview::where('item_id', $validatedData['item_id'])->count();
        $rate = ItemReview::where('item_id', $validatedData['item_id'])->avg('rate');
        $rate = number_format((float)$rate, 2, '.', '');
        $rateCount = [
            'one' => ItemReview::where('item_id', $validatedData['item_id'])->where('rate', 1)->count(),
            'two' => ItemReview::where('item_id', $validatedData['item_id'])->where('rate', 2)->count(),
            'three' => ItemReview::where('item_id', $validatedData['item_id'])->where('rate', 3)->count(),
            'four' => ItemReview::where('item_id', $validatedData['item_id'])->where('rate', 4)->count(),
            'five' => ItemReview::where('item_id', $validatedData['item_id'])->where('rate', 5)->count(),
        ];

        return response()->json(['success' => true, 'reviews' => $reviews, 'totalReviews' => $totalReviews, 'rate' => $rate, 'rateCount' => $rateCount], 200);
    }

    public function makeReviewFeedback(Request $request) {
        $validator = \Validator::make($request->all(), [
            'review_id' => 'required',
            'like' => 'required|integer|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false , 'error'=> $validator->errors()->first()], 422);
        }

        $existingFeedback = ItemReviewFeedback::where('user_id', Auth::guard()->user()->id)->where('item_review_id', $request->review_id)->first();

        if ($existingFeedback && $request->like == $existingFeedback->like) {
            $existingFeedback->delete();

            if ($request->like == 1)
                ItemReview::find($request->review_id)->decrement('like');
            else
                ItemReview::find($request->review_id)->decrement('dislike');

            return response()->json(['success' => true, 'increment' => false], 200);
        } elseif ($existingFeedback && $request->like != $existingFeedback->like) {
            return response()->json(['success' => false , 'error'=> 'You already gave feedback'], 409);
        } else {
            $feedback = new ItemReviewFeedback();
            $feedback->user_id = Auth::guard()->user()->id;
            $feedback->item_review_id = $request->review_id;
            $feedback->like = $request->like;
            $feedback->save();

            if ($request->like == 1)
                ItemReview::find($request->review_id)->increment('like');
            else
                ItemReview::find($request->review_id)->increment('dislike');

            return response()->json(['success' => true, 'increment' => true], 200);
        }
    }
}
