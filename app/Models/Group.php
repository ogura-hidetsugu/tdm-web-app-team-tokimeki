<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'memberships');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function shoppingEntries()
    {
        return $this->hasMany(ShoppingEntry::class);
    }
}
