<?php

namespace App\Model;

use App\Enumeration\Providers;
use Illuminate\Database\Eloquent\Model;

class ItemInv extends Model
{
    protected $table = 'item_inv';

    public function size() {
        return $this->belongsTo(Size::class, 'itemsize', 'id');
    }

}
