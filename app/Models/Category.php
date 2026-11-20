<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'name', 'icon', 'sort_order'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
