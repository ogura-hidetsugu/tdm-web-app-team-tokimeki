<?php

namespace App\Http\Controllers;

use App\Models\ShoppingEntry;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShoppingController extends Controller
{
    public function index(Request $request)
    {
        $group = $this->ensureCurrentGroup();

        $selectedStore = $request->query('store', 'all');
        $sortByStore = $request->boolean('sort_by_store', false);

        $stores = Store::where('group_id', $group->id)->orderBy('sort_order')->get();

        $query = ShoppingEntry::where('shopping_entries.group_id', $group->id)
            ->where('shopping_entries.status', '未購入')
            ->with(['store', 'addedBy']);

        if ($selectedStore !== 'all') {
            $query->whereHas('store', function ($q) use ($selectedStore) {
                $q->where('name', $selectedStore);
            });
        }

        if ($sortByStore) {
            $query->leftJoin('stores', 'shopping_entries.store_id', '=', 'stores.id')
                  ->orderByRaw('COALESCE(stores.sort_order, 999999) asc')
                  ->orderBy('shopping_entries.id', 'desc')
                  ->select('shopping_entries.*');
        } else {
            $query->orderBy('shopping_entries.sort_order')->orderBy('shopping_entries.id', 'asc');
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
        $group = $this->ensureCurrentGroup();

        $stores = Store::where('group_id', $group->id)->orderBy('sort_order')->get();

        $entries = ShoppingEntry::where('group_id', $group->id)
            ->where('status', '未購入')
            ->with('store')
            ->orderBy('id', 'desc')
            ->get();

        $addedCount = $entries->count();

        return view('shopping.create', compact('entries', 'stores', 'addedCount', 'group'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'store_id' => 'nullable|exists:stores,id',
            'memo' => 'nullable|string|max:255',
        ], [
            'name.required' => '品名を入力してください。',
            'name.max' => '品名は100文字以内で入力してください。',
        ])->validate();

        $group = $this->ensureCurrentGroup();
        $user = $this->currentUser();

        // 自グループの店舗であることを保証（未指定・他グループなら「未設定」店舗）
        $storeId = $request->input('store_id');
        if ($storeId && ! Store::where('group_id', $group->id)->where('id', $storeId)->exists()) {
            $storeId = null;
        }
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
        if ($redirect === 'home') {
            return redirect()->route('home')->with('success', '買い物リストに「' . $request->input('name') . '」を追加しました！');
        }
        if ($redirect === 'index') {
            return redirect()->route('shopping.index')->with('success', '買い物リストに「' . $request->input('name') . '」を追加しました！');
        }

        return redirect()->route('shopping.create')->with('success', '「' . $request->input('name') . '」を追加しました！');
    }

    public function toggle($id)
    {
        $group = $this->ensureCurrentGroup();
        $entry = ShoppingEntry::where('group_id', $group->id)->findOrFail($id);
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
        $group = $this->ensureCurrentGroup();
        $entry = ShoppingEntry::where('group_id', $group->id)->findOrFail($id);
        $name = $entry->name;
        $entry->delete();

        return back()->with('success', '「' . $name . '」を取り消しました。');
    }
}
