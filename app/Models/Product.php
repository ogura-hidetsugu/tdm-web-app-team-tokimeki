<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'store',
        'stock',
        'url',
        'image_path',
        'memo',
    ];
}