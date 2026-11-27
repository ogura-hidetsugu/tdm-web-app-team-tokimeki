<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\NotificationSetting;
use App\Models\ShoppingEntry;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private const ALLOWED_DAYS = [1, 3, 5, 7];

    public function index()
    {
        $group = $this->ensureCurrentGroup();

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

        // 2. 購入周期が近づいたもの（残り日数が短い順にソート）
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

        // 3. 通知設定（DBから読み込み）
        $user = $this->currentUser();
        $settings = $this->loadUserSettings($user?->id);

        return view('notifications.index', compact('group', 'lowStockItems', 'recurringItems', 'settings'));
    }

    /**
     * 通知から買い物リストへワンクリック追加
     */
    public function addToList(Request $request, $itemId)
    {
        $group = $this->ensureCurrentGroup();
        $item = Item::where('group_id', $group->id)->findOrFail($itemId);
        $user = $this->currentUser();

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
        $validated = $request->validate([
            'notify_days_before' => ['required', 'integer', 'in:1,3,5,7'],
            'auto_add_shopping' => ['required', 'boolean'],
        ]);

        $user = $this->currentUser();
        if ($user) {
            $selectedDays = (int) $validated['notify_days_before'];
            foreach (self::ALLOWED_DAYS as $days) {
                NotificationSetting::updateOrCreate(
                    ['user_id' => $user->id, 'notification_type' => 'cycle_days_' . $days],
                    ['is_enabled' => $days === $selectedDays]
                );
            }

            NotificationSetting::updateOrCreate(
                ['user_id' => $user->id, 'notification_type' => 'auto_add_shopping'],
                ['is_enabled' => (bool) $validated['auto_add_shopping']]
            );
        }

        return back()->with('success', '通知設定を更新しました！🔔');
    }

    private function loadUserSettings(?int $userId): array
    {
        $defaults = [
            'notify_days_before' => 3,
            'auto_add_shopping' => false,
            'low_stock_notify' => true,
        ];

        if (! $userId) {
            return $defaults;
        }

        $rows = NotificationSetting::where('user_id', $userId)->get()->keyBy('notification_type');

        foreach (self::ALLOWED_DAYS as $days) {
            $row = $rows->get('cycle_days_' . $days);
            if ($row && $row->is_enabled) {
                $defaults['notify_days_before'] = $days;
                break;
            }
        }

        if ($rows->has('auto_add_shopping')) {
            $defaults['auto_add_shopping'] = (bool) $rows->get('auto_add_shopping')->is_enabled;
        }

        return $defaults;
    }
}
