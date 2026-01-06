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

    /** @test */
    public function user_can_login_with_valid_otp()
    {
        $user = User::factory()->create([
            'mobile_number' => '1234567890',
        ]);

        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);

        $otpServiceMock->shouldReceive('hasTooManyAttempts')->once()->with('1234567890')->andReturn(false);
        $otpServiceMock->shouldReceive('generateAndSendOtp')->once()->with(Mockery::on(function ($arg) use ($user) {
            return $arg->id === $user->id;
        }));

        $response = $this->post(route('login.request-otp'), [
            'mobile_number' => '1234567890',
        ]);

        $response->assertRedirect(route('login.otp.show'));
        $response->assertSessionHas('mobile_number', '1234567890');

        $otpServiceMock->shouldReceive('verifyOtp')->once()->with('1234567890', '123456')->andReturn(true);

        $response = $this->post(route('login.otp.verify'), [
            'otp' => '123456',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_can_resend_otp()
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

    /** @test */
    public function rate_limiting_is_enforced()
    {
        $user = User::factory()->create([
            'mobile_number' => '1234567890',
        ]);

        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);
        $otpServiceMock->shouldReceive('hasTooManyAttempts')->andReturn(true);

        $response = $this->post(route('login.request-otp'), ['mobile_number' => '1234567890']);

        $response->assertSessionHasErrors('mobile_number');
    }
}
