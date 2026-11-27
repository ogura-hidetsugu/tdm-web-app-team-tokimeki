<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Item;
use App\Models\NotificationSetting;
use App\Models\ShoppingEntry;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $group = $this->currentGroup();
        if (!$group) {
            return redirect()->route('home');
        }

        // 1. 少なくなった商品（在庫 <= 基準値、または <= 1）
        $lowStockItems = Item::where('group_id', $group->id)
            ->whereNull('archived_at')
            ->where(function ($q) {
                $q->whereColumn('stock_quantity', '<=', 'reorder_level')
                  ->orWhere('stock_quantity', '<=', 1);
            })
            ->with(['category', 'defaultStore'])
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // 2. 購入周期が近づいたもの（定期購入品で残り日数が短い順にソート！）
        $recurringItems = Item::where('group_id', $group->id)
            ->whereNull('archived_at')
            ->where('is_recurring', true)
            ->whereNotNull('purchase_cycle_days')
            ->with(['category', 'defaultStore'])
            ->get()
            ->sortBy(function ($item) {
                return $item->days_until_next_purchase ?? 999;
            })
            ->values();

        // 3. 通知設定（ユーザーの設定）
        $user = $this->currentUser();
        $settings = [
            'notify_days_before' => 3, // 3日前に通知
            'auto_add_shopping' => false, // 自動追加
            'low_stock_notify' => true, // 在庫僅少通知
        ];

        return view('notifications.index', compact('group', 'lowStockItems', 'recurringItems', 'settings'));
    }

    /**
     * 通知から買い物リストへワンクリック追加
     */
    public function addToList(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $group = $this->currentGroup();
        $user = $this->currentUser();

        // 既に買い物リストにあるか確認
        $existing = ShoppingEntry::where('group_id', $group->id)
            ->where('item_id', $item->id)
            ->where('status', '未購入')
            ->first();

        if ($existing) {
            return back()->with('success', "「{$item->name}」は既に買い物リストに登録されています！");
        }

        ShoppingEntry::create([
            'group_id' => $group->id,
            'item_id' => $item->id,
            'name' => $item->name,
            'store_id' => $item->default_store_id,
            'memo' => '通知から追加（残り ' . ($item->days_until_next_purchase ?? 0) . ' 日で目安）',
            'status' => '未購入',
            'added_by_user_id' => $user->id,
        ]);

        return back()->with('success', "「{$item->name}」を買い物リストに追加しました！🛒✨");
    }

    /**
     * 通知設定の更新
     */
    public function updateSettings(Request $request)
    {
        // 設定保存のシミュレーション
        return back()->with('success', '通知設定を更新しました！🔔');
    }
}
