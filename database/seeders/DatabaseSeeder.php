<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Membership;
use App\Models\Category;
use App\Models\Store;
use App\Models\Item;
use App\Models\ShoppingEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ユーザー作成
        $me = User::firstOrCreate(
            ['email' => 'me@tokimeki.local'],
            [
                'display_name' => 'きょうか (わたし)',
                'password' => Hash::make('password'),
            ]
        );

        $papa = User::firstOrCreate(
            ['email' => 'papa@tokimeki.local'],
            [
                'display_name' => 'パパ',
                'password' => Hash::make('password'),
            ]
        );

        $mama = User::firstOrCreate(
            ['email' => 'mama@tokimeki.local'],
            [
                'display_name' => 'ママ',
                'password' => Hash::make('password'),
            ]
        );

        // 2. グループ作成
        $group = Group::firstOrCreate(
            ['name' => 'ときめきファミリー'],
            ['name' => 'ときめきファミリー']
        );

        // 3. メンバーシップ
        Membership::firstOrCreate(['user_id' => $me->id, 'group_id' => $group->id], ['role' => 'admin']);
        Membership::firstOrCreate(['user_id' => $papa->id, 'group_id' => $group->id], ['role' => 'member']);
        Membership::firstOrCreate(['user_id' => $mama->id, 'group_id' => $group->id], ['role' => 'member']);

        // 4. 店舗作成
        $storeMap = [];
        $stores = [
            ['name' => '未設定', 'kind' => 'other', 'sort_order' => 0],
            ['name' => 'スーパー', 'kind' => 'supermarket', 'sort_order' => 1],
            ['name' => '薬局', 'kind' => 'drugstore', 'sort_order' => 2],
            ['name' => 'Amazon', 'kind' => 'online', 'sort_order' => 3],
        ];
        foreach ($stores as $s) {
            $st = Store::firstOrCreate(
                ['group_id' => $group->id, 'name' => $s['name']],
                ['kind' => $s['kind'], 'sort_order' => $s['sort_order']]
            );
            $storeMap[$s['name']] = $st->id;
        }

        // 5. カテゴリー作成
        $categoryMap = [];
        $categories = [
            ['name' => '食品', 'icon' => 'canned', 'sort_order' => 1],
            ['name' => '飲み物', 'icon' => 'bottle', 'sort_order' => 2],
            ['name' => '日用品', 'icon' => 'pump', 'sort_order' => 3],
            ['name' => '消耗品', 'icon' => 'tissue', 'sort_order' => 4],
            ['name' => 'コスメ', 'icon' => 'cosme', 'sort_order' => 5],
            ['name' => '薬・サプリ', 'icon' => 'pill', 'sort_order' => 6],
            ['name' => 'その他', 'icon' => 'box', 'sort_order' => 7],
        ];
        foreach ($categories as $c) {
            $cat = Category::firstOrCreate(
                ['group_id' => $group->id, 'name' => $c['name']],
                ['icon' => $c['icon'], 'sort_order' => $c['sort_order']]
            );
            $categoryMap[$c['name']] = $cat->id;
        }

        // 6. 在庫・アイテム作成 (5種類のおうちの在庫)
        $items = [
            [
                'name' => '牛乳',
                'category_id' => $categoryMap['飲み物'] ?? null,
                'default_store_id' => $storeMap['スーパー'] ?? null,
                'stock_quantity' => 1,
                'reorder_level' => 1,
                'is_recurring' => true,
                'purchase_cycle_days' => 7,
                'memo' => '低脂肪乳か無調整牛乳',
            ],
            [
                'name' => 'たまご (10個入り)',
                'category_id' => $categoryMap['食品'] ?? null,
                'default_store_id' => $storeMap['スーパー'] ?? null,
                'stock_quantity' => 2,
                'reorder_level' => 3,
                'is_recurring' => true,
                'purchase_cycle_days' => 10,
                'memo' => 'そろそろなくなるよ！',
            ],
            [
                'name' => 'ティッシュ (5箱パック)',
                'category_id' => $categoryMap['消耗品'] ?? null,
                'default_store_id' => $storeMap['薬局'] ?? null,
                'stock_quantity' => 1,
                'reorder_level' => 2,
                'is_recurring' => true,
                'purchase_cycle_days' => 30,
                'memo' => 'エリエールかネピア',
            ],
            [
                'name' => '富士清水 バナジウム＆シリカ天然水',
                'category_id' => $categoryMap['飲み物'] ?? null,
                'default_store_id' => $storeMap['Amazon'] ?? null,
                'stock_quantity' => 12,
                'reorder_level' => 4,
                'is_recurring' => true,
                'purchase_cycle_days' => 30,
                'product_url' => 'https://www.amazon.co.jp',
                'memo' => '特売なら2ケース',
            ],
            [
                'name' => 'トイレットペーパー (12ロール)',
                'category_id' => $categoryMap['消耗品'] ?? null,
                'default_store_id' => $storeMap['薬局'] ?? null,
                'stock_quantity' => 3,
                'reorder_level' => 2,
                'is_recurring' => true,
                'purchase_cycle_days' => 25,
                'memo' => 'ダブルがおすすめ',
            ],
        ];

        $createdItems = [];
        foreach ($items as $itemData) {
            $createdItems[$itemData['name']] = Item::firstOrCreate(
                ['group_id' => $group->id, 'name' => $itemData['name']],
                array_merge($itemData, [
                    'group_id' => $group->id,
                    'created_by_user_id' => $me->id,
                ])
            );
        }

        // 7. 買い物リスト（初期エントリー）
        $shoppingList = [
            [
                'name' => 'ティッシュ',
                'memo' => '5箱パック',
                'store_name' => 'スーパー',
                'status' => '未購入',
                'sort_order' => 1,
            ],
            [
                'name' => '牛乳',
                'memo' => '',
                'store_name' => 'スーパー',
                'status' => '未購入',
                'sort_order' => 2,
            ],
            [
                'name' => '卵',
                'memo' => '10個入り',
                'store_name' => 'スーパー',
                'status' => '未購入',
                'sort_order' => 3,
            ],
            [
                'name' => '食パン',
                'memo' => '6枚切り',
                'store_name' => 'スーパー',
                'status' => '未購入',
                'sort_order' => 4,
            ],
            [
                'name' => 'バナナ',
                'memo' => '1房',
                'store_name' => 'スーパー',
                'status' => '未購入',
                'sort_order' => 5,
            ],
            [
                'name' => 'りんご',
                'memo' => '',
                'store_name' => '未設定',
                'status' => '未購入',
                'sort_order' => 6,
            ],
            [
                'name' => 'キャベツ',
                'memo' => '',
                'store_name' => '未設定',
                'status' => '未購入',
                'sort_order' => 7,
            ],
            [
                'name' => 'じゃがいも',
                'memo' => '',
                'store_name' => 'スーパー',
                'status' => '未購入',
                'sort_order' => 8,
            ],
            [
                'name' => 'トイレットペーパー',
                'memo' => 'たった今購入',
                'store_name' => '薬局',
                'status' => '購入済',
                'sort_order' => 9,
                'purchased_by_user_id' => $papa->id,
                'purchased_at' => now(),
            ],
        ];

        foreach ($shoppingList as $entry) {
            ShoppingEntry::firstOrCreate(
                ['group_id' => $group->id, 'name' => $entry['name']],
                [
                    'group_id' => $group->id,
                    'store_id' => $storeMap[$entry['store_name']] ?? $storeMap['未設定'],
                    'memo' => $entry['memo'],
                    'status' => $entry['status'],
                    'sort_order' => $entry['sort_order'],
                    'added_by_user_id' => $me->id,
                    'purchased_by_user_id' => $entry['purchased_by_user_id'] ?? null,
                    'purchased_at' => $entry['purchased_at'] ?? null,
                ]
            );
        }
    }
}
