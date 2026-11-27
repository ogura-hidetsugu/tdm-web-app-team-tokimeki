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

    /**
     * 新規グループ用の初期店舗・初期カテゴリーを作成する。
     */
    public function seedDefaults(): void
    {
        $defaultStores = [
            ['name' => '未設定', 'kind' => 'other', 'sort_order' => 0],
            ['name' => 'スーパー', 'kind' => 'supermarket', 'sort_order' => 1],
            ['name' => '薬局', 'kind' => 'drugstore', 'sort_order' => 2],
            ['name' => 'Amazon', 'kind' => 'online', 'sort_order' => 3],
        ];

        foreach ($defaultStores as $store) {
            Store::firstOrCreate(
                ['group_id' => $this->id, 'name' => $store['name']],
                ['kind' => $store['kind'], 'sort_order' => $store['sort_order']]
            );
        }

        $defaultCategories = [
            ['name' => '食品', 'icon' => 'canned', 'sort_order' => 1],
            ['name' => '飲み物', 'icon' => 'bottle', 'sort_order' => 2],
            ['name' => '日用品', 'icon' => 'pump', 'sort_order' => 3],
            ['name' => '消耗品', 'icon' => 'tissue', 'sort_order' => 4],
            ['name' => 'コスメ', 'icon' => 'cosme', 'sort_order' => 5],
            ['name' => '薬・サプリ', 'icon' => 'pill', 'sort_order' => 6],
            ['name' => 'その他', 'icon' => 'box', 'sort_order' => 7],
        ];

        foreach ($defaultCategories as $category) {
            Category::firstOrCreate(
                ['group_id' => $this->id, 'name' => $category['name']],
                ['icon' => $category['icon'], 'sort_order' => $category['sort_order']]
            );
        }
    }
}
