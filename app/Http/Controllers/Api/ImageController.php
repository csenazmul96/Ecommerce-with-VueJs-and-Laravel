<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ImageController extends Controller
{
    public function temporaryImageUpload(Request $request)
    {
        $urls = [];
        foreach ($request->images as $image) {
            array_push($urls, temporaryImageUpload($image));
        }
        return response()->json(['success' => true, 'urls' => $urls]);
    }
    
    public function removeTemporaryImage(Request $request)
    {
        return response()->json(['success' => removeTemporaryImage($request->image)]);
    }
}
