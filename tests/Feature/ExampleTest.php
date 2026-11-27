<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_home(): void
    {
        $user = User::factory()->create();
        $group = Group::create(['name' => 'テストファミリー']);
        Membership::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
