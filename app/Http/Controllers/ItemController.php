<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function home()
    {
        $allItems = Item::latest()->get();

        $categories = collect(Item::CATEGORIES)->map(function ($category) use ($allItems) {
            $items = $allItems->where('category', $category);
            return [
                'name' => $category,
                'items' => $items,
                'count' => $items->count(),
                'total_quantity' => $items->sum('quantity'),
            ];
        });

        return view('home', compact('categories'));
    }

    public function create()
    {
        $categories = Item::CATEGORIES;
        $purchaseLocations = Item::PURCHASE_LOCATIONS;
        $purchaseCycles = Item::PURCHASE_CYCLES;

        return view('items.create', compact('categories', 'purchaseLocations', 'purchaseCycles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', Item::CATEGORIES),
            'purchase_location' => 'required|string|in:' . implode(',', Item::PURCHASE_LOCATIONS),
            'purchase_cycle' => 'required|string|in:' . implode(',', Item::PURCHASE_CYCLES),
            'memo' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
        ]);

        Item::create($validated);

        return redirect()->route('home')->with('success', '商品を登録しました！');
    }

    // 個数を +1 する
    public function increment(Item $item)
    {
        $item->increment('quantity');

        return redirect()->route('home')->with('success', "{$item->name} の個数を増やしました。");
    }

    // 個数を -1 する（最小1まで）
    public function decrement(Item $item)
    {
        if ($item->quantity > 1) {
            $item->decrement('quantity');
            return redirect()->route('home')->with('success', "{$item->name} の個数を減らしました。");
        }

        return redirect()->route('home')->with('success', '個数は1未満にはできません。削除したい場合は「削除」を押してください。');
    }

    // 商品を削除する
    public function destroy(Item $item)
    {
        $name = $item->name;
        $item->delete();

        return redirect()->route('home')->with('success', "{$name} を削除しました。");
    }
}