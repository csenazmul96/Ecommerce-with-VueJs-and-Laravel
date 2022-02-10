<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class WishListItem extends Model
{
    protected $fillable = [
        'user_id', 'item_id'
    ];

    public function getItemIds($user) {
        $ids = [];  
        $items = DB::table('wish_list_items')->whereIn('user_id', $user)->get(); 
        foreach ($items as $item)
            $ids[] = $item->item_id; 
        return $ids;
    }
}
