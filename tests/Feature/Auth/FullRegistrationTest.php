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

class FullRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function a_user_can_register_by_filling_out_all_fields()
    {
        Storage::fake('public');

        $education = Education::inRandomOrder()->first();
        $profession = Profession::inRandomOrder()->first();
        $language = Language::inRandomOrder()->first();
        $shikshaLevel = ShikshaLevel::inRandomOrder()->first();
        $bhaktiSadan = BhaktiSadan::inRandomOrder()->first();
        $seva = Seva::inRandomOrder()->first();
        $state = State::inRandomOrder()->first();
        $bloodGroup = \App\Models\BloodGroup::inRandomOrder()->first();

        // Step 1: Personal Details
        $response = $this->post(route('register.step1.store'), [
            'full_name' => 'Full Test User',
            'gender' => 'Female',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'date_of_birth' => '1992-02-02',
            'marital_status' => 'Married',
            'marriage_anniversary_date' => '2015-11-20',
        ]);
        $response->assertRedirect(route('register.step2.show'));

        // Mock OtpService
        $otpServiceMock = Mockery::mock(OtpService::class);
        $this->app->instance(OtpService::class, $otpServiceMock);
        $otpServiceMock->shouldReceive('generateAndSendOtp')->once();

        // Step 2: Contact Details
        $response = $this->withSession(session()->all())->post(route('register.step2.store'), [
            'email' => 'fulltest@example.com',
            'mobile_number' => '9876543210',
            'address' => '456 Park Ave',
            'city' => 'Metropolis',
            'state' => $state->id,
            'pincode' => '54321',
            'country' => 'Testland',
        ]);
        $response->assertRedirect(route('register.otp.show'));

        $otpServiceMock->shouldReceive('verifyOtp')->once()->with('9876543210', '123456')->andReturn(true);

        // OTP Verification
        $response = $this->withSession(session()->all())->post(route('register.otp.verify'), [
            'otp' => '123456',
        ]);
        $response->assertRedirect(route('register.step3.show'));

        // Step 3: Professional & Dependant Details
        $response = $this->withSession(session()->all())->post(route('register.step3.store'), [
            'education_id' => $education->id,
            'profession_id' => $profession->id,
            'blood_group_id' => $bloodGroup->id,
            'languages' => [$language->id],
            'dependants' => [
                [
                    'name' => 'Test Dependant',
                    'age' => 10,
                    'gender' => 'Male',
                    'dob' => '2014-01-15'
                ]
            ],
        ]);
        $response->assertRedirect(route('register.step4.show'));

        // Step 4: Spiritual Details
        $response = $this->withSession(session()->all())->post(route('register.step4.store'), [
            'initiated' => true,
            'initiated_name' => 'Initiated Das',
            'spiritual_master' => 'Test Spiritual Master',
            'rounds' => 16,
            'shiksha_levels' => [$shikshaLevel->id],
            'second_initiation' => true,
            'bhakti_sadan_id' => $bhaktiSadan->id,
            'life_membership' => true,
            'life_member_no' => 'LM-TEST-999',
            'temple' => 'Test Temple',
            'services' => [$seva->id],
        ]);
        $response->assertRedirect(route('register.step5.show'));

        // Step 5: Disclaimer
        $response = $this->withSession(session()->all())->post(route('register.step5.store'), [
            'disclaimer' => true,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user = User::where('mobile_number', '9876543210')->first();
        $this->assertNotNull($user);

        $this->assertEquals('Full Test User', $user->name);
        $this->assertEquals('fulltest@example.com', $user->email);
        $this->assertTrue($user->hasRole('Devotee'));
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
            'step3' => ['education_id' => $education->id, 'profession_id' => $profession->id, 'languages' => [$language->id], 'blood_group_id' => $bloodGroup->id],
        ];

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
    }
}
