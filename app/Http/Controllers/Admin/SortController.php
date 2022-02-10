<?php

namespace App\Http\Controllers\Admin;

use App\Model\Item;
use App\Model\Category;
use App\Model\ItemCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SortController extends Controller
{
    public function index(Request $request) {
        $query = Item::query();

        // Sort
        $query->orderBy('sorting', 'desc');
        if ($request->sort) {
            if ($request->sort == '2')
                $query->orderBy('activated_at', 'desc');
            else if ($request->sort == '3')
                $query->orderBy('updated_at', 'desc');
            else {
                $query->orderBy('sorting', 'desc');
                $query->orderBy('activated_at', 'desc');
            }
        } else {
            $query->orderBy('sorting', 'desc');
            $query->orderBy('activated_at', 'desc');
        }

        // Type
        if ($request->a) {
            if ($request->a == '2')
                $query->where('status', 1);
            else if ($request->a == '3')
                $query->where('status', 0);
            else if ($request->a == '1')
                $query->whereIn('status', [0, 1]);
            else
                $query->where('status', 1);
        } else {
            $query->where('status', 1);
        }

        // Category
        if ($request->c1) {
            if ($request->c1 != '0') {
                $itemIds = ItemCategory::where('default_parent_category', $request->c1)->pluck('item_id')->toArray();
                $query->whereIn('id', $itemIds);
            }
        }

        if ($request->c2) {
            if ($request->c2 != '0') {
                $itemIds = ItemCategory::where('default_second_category', $request->c2)->pluck('item_id')->toArray();
                $query->whereIn('id', $itemIds);
            }
        }

        if ($request->c3) {
            if ($request->c3 != '0') {
                $itemIds = ItemCategory::where('default_third_category', $request->c3)->pluck('item_id')->toArray();
                $query->whereIn('id', $itemIds);
            }
        }

        // Per page
        if ($request->p) {
            if ($request->p == '1')
                $items = $query->paginate(50);
            else if ($request->p == '2')
                $items = $query->paginate(100);
            else if ($request->p == '3')
                $items = $query->paginate(150);
            else
                $items = $query->paginate(50);
        } else {
            $items = $query->paginate(50);
        }


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

        return view('admin.dashboard.item_settings.sort_items.index', compact('items', 'defaultCategories'))->with('page_title', 'Sort Items');
    }

    public function save(Request $request) {
        if(!empty($request->ids)){
            for($i=0; $i < count($request->ids); $i++) {
                Item::where('id', $request->ids[$i])->update([
                    'sorting' => (int) $request->sort[$i]
                ]);
            }
        }
        Item::resetSorting();
        return redirect()->back()->with('message', 'Updated!');
    }

    
    public function saveSingle(Request $request) {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'sorting' => 'required|numeric',
        ]);
        $currentItem = Item::find($request->item_id);
        if ($request->sorting > $currentItem->sorting) {
            // position increase
            $items = Item::where('status', 1)
                            ->orderBy('sorting', 'desc')
                            ->where('sorting', '<=', $request->sorting)
                            ->where('sorting', '>', $currentItem->sorting)
                            ->get();
            foreach ($items as $item) {
                $item->sorting = $item->sorting - 1;
                $item->update();
            }
        } else if ($request->sorting < $currentItem->sorting) {
            // position decrease
            $items = Item::where('status', 1)
                            ->orderBy('sorting', 'desc')
                            ->where('sorting', '<', $currentItem->sorting)
                            ->where('sorting', '>=', $request->sorting)
                            ->get();
            foreach ($items as $item) {
                $item->sorting = $item->sorting + 1;
                $item->update();
            }
        }

        $currentItem->sorting = $request->sorting;
        $currentItem->update();
        return response()->json(['success'=>true], 200);
    }
}
