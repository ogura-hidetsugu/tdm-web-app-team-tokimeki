<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Group;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('おうちのストックを、かんたん管理');
        $response->assertSee('メールアドレス');
        $response->assertSee('パスワード');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('商品管理システム');
        $response->assertSee('メールアドレス');
        $response->assertSee('パスワード（確認）');
    }

    public function test_new_user_can_register_and_initial_group_is_created(): void
    {
        $response = $this->post('/register', [
            'email' => 'hanako@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $user = User::where('email', 'hanako@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('hanako', $user->display_name);
        $this->assertTrue(Hash::check('secret123', $user->password));

        $group = $user->primaryGroup();
        $this->assertInstanceOf(Group::class, $group);
        $this->assertGreaterThan(0, Store::where('group_id', $group->id)->count());
        $this->assertGreaterThan(0, Category::where('group_id', $group->id)->count());

        $this->get('/')->assertStatus(200)->assertSee('hanako');
    }

    public function test_register_validates_duplicate_email_and_password_confirmation(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->from('/register')->post('/register', [
            'email' => 'taken@example.com',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_existing_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'taro@example.com',
            'password' => Hash::make('password123'),
            'display_name' => 'たろう',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'taro@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password_or_inactive_account(): void
    {
        User::factory()->create([
            'email' => 'taro@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'taro@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $inactiveResponse = $this->from('/login')->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $inactiveResponse->assertRedirect('/login');
        $inactiveResponse->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_away_from_guest_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect('/');
        $this->actingAs($user)->get('/register')->assertRedirect('/');
    }
}
