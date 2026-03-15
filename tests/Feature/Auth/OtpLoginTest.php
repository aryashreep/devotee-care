<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_mobile_number_and_password(): void
    {
        $role = Role::create(['name' => 'Devotee']);
        $user = User::factory()->create([
            'mobile_number' => '9876543210',
            'password' => Hash::make('StrongPass!123'),
        ]);
        $user->roles()->attach($role);

        $this->get(route('login'));

        $response = $this->post(route('login.attempt'), [
            'mobile_number' => '9876543210',
            'password' => 'StrongPass!123',
            'captcha_answer' => session('login_captcha_challenge'),
            'company_name' => '',
        ]);

        $response->assertRedirect(route('my-profile.show'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_honeypot_submission(): void
    {
        Role::create(['name' => 'Devotee']);
        User::factory()->create([
            'mobile_number' => '9876543210',
            'password' => Hash::make('StrongPass!123'),
        ]);

        $this->get(route('login'));

        $response = $this->from(route('login'))->post(route('login.attempt'), [
            'mobile_number' => '9876543210',
            'password' => 'StrongPass!123',
            'captcha_answer' => session('login_captcha_challenge'),
            'company_name' => 'bot-data',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('company_name');
        $this->assertGuest();
    }
}
