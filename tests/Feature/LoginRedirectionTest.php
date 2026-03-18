<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_devotee_is_redirected_to_profile_after_login(): void
    {
        $this->get('/login');
        $devoteeRole = Role::where('name', 'Devotee')->first();
        $user = User::factory()->create([
            'password' => bcrypt('Password123!'),
            'mobile_number' => '9886543210' // Avoid suspicious mobile check
        ]);
        $user->roles()->attach($devoteeRole);

        $response = $this->post('/login', [
            'mobile_number' => '9886543210',
            'password' => 'Password123!',
            'captcha_answer' => session('login_captcha_challenge'),
        ]);

        $response->assertRedirect(route('my-profile.show'));
    }

    public function test_user_with_no_role_is_redirected_to_profile_after_login(): void
    {
        $this->get('/login');
        $user = User::factory()->create([
            'password' => bcrypt('Password123!'),
            'mobile_number' => '9886543211'
        ]);

        $response = $this->post('/login', [
            'mobile_number' => '9886543211',
            'password' => 'Password123!',
            'captcha_answer' => session('login_captcha_challenge'),
        ]);

        // Current behavior might be redirecting to dashboard which causes 403
        $response->assertRedirect(route('my-profile.show'));
    }

    public function test_root_route_redirects_non_admin_to_profile(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertRedirect(route('my-profile.show'));
    }

    public function test_admin_is_redirected_to_dashboard_after_login(): void
    {
        $this->get('/login');
        $adminRole = Role::where('name', 'Admin')->first();
        $user = User::factory()->create([
            'password' => bcrypt('Password123!'),
            'mobile_number' => '9886543212'
        ]);
        $user->roles()->attach($adminRole);

        $response = $this->post('/login', [
            'mobile_number' => '9886543212',
            'password' => 'Password123!',
            'captcha_answer' => session('login_captcha_challenge'),
        ]);

        $response->assertRedirect(route('dashboard'));
    }
}
