<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        $query = Product::query()->latest();

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $products = $query->get();

        return view('products.index', [
            'products' => $products,
            'selectedCategory' => $category,
        ]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'store'    => 'nullable|string|in:スーパー,薬局,コンビニ,ホームセンター,100円ショップ,Amazon,楽天,その他',
            'stock'    => 'required|integer|min:0',
            'url'      => 'nullable|url|max:2048',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'memo'     => 'nullable|string|max:1000',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // storage/app/public/products に保存
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'       => $validated['name'],
            'category'   => $validated['category'] ?? null,
            'store'      => $validated['store'] ?? null,
            'stock'      => $validated['stock'],
            'url'        => $validated['url'] ?? null,
            'image_path' => $imagePath,
            'memo'       => $validated['memo'] ?? null,
        ]);

        return redirect()->route('products.index')->with('success', '「くらすと」にアイテムを登録しました！');
    }
}