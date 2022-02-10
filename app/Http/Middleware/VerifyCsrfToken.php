<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'paypal',
        'admin/create_new_item/upload/image',
        'admin/items/import/read_file',
        'laravel-filemanager/upload',
        'resource/search_items'
    ];
}
