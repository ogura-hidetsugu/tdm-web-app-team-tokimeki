<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Item;
use App\Models\ShoppingEntry;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ShoppingController extends Controller
{
    public function index(Request $request)
    {
        $group = $this->currentGroup();

        $selectedStore = $request->query('store', 'all');
        $sortByStore = $request->boolean('sort_by_store', false);

        if (!$group) {
            $stores = collect();
            $items = collect();
            $purchasedItems = collect();
            $counts = ['all' => 0];

            return view('shopping.index', compact('items', 'purchasedItems', 'stores', 'selectedStore', 'sortByStore', 'counts', 'group'));
        }

        $stores = Store::where('group_id', $group->id)->orderBy('sort_order')->get();

        $query = ShoppingEntry::where('group_id', $group->id)
            ->where('status', '未購入')
            ->with(['store', 'addedBy']);

        if ($selectedStore !== 'all') {
            $query->whereHas('store', function ($q) use ($selectedStore) {
                $q->where('name', $selectedStore);
            });
        }

        if ($sortByStore) {
            $query->join('stores', 'shopping_entries.store_id', '=', 'stores.id')
                  ->orderBy('stores.sort_order')
                  ->orderBy('shopping_entries.id', 'desc')
                  ->select('shopping_entries.*');
        } else {
            $query->orderBy('sort_order')->orderBy('id', 'asc');
        }

        $items = $query->get();

        // 購入済みのアイテム
        $purchasedItems = ShoppingEntry::where('group_id', $group->id)
            ->where('status', '購入済')
            ->with(['store', 'purchasedBy'])
            ->orderBy('purchased_at', 'desc')
            ->take(10)
            ->get();

        // 店舗ごとの件数
        $counts = [
            'all' => ShoppingEntry::where('group_id', $group->id)->where('status', '未購入')->count(),
        ];
        foreach ($stores as $s) {
            $counts[$s->name] = ShoppingEntry::where('group_id', $group->id)
                ->where('status', '未購入')
                ->where('store_id', $s->id)
                ->count();
        }

        return view('shopping.index', compact('items', 'purchasedItems', 'stores', 'selectedStore', 'sortByStore', 'counts', 'group'));
    }

    public function create()
    {
        $group = $this->currentGroup();

        if (!$group) {
            $stores = collect();
            $entries = collect();
            $addedCount = 0;

            return view('shopping.create', compact('entries', 'stores', 'addedCount', 'group'));
        }

        $stores = Store::where('group_id', $group->id)->orderBy('sort_order')->get();

        // 現在登録されている未購入品一覧（画像2の上部リスト）
        $entries = ShoppingEntry::where('group_id', $group->id)
            ->with('store')
            ->orderBy('id', 'desc')
            ->get();

        $addedCount = $entries->where('status', '未購入')->count();

        return view('shopping.create', compact('entries', 'stores', 'addedCount', 'group'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'store_id' => 'nullable|exists:stores,id',
            'memo' => 'nullable|string|max:255',
        ])->validate();

        $group = $this->currentGroup();
        if (!$group) {
            $group = Group::create(['name' => 'ときめきファミリー']);
        }

        $user = $this->currentUser();
        if (!$user) {
            $user = User::create([
                'display_name' => 'きょうか (わたし)',
                'email' => 'me@tokimeki.local',
                'password' => Hash::make('password'),
            ]);
        }

        // store_idが未指定の場合は「未設定」店舗を探す
        $storeId = $request->input('store_id');
        if (!$storeId) {
            $defaultStore = Store::where('group_id', $group->id)->where('name', '未設定')->first();
            $storeId = $defaultStore ? $defaultStore->id : null;
        }

        ShoppingEntry::create([
            'group_id' => $group->id,
            'name' => $request->input('name'),
            'store_id' => $storeId,
            'memo' => $request->input('memo'),
            'status' => '未購入',
            'added_by_user_id' => $user->id,
        ]);

        $redirect = $request->input('redirect_to', 'create');
        if ($redirect === 'index') {
            return redirect()->route('shopping.index')->with('success', '買い物リストに「' . $request->input('name') . '」を追加しました！');
        }

        return redirect()->route('shopping.create')->with('success', '「' . $request->input('name') . '」を追加しました！');
    }

    public function toggle($id)
    {
        $entry = ShoppingEntry::findOrFail($id);
        $user = $this->currentUser();

        if ($entry->status === '未購入') {
            $entry->status = '購入済';
            $entry->purchased_by_user_id = $user ? $user->id : null;
            $entry->purchased_at = now();
            $msg = '「' . $entry->name . '」を購入済みにしました！';
        } else {
            $entry->status = '未購入';
            $entry->purchased_by_user_id = null;
            $entry->purchased_at = null;
            $msg = '「' . $entry->name . '」を未購入に戻しました！';
        }
        $entry->save();

        return back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $entry = ShoppingEntry::findOrFail($id);
        $name = $entry->name;
        $entry->delete();

        return back()->with('success', '「' . $name . '」を取り消しました。');
    }
}
