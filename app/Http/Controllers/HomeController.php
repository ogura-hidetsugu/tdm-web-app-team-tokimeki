<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\Item;
use App\Models\Membership;
use App\Models\ShoppingEntry;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $group = $this->currentGroup();
        if (!$group) {
            return redirect()->route('shopping.index');
        }

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

        // そろそろなくなるアラート品 (在庫が少なくなっている、またはたまご)
        $alertItem = Item::where('group_id', $group->id)
            ->whereNull('archived_at')
            ->where(function ($q) {
                $q->where('name', 'like', '%たまご%')
                  ->orWhere('name', 'like', '%卵%')
                  ->orWhereColumn('stock_quantity', '<=', 'reorder_level');
            })
            ->first();

        // 次回の買い替え予定品 (牛乳または定期購入品)
        $upcomingItem = Item::where('group_id', $group->id)
            ->whereNull('archived_at')
            ->where('is_recurring', true)
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
            'alertItem',
            'upcomingItem',
            'recentShoppingEntries',
            'familyMembers'
        ));
    }
}
