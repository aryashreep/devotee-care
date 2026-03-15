<?php

namespace Tests\Feature\Auth;

use App\Models\BhaktiSadan;
use App\Models\BloodGroup;
use App\Models\Education;
use App\Models\Language;
use App\Models\Profession;
use App\Models\Role;
use App\Models\Seva;
use App\Models\ShikshaLevel;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_register_with_mobile_and_password(): void
    {
        Storage::fake('public');

        $state = State::query()->firstOrFail();
        $education = Education::query()->firstOrFail();
        $profession = Profession::query()->firstOrFail();
        $language = Language::query()->firstOrFail();
        $bloodGroup = BloodGroup::query()->firstOrFail();
        $shikshaLevel = ShikshaLevel::query()->firstOrFail();
        $bhaktiSadan = BhaktiSadan::query()->firstOrFail();
        $seva = Seva::query()->firstOrFail();

        $this->post(route('register.step1.store'), [
            'full_name' => 'Full Test User',
            'gender' => 'Female',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'date_of_birth' => '1992-02-02',
            'marital_status' => 'Married',
            'marriage_anniversary_date' => '2015-11-20',
        ])->assertRedirect(route('register.step2.show'));

        $this->get(route('register.step2.show'));

        $this->post(route('register.step2.store'), [
            'email' => 'fulltest@example.com',
            'mobile_number' => '9876543210',
            'password' => 'SecurePass!1234',
            'password_confirmation' => 'SecurePass!1234',
            'address' => '456 Park Ave',
            'city' => 'Metropolis',
            'state' => $state->id,
            'pincode' => '54321',
            'country' => 'India',
            'captcha_answer' => session('register_captcha_challenge'),
            'website_url' => '',
        ])->assertRedirect(route('register.step3.show'));

        $this->post(route('register.step3.store'), [
            'education_id' => $education->id,
            'profession_id' => $profession->id,
            'blood_group_id' => $bloodGroup->id,
            'languages' => [$language->id],
            'dependants' => [[
                'name' => 'Test Dependant',
                'age' => 10,
                'gender' => 'Male',
                'dob' => '2014-01-15',
            ]],
        ])->assertRedirect(route('register.step4.show'));

        $this->post(route('register.step4.store'), [
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
        ])->assertRedirect(route('register.step5.show'));

        $this->post(route('register.step5.store'), [
            'disclaimer' => true,
        ])->assertRedirect(route('login'));

        $user = User::where('mobile_number', '9876543210')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('SecurePass!1234', $user->password));
        $this->assertTrue($user->roles->contains('name', 'Devotee'));
    }

    public function test_registration_rejects_fake_mobile_number_patterns(): void
    {
        Storage::fake('public');
        $state = State::query()->firstOrFail();

        $this->post(route('register.step1.store'), [
            'full_name' => 'Bot User',
            'gender' => 'Male',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
        ]);

        $this->get(route('register.step2.show'));

        $response = $this->from(route('register.step2.show'))->post(route('register.step2.store'), [
            'email' => 'bot@example.com',
            'mobile_number' => '9999999999',
            'password' => 'SecurePass!1234',
            'password_confirmation' => 'SecurePass!1234',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => $state->id,
            'pincode' => '12345',
            'country' => 'India',
            'captcha_answer' => session('register_captcha_challenge'),
            'website_url' => '',
        ]);

        $response->assertRedirect(route('register.step2.show'));
        $response->assertSessionHasErrors('mobile_number');
    }
}
