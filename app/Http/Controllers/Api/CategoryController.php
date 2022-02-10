<?php

namespace App\Http\Controllers\Api;

use App\Model\Banner;
use App\Model\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index() {
        $defaultCategories = [];
        $categoriesCollection = Category::where('status', 1)->orderBy('sort')->with('banners')->get();
        foreach($categoriesCollection as $cc) {
            if ($cc->parent == 0) {
                $data = [
                    'id' => $cc->id,
                    'name' => $cc->name,
                    'slug' => $cc->slug,
                    'sub_title' => $cc->sub_title,
                    'description' => $cc->description,
                    'image' => $cc->image
                ];

                $subCategories = [];
                foreach($categoriesCollection as $item) {
                    if ($item->parent == $cc->id) {
                        $data2 = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'slug' => $item->slug,
                            'sub_title' => $item->sub_title,
                            'description' => $item->description,
                            'image' => $cc->image
                        ];

                        $data3 = [];
                        foreach($categoriesCollection as $item2) {
                            if ($item2->parent == $item->id) {
                                $data3[] = [
                                    'id' => $item2->id,
                                    'name' => $item2->name,
                                    'slug' => $item2->slug,
                                    'sub_title' => $item2->sub_title,
                                    'description' => $item2->description,
                                    'image' => $cc->image
                                ];
                            }

                        }

                        $data2['thirdcategory'] = $data3;
                        $subCategories[] = $data2;
                    }
                }

                $data['subCategories'] = $subCategories;
                $data['banners'] = Banner::where('category', $cc->id)->orderBy('sort')->take(3)->get();
                $defaultCategories[] = $data;
            }
        }

        return response()->json(['categories'=>$defaultCategories],200);
    }

    public function defaultCategories() {
        // Default Categories
        $defaultCategories = [];

        $categoriesCollection = Category::orderBy('sort')->orderBy('name')->get();

        foreach($categoriesCollection as $cc) {
            if ($cc->parent == 0) {
                $data = [
                    'id' => $cc->id,
                    'name' => $cc->name
                ];

                $subCategories = [];
                foreach($categoriesCollection as $item) {
                    if ($item->parent == $cc->id) {
                        $data2 = [
                            'id' => $item->id,
                            'name' => $item->name
                        ];

                        $data3 = [];
                        foreach($categoriesCollection as $item2) {
                            if ($item2->parent == $item->id) {
                                $data3[] = [
                                    'id' => $item2->id,
                                    'name' => $item2->name
                                ];
                            }
                        }

                        $data2['subCategories'] = $data3;
                        $subCategories[] = $data2;
                    }
                }

                $data['subCategories'] = $subCategories;
                $defaultCategories[] = $data;
            }
        }

        return response()->json($defaultCategories);
    }

}
