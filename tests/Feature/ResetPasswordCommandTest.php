<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_command_updates_user_password_by_mobile(): void
    {
        $user = User::factory()->create([
            'mobile_number' => '9000000000',
            'password' => Hash::make('old-password'),
        ]);

        $this->artisan('auth:reset-password', ['identifier' => '9000000000'])
            ->expectsQuestion('Enter the new password', 'new-password')
            ->expectsQuestion('Confirm the new password', 'new-password')
            ->assertExitCode(0);

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_reset_password_command_updates_user_password_by_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('old-password'),
        ]);

        $this->artisan('auth:reset-password', ['identifier' => 'test@example.com'])
            ->expectsQuestion('Enter the new password', 'new-password')
            ->expectsQuestion('Confirm the new password', 'new-password')
            ->assertExitCode(0);

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_reset_password_command_fails_on_password_mismatch(): void
    {
        $user = User::factory()->create([
            'mobile_number' => '9000000000',
            'password' => Hash::make('old-password'),
        ]);

        $this->artisan('auth:reset-password', ['identifier' => '9000000000'])
            ->expectsQuestion('Enter the new password', 'new-password')
            ->expectsQuestion('Confirm the new password', 'different-password')
            ->expectsOutput('Passwords do not match.')
            ->assertExitCode(1);

        $this->assertTrue(Hash::check('old-password', $user->refresh()->password));
    }

    public function test_reset_password_command_fails_if_user_not_found(): void
    {
        $this->artisan('auth:reset-password', ['identifier' => 'nonexistent'])
            ->expectsOutput('User with identifier [nonexistent] not found.')
            ->assertExitCode(1);
    }
}
