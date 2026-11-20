<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'name', 'kind', 'sort_order'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function shoppingEntries()
    {
        return $this->hasMany(ShoppingEntry::class);
    }
}
