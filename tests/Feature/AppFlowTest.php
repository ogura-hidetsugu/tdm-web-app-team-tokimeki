<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\ShoppingEntry;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AppFlowTest extends TestCase
{
    use RefreshDatabase;

    private function registerAndGetUser(string $email = 'tester@example.com'): User
    {
        $this->post('/register', [
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('home'));

        return User::where('email', $email)->firstOrFail();
    }

    public function test_home_quick_add_uses_group_stores_and_redirects_home(): void
    {
        $user = $this->registerAndGetUser();
        $group = $user->primaryGroup();
        $supermarket = Store::where('group_id', $group->id)->where('name', 'スーパー')->firstOrFail();

        $this->get('/')->assertStatus(200)->assertSee('value="' . $supermarket->id . '"', false);

        $response = $this->post('/shopping', [
            'name' => '牛乳',
            'store_id' => $supermarket->id,
            'redirect_to' => 'home',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('shopping_entries', [
            'group_id' => $group->id,
            'name' => '牛乳',
            'store_id' => $supermarket->id,
            'status' => '未購入',
        ]);
    }

    public function test_item_registration_saves_captured_photo_and_can_delete_with_related_entries(): void
    {
        $user = $this->registerAndGetUser();
        $group = $user->primaryGroup();
        $category = Category::where('group_id', $group->id)->firstOrFail();
        $store = Store::where('group_id', $group->id)->where('name', 'Amazon')->firstOrFail();

        // 1x1 透明PNGのDataURL
        $tinyPng = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        $this->get('/items/create')->assertStatus(200);

        $response = $this->post('/items', [
            'name' => '天然水 2L',
            'category_id' => $category->id,
            'default_store_id' => $store->id,
            'memo' => '備蓄用',
            'photo_display' => 'cutout',
            'photo_captured' => $tinyPng,
            'product_url' => 'https://example.com/water',
            'is_recurring' => '1',
            'purchase_cycle_days' => 14,
            'stock_quantity' => 2,
            'add_to_shopping_list' => '1',
        ]);

        $response->assertRedirect(route('items.index'));

        $item = Item::where('group_id', $group->id)->where('name', '天然水 2L')->firstOrFail();
        $this->assertNotNull($item->photo_original_ref);
        $this->assertStringStartsWith('uploads/items/', $item->photo_original_ref);
        $this->assertLessThanOrEqual(255, strlen($item->photo_original_ref));
        $this->assertTrue(File::exists(public_path($item->photo_original_ref)));

        $this->assertDatabaseHas('shopping_entries', [
            'group_id' => $group->id,
            'item_id' => $item->id,
            'name' => '天然水 2L',
        ]);

        // 在庫増減テスト（0未満にならないことも確認）
        $this->post('/items/' . $item->id . '/stock', ['change' => -5])->assertRedirect();
        $this->assertSame(0, $item->fresh()->stock_quantity);

        $this->post('/items/' . $item->id . '/stock', ['change' => 3])->assertRedirect();
        $this->assertSame(3, $item->fresh()->stock_quantity);

        // 関連する ShoppingEntry があっても外部キー制約エラーなく削除できること
        $photoPath = public_path($item->photo_original_ref);
        $this->delete('/items/' . $item->id)->assertRedirect();

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
        $this->assertFalse(File::exists($photoPath));
    }

    public function test_shopping_list_sort_by_store_keeps_null_store_items_and_toggles_status(): void
    {
        $user = $this->registerAndGetUser();
        $group = $user->primaryGroup();

        // store_id = null のアイテムを作成
        $entry = ShoppingEntry::create([
            'group_id' => $group->id,
            'name' => '店舗未設定アイテム',
            'store_id' => null,
            'status' => '未購入',
            'added_by_user_id' => $user->id,
        ]);

        $this->get('/shopping?sort_by_store=1')
            ->assertStatus(200)
            ->assertSee('店舗未設定アイテム');

        $this->get('/shopping/create')->assertStatus(200);

        // 購入済みへ切り替え
        $this->post('/shopping/' . $entry->id . '/toggle')->assertRedirect();
        $this->assertSame('購入済', $entry->fresh()->status);
        $this->assertSame($user->id, $entry->fresh()->purchased_by_user_id);

        // 未購入に戻す
        $this->post('/shopping/' . $entry->id . '/toggle')->assertRedirect();
        $this->assertSame('未購入', $entry->fresh()->status);

        // 削除
        $this->delete('/shopping/' . $entry->id)->assertRedirect();
        $this->assertDatabaseMissing('shopping_entries', ['id' => $entry->id]);
    }

    public function test_notifications_and_settings_are_persisted_and_family_works(): void
    {
        $user = $this->registerAndGetUser();
        $group = $user->primaryGroup();

        $item = Item::create([
            'group_id' => $group->id,
            'name' => 'たまご',
            'stock_quantity' => 1,
            'reorder_level' => 2,
            'is_recurring' => true,
            'purchase_cycle_days' => 7,
            'created_by_user_id' => $user->id,
        ]);

        $this->get('/notifications')->assertStatus(200)->assertSee('たまご');

        // ワンクリック追加
        $this->post('/notifications/add/' . $item->id)->assertRedirect();
        $this->assertDatabaseHas('shopping_entries', [
            'group_id' => $group->id,
            'item_id' => $item->id,
            'name' => 'たまご',
        ]);

        // 通知設定保存と永続化確認
        $this->post('/notifications/settings', [
            'notify_days_before' => 5,
            'auto_add_shopping' => '1',
        ])->assertRedirect();

        $this->get('/notifications')
            ->assertStatus(200)
            ->assertSee('<option value="5" selected>', false)
            ->assertSee('<option value="1" selected>', false);

        // 家族共有ページ
        $this->get('/family')->assertStatus(200);
        $this->post('/family/members', ['display_name' => 'おばあちゃん'])->assertRedirect();
        $this->assertDatabaseHas('users', ['display_name' => 'おばあちゃん']);
        $this->post('/family/reaction', ['reaction' => 'thanks'])->assertRedirect();
    }

    public function test_users_cannot_modify_other_groups_items_or_shopping_entries(): void
    {
        $userA = $this->registerAndGetUser('usera@example.com');
        $groupA = $userA->primaryGroup();

        $itemA = Item::create([
            'group_id' => $groupA->id,
            'name' => 'Aさんの洗剤',
            'stock_quantity' => 2,
            'created_by_user_id' => $userA->id,
        ]);

        $entryA = ShoppingEntry::create([
            'group_id' => $groupA->id,
            'name' => 'Aさんのパン',
            'status' => '未購入',
            'added_by_user_id' => $userA->id,
        ]);

        $this->post('/logout');
        $this->registerAndGetUser('userb@example.com');

        $this->post('/items/' . $itemA->id . '/stock', ['change' => 1])->assertNotFound();
        $this->delete('/items/' . $itemA->id)->assertNotFound();
        $this->post('/shopping/' . $entryA->id . '/toggle')->assertNotFound();
        $this->delete('/shopping/' . $entryA->id)->assertNotFound();
        $this->post('/notifications/add/' . $itemA->id)->assertNotFound();
    }
}
