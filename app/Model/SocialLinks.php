<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SocialLinks extends Model
{
    protected $fillable = [
        'facebook', 'twitter', 'pinterest', 'instagram', 'whatsapp', 'google_plus'
    ];
}
