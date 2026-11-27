<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Membership;
use App\Models\ShoppingEntry;
use App\Models\Store;

class HomeController extends Controller
{
    public function index()
    {
        $group = $this->ensureCurrentGroup();

        // おうちの在庫数
        $stockCount = Item::where('group_id', $group->id)->whereNull('archived_at')->count();

        // 買い物リストの未購入アイテム数
        $shoppingCount = ShoppingEntry::where('group_id', $group->id)
            ->where('status', '未購入')
            ->count();

        // カテゴリー一覧
        $categories = Category::where('group_id', $group->id)
            ->orderBy('sort_order')
            ->withCount(['items' => function ($query) {
                $query->whereNull('archived_at');
            }])
            ->get();

        // 購入先店舗一覧（クイック追加用）
        $stores = Store::where('group_id', $group->id)
            ->orderBy('sort_order')
            ->get();

        // そろそろなくなるアラート品 (在庫が少なくなっているもの優先)
        $alertItem = Item::where('group_id', $group->id)
            ->whereNull('archived_at')
            ->where(function ($q) {
                $q->whereColumn('stock_quantity', '<=', 'reorder_level')
                  ->orWhere('stock_quantity', '<=', 1);
            })
            ->orderBy('stock_quantity', 'asc')
            ->first();

        // 次回の買い替え予定品 (定期購入品の中で残り日数が一番短いもの)
        $upcomingItem = Item::where('group_id', $group->id)
            ->whereNull('archived_at')
            ->where('is_recurring', true)
            ->whereNotNull('purchase_cycle_days')
            ->get()
            ->sortBy(fn ($item) => $item->days_until_next_purchase ?? 999)
            ->first();

        // 最近の買い物リスト（プレビュー用）
        $recentShoppingEntries = ShoppingEntry::where('group_id', $group->id)
            ->with('store')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 家族メンバー
        $familyMembers = Membership::where('group_id', $group->id)
            ->with('user')
            ->get();

        return view('home', compact(
            'group',
            'stockCount',
            'shoppingCount',
            'categories',
            'stores',
            'alertItem',
            'upcomingItem',
            'recentShoppingEntries',
            'familyMembers'
        ));
    }
}
