<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\Item;
use App\Models\ShoppingEntry;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $group = $this->currentGroup();
        $selectedCategory = $request->query('category', 'all');

        if (!$group) {
            $categories = collect();
            $items = collect();
            return view('items.index', compact('items', 'categories', 'selectedCategory', 'group'));
        }

        $categories = Category::where('group_id', $group->id)->orderBy('sort_order')->get();

        $query = Item::where('group_id', $group->id)
            ->whereNull('archived_at')
            ->with(['category', 'defaultStore']);

        if ($selectedCategory !== 'all') {
            $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('name', $selectedCategory);
            });
        }

        $items = $query->orderBy('id', 'desc')->get();

        return view('items.index', compact('items', 'categories', 'selectedCategory', 'group'));
    }

    public function create()
    {
        $group = $this->currentGroup();

        if (!$group) {
            $categories = collect();
            $stores = collect();
            return view('items.create', compact('categories', 'stores', 'group'));
        }

        $categories = Category::where('group_id', $group->id)->orderBy('sort_order')->get();
        $stores = Store::where('group_id', $group->id)->orderBy('sort_order')->get();

        return view('items.create', compact('categories', 'stores', 'group'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'default_store_id' => 'nullable|exists:stores,id',
            'memo' => 'nullable|string|max:1000',
            'photo_display' => 'nullable|string|in:cutout,original',
            'product_url' => 'nullable|url|max:2048',
            'is_recurring' => 'nullable|boolean',
            'purchase_cycle_days' => 'nullable|integer|min:1',
            'stock_quantity' => 'nullable|integer|min:0',
        ])->validate();

        $group = $this->currentGroup();
        $user = $this->currentUser();

        $isRecurring = $request->boolean('is_recurring', true);
        $cycleDays = $request->input('purchase_cycle_days', 30);
        $photoRef = $request->input('photo_captured');

        $item = Item::create([
            'group_id' => $group->id,
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'default_store_id' => $request->input('default_store_id'),
            'memo' => $request->input('memo'),
            'photo_original_ref' => $photoRef,
            'photo_cutout_ref' => $photoRef,
            'photo_display' => $request->input('photo_display', 'cutout'),
            'product_url' => $request->input('product_url'),
            'is_recurring' => $isRecurring,
            'purchase_cycle_days' => $isRecurring ? $cycleDays : null,
            'stock_quantity' => $request->input('stock_quantity', 1),
            'reorder_level' => 1,
            'created_by_user_id' => $user->id,
        ]);

        // 同時に買い物リストにも追加するか？などのオプションがあれば
        if ($request->boolean('add_to_shopping_list', false)) {
            ShoppingEntry::create([
                'group_id' => $group->id,
                'item_id' => $item->id,
                'name' => $item->name,
                'store_id' => $item->default_store_id,
                'memo' => $item->memo,
                'status' => '未購入',
                'added_by_user_id' => $user->id,
            ]);
        }

        return redirect()->route('items.index')->with('success', '「' . $item->name . '」をおうちの在庫に登録しました！');
    }

    public function updateStock(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $change = (int) $request->input('change', 0);
        $newStock = max(0, $item->stock_quantity + $change);
        $item->stock_quantity = $newStock;
        $item->save();

        return back()->with('success', "「{$item->name}」の在庫を {$newStock} 個に更新しました。");
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $name = $item->name;
        $item->delete();

        return back()->with('success', "「{$name}」を削除しました。");
    }
}
