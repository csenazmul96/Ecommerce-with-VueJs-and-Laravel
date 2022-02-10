<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $table = 'login_history';

    protected $fillable = [
        'user_id', 'ip'
    ];

    public function user() {
        return $this->belongsTo('App\Model\User');
    }
}
