<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AuthorizeLog extends Model
{
    protected $table = 'authorize_log';
    protected $fillable = [
        'order_id', 'authorize_type', 'authorize_info', 'status'
    ];
    public function authorizeHistory() {
        return $this->belongsTo('App\Models\AuthorizeLog');
    }
}
