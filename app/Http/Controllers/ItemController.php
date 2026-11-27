<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Category;
use App\Models\Item;
use App\Models\ShoppingEntry;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $group = $this->ensureCurrentGroup();
        $selectedCategory = $request->query('category', 'all');

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
        $group = $this->ensureCurrentGroup();

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
            'photo_captured' => 'nullable|string',
            'product_url' => 'nullable|url|max:2048',
            'is_recurring' => 'nullable|boolean',
            'purchase_cycle_days' => 'nullable|integer|min:1|max:365',
            'stock_quantity' => 'nullable|integer|min:0|max:9999',
        ], [
            'name.required' => '商品名を入力してください。',
            'name.max' => '商品名は100文字以内で入力してください。',
            'product_url.url' => '商品リンクは有効なURL（https://...）で入力してください。',
            'purchase_cycle_days.min' => '購入周期は1日以上で入力してください。',
            'purchase_cycle_days.max' => '購入周期は365日以内で入力してください。',
        ])->validate();

        $group = $this->ensureCurrentGroup();
        $user = $this->currentUser();

        // グループ所有のカテゴリー・店舗であることを保証
        $categoryId = $request->input('category_id');
        if ($categoryId && ! Category::where('group_id', $group->id)->where('id', $categoryId)->exists()) {
            $categoryId = null;
        }

        $defaultStoreId = $request->input('default_store_id');
        if ($defaultStoreId && ! Store::where('group_id', $group->id)->where('id', $defaultStoreId)->exists()) {
            $defaultStoreId = null;
        }

        $isRecurring = $request->boolean('is_recurring', true);
        $cycleDays = $request->input('purchase_cycle_days', 30);
        $photoPath = $this->storeCapturedPhoto($request->input('photo_captured'));

        $item = Item::create([
            'group_id' => $group->id,
            'name' => $request->input('name'),
            'category_id' => $categoryId,
            'default_store_id' => $defaultStoreId,
            'memo' => $request->input('memo'),
            'photo_original_ref' => $photoPath,
            'photo_cutout_ref' => $photoPath,
            'photo_display' => $request->input('photo_display', 'cutout'),
            'product_url' => $request->input('product_url'),
            'is_recurring' => $isRecurring,
            'purchase_cycle_days' => $isRecurring ? $cycleDays : null,
            'stock_quantity' => $request->input('stock_quantity', 1),
            'reorder_level' => 1,
            'created_by_user_id' => $user->id,
        ]);

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
        $group = $this->ensureCurrentGroup();
        $item = Item::where('group_id', $group->id)->findOrFail($id);

        $change = (int) $request->input('change', 0);
        $newStock = max(0, $item->stock_quantity + $change);
        $item->stock_quantity = $newStock;
        $item->save();

        return back()->with('success', "「{$item->name}」の在庫を {$newStock} 個に更新しました。");
    }

    public function destroy($id)
    {
        $group = $this->ensureCurrentGroup();
        $item = Item::where('group_id', $group->id)->findOrFail($id);
        $name = $item->name;
        $photoRef = $item->photo_original_ref;

        DB::transaction(function () use ($item) {
            ShoppingEntry::where('item_id', $item->id)->update(['item_id' => null]);
            AppNotification::where('item_id', $item->id)->delete();
            DB::table('stock_movements')->where('item_id', $item->id)->update(['item_id' => null]);
            $item->delete();
        });

        if ($photoRef && Str::startsWith($photoRef, 'uploads/items/')) {
            $fullPath = public_path($photoRef);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        return back()->with('success', "「{$name}」を削除しました。");
    }

    /**
     * Base64 DataURL で送信された画像を public/uploads/items 配下へ保存し、DB格納用の相対パスを返す。
     */
    private function storeCapturedPhoto(?string $photoInput): ?string
    {
        if (! $photoInput) {
            return null;
        }

        if (preg_match('/^data:image\/(png|jpe?g|webp|gif);base64,(.+)$/i', $photoInput, $matches)) {
            $ext = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
            $binary = base64_decode($matches[2], true);

            if ($binary === false || strlen($binary) > 5 * 1024 * 1024) {
                return null;
            }

            $relativeDir = 'uploads/items';
            $targetDir = public_path($relativeDir);
            File::ensureDirectoryExists($targetDir);

            $filename = Str::uuid()->toString() . '.' . $ext;
            File::put($targetDir . DIRECTORY_SEPARATOR . $filename, $binary);

            return $relativeDir . '/' . $filename;
        }

        return Str::limit($photoInput, 255, '');
    }
}
