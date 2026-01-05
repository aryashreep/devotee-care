<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\OtpService;
use Mockery;

class OtpLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_otp()
    {
        $user = User::factory()->create([
            'mobile_number' => '1234567890',
        ]);

        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);

        $otpServiceMock->shouldReceive('generateAndSendOtp')->once()->with(Mockery::on(function ($arg) use ($user) {
            return $arg->id === $user->id;
        }));

        $response = $this->post(route('login.request-otp'), [
            'mobile_number' => '1234567890',
        ]);

        $response->assertRedirect(route('login.otp.show'));
        $response->assertSessionHas('mobile_number', '1234567890');

        $otpServiceMock->shouldReceive('verifyOtp')->once()->with('1234567890', '123421')->andReturn(true);

        $response = $this->post(route('login.otp.verify'), [
            'otp' => '123421',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_resend_otp()
    {
        $user = User::factory()->create([
            'mobile_number' => '1234567890',
        ]);

        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);

        $this->withSession(['mobile_number' => '1234567890']);

        $otpServiceMock->shouldReceive('generateAndSendOtp')->once()->with(Mockery::on(function ($arg) use ($user) {
            return $arg->id === $user->id;
        }));

        $response = $this->post(route('login.otp.resend'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'A new OTP has been sent to your mobile number and email.');
    }
}
