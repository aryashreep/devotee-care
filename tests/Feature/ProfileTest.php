<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_see_their_own_profile()
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertViewIs('profile.show')
            ->assertSee($admin->name);
    }

    /** @test */
    public function a_non_admin_user_cannot_see_the_profile_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertForbidden();
    }

    /** @test */
    public function an_unauthenticated_user_is_redirected_to_login()
    {
        $this->get(route('profile.show'))
            ->assertRedirect(route('login'));
    }
}
