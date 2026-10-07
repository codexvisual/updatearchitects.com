<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminSeeder::class);
        $this->seed(ContentSeeder::class);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/admin/projects')->assertRedirect(route('login'));
        $this->post('/admin/projects', [])->assertRedirect(route('login'));
        $this->get('/admin/hero-slides')->assertRedirect(route('login'));
        $this->get('/profile')->assertRedirect(route('login'));
    }

    public function test_authenticated_users_without_a_role_cannot_open_the_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_staff_role_cannot_open_the_admin(): void
    {
        $user = User::factory()->create();
        $user->assignRole('staff');

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/admin/projects')->assertForbidden();
    }

    public function test_editor_can_manage_content_but_not_users(): void
    {
        $user = User::factory()->create();
        $user->assignRole('editor');

        $this->actingAs($user)->get('/admin')->assertOk();
        $this->actingAs($user)->get('/admin/projects')->assertOk();
        $this->actingAs($user)->get('/admin/hero-slides')->assertOk();
        $this->actingAs($user)->get('/admin/users')->assertForbidden();
        $this->actingAs($user)->get('/admin/roles')->assertForbidden();
    }

    public function test_super_admin_can_manage_users_and_their_own_profile(): void
    {
        $superAdmin = User::where('email', 'admin@updatearchitects.com')->firstOrFail();

        $this->actingAs($superAdmin)->get('/admin')->assertOk();
        $this->actingAs($superAdmin)->get('/admin/users')->assertOk();
        $this->actingAs($superAdmin)->get('/admin/roles')->assertOk();
        $this->actingAs($superAdmin)->get('/profile')->assertOk();
    }
}
