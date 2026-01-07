<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Education;
use App\Models\Profession;
use App\Models\Language;
use App\Models\ShikshaLevel;
use App\Models\BhaktiSadan;
use App\Models\Seva;
use App\Models\State;
use App\Services\OtpService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function a_user_can_register_through_the_multi_step_form()
    {
        Storage::fake('public');

        // Seed necessary data
        $education = Education::factory()->create();
        $profession = Profession::factory()->create();
        $language = Language::factory()->create();
        $shikshaLevel = ShikshaLevel::factory()->create();
        $bhaktiSadan = BhaktiSadan::factory()->create();
        $seva = Seva::factory()->create();
        $state = State::factory()->create();
        $bloodGroup = \App\Models\BloodGroup::factory()->create();

        // Step 1
        $response = $this->post(route('register.step1.store'), [
            'full_name' => 'Test User',
            'gender' => 'Male',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
        ]);
        $response->assertRedirect(route('register.step2.show'));

        // Mock OtpService
        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);

        $otpServiceMock->shouldReceive('hasTooManyAttempts')->once()->andReturn(false);
        $otpServiceMock->shouldReceive('generateAndSendOtp')->once()->andReturn(true);

        // Step 2
        $response = $this->withSession(['step1' => session('step1')])->post(route('register.step2.store'), [
            'email' => 'test@example.com',
            'mobile_number' => '9876543210',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => $state->id,
            'pincode' => '12345',
        ]);
        $response->assertRedirect(route('register.otp.show'));

        $otpServiceMock->shouldReceive('verifyOtp')->once()->with('9876543210', '123456')->andReturn(true);

        // OTP Verification
        $response = $this->withSession(session()->all())->post(route('register.otp.verify'), [
            'otp' => '123456',
        ]);
        $response->assertRedirect(route('register.step3.show'));

        // Step 3
        $response = $this->withSession(session()->all())->post(route('register.step3.store'), [
            'education_id' => $education->id,
            'profession_id' => $profession->id,
            'languages' => [$language->id],
            'blood_group_id' => $bloodGroup->id,
        ]);

        $response->assertRedirect(route('register.step4.show'));

        // Step 4
        $response = $this->withSession(session()->all())->post(route('register.step4.store'), [
            'initiated' => false,
            'rounds' => 108,
            'shiksha_levels' => [$shikshaLevel->id],
            'second_initiation' => false,
            'bhakti_sadan_id' => $bhaktiSadan->id,
            'life_membership' => false,
            'services' => [$seva->id],
        ]);
        $response->assertRedirect(route('register.step5.show'));

        // Step 5
        $response = $this->withSession(session()->all())->post(route('register.step5.store'), [
            'disclaimer' => true,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'mobile_number' => '9876543210',
        ]);

        $user = User::where('mobile_number', '9876543210')->first();

        $this->assertDatabaseHas('user_shiksha_level', [
            'user_id' => $user->id,
            'shiksha_level_id' => $shikshaLevel->id,
        ]);
    }

    /** @test */
    public function user_can_resend_otp_during_registration()
    {
        $state = State::factory()->create();
        $sessionData = [
            'step1' => ['full_name' => 'Test User', 'gender' => 'Male', 'photo' => 'photo.jpg', 'date_of_birth' => '1990-01-01', 'marital_status' => 'Single'],
            'step2' => ['email' => 'test@example.com', 'mobile_number' => '9876543210', 'address' => '123 Main St', 'city' => 'Anytown', 'state' => $state->id, 'pincode' => '12345'],
        ];
        $this->withSession($sessionData);

        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);

        $otpServiceMock->shouldReceive('generateAndSendOtp')->once()->andReturn(true);

        $response = $this->post(route('register.otp.resend'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'A new OTP has been sent to your mobile number and email.');
    }

    /** @test */
    public function rate_limiting_is_enforced_during_registration()
    {
        $state = State::factory()->create();
        $sessionData = [
            'step1' => ['full_name' => 'Test User', 'gender' => 'Male', 'photo' => 'photo.jpg', 'date_of_birth' => '1990-01-01', 'marital_status' => 'Single'],
        ];
        $this->withSession($sessionData);

        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);
        $otpServiceMock->shouldReceive('hasTooManyAttempts')->andReturn(true);

        $response = $this->post(route('register.step2.store'), [
            'email' => 'test@example.com',
            'mobile_number' => '9876543210',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => $state->id,
            'pincode' => '12345',
        ]);

        $response->assertSessionHasErrors('mobile_number');
    }

    /** @test */
    public function conditional_validation_rules_are_enforced()
    {
        $bhaktiSadan = BhaktiSadan::factory()->create();
        $seva = Seva::factory()->create();
        $language = Language::factory()->create();
        $education = Education::factory()->create();
        $profession = Profession::factory()->create();
        $bloodGroup = \App\Models\BloodGroup::factory()->create();
        $state = State::factory()->create();

        $sessionData = [
            'step1' => ['full_name' => 'Test User', 'gender' => 'Male', 'photo' => 'photo.jpg', 'date_of_birth' => '1990-01-01', 'marital_status' => 'Single'],
            'step2' => ['mobile_number' => '1234567891', 'address' => '123 Main St', 'city' => 'Anytown', 'state' => $state->id, 'pincode' => '12345'],
            'otp_verified' => true,
            'step3' => ['education_id' => $education->id, 'profession_id' => $profession->id, 'languages' => [$language->id], 'blood_group_id' => $bloodGroup->id],
        ];

        // Test missing initiated_name and spiritual_master when initiated
        session()->put($sessionData);
        session()->put('url.previous', route('register.step3.show'));
        $response = $this->post(route('register.step4.store'), [
            'initiated' => '1',
            'rounds' => 108,
            'shiksha_levels' => [],
            'second_initiation' => '0',
            'bhakti_sadan_id' => $bhaktiSadan->id,
            'life_membership' => '0',
            'services' => [$seva->id],
        ]);
        $response->assertSessionHasErrors(['initiated_name', 'spiritual_master']);

        // Test missing life_member_no and temple when life_membership is true
        session()->put($sessionData);
        session()->put('url.previous', route('register.step3.show'));
        $response = $this->post(route('register.step4.store'), [
            'initiated' => '0',
            'rounds' => 108,
            'shiksha_levels' => [],
            'second_initiation' => '0',
            'bhakti_sadan_id' => $bhaktiSadan->id,
            'life_membership' => '1',
            'services' => [$seva->id],
        ]);
        $response->assertSessionHasErrors(['life_member_no', 'temple']);
    }
}
