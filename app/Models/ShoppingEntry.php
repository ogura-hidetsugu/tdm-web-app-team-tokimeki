<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'item_id',
        'name',
        'store_id',
        'memo',
        'status',
        'sort_order',
        'added_by_user_id',
        'purchased_by_user_id',
        'purchased_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    public function purchasedBy()
    {
        return $this->belongsTo(User::class, 'purchased_by_user_id');
    }
}
