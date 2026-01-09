<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_password()
    {
        $user = User::factory()->create([
            'mobile_number' => '1234567890',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.password'), [
            'mobile_number' => '1234567890',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'mobile_number' => '1234567890',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.password'), [
            'mobile_number' => '1234567890',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('mobile_number');
        $this->assertGuest();
    }

    /** @test */
    public function user_can_request_password_reset_link()
    {
        $user = User::factory()->create();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
    }

    /** @test */
    public function user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
