<?php

namespace App\Model;

use App\Enumeration\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class MetaSettings extends Model
{
    protected $table = 'meta_settings';

    protected $fillable = [
        'meta_id', 'meta_value'
    ];
}
