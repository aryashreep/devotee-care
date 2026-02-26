<?php

namespace Tests\Feature\Auth;

use App\Models\BhaktiSadan;
use App\Models\BloodGroup;
use App\Models\Education;
use App\Models\Language;
use App\Models\Profession;
use App\Models\Seva;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FullRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_conditional_validation_rules_are_enforced_in_step4(): void
    {
        Storage::fake('public');

        $state = State::query()->firstOrFail();
        $education = Education::query()->firstOrFail();
        $profession = Profession::query()->firstOrFail();
        $language = Language::query()->firstOrFail();
        $bloodGroup = BloodGroup::query()->firstOrFail();
        $bhaktiSadan = BhaktiSadan::query()->firstOrFail();
        $seva = Seva::query()->firstOrFail();

        $this->post(route('register.step1.store'), [
            'full_name' => 'Test User',
            'gender' => 'Male',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
        ]);

        $this->get(route('register.step2.show'));
        $this->post(route('register.step2.store'), [
            'mobile_number' => '9123456789',
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

        $this->post(route('register.step3.store'), [
            'education_id' => $education->id,
            'profession_id' => $profession->id,
            'blood_group_id' => $bloodGroup->id,
            'languages' => [$language->id],
        ]);

        $response = $this->post(route('register.step4.store'), [
            'initiated' => '1',
            'rounds' => 16,
            'second_initiation' => '0',
            'bhakti_sadan_id' => $bhaktiSadan->id,
            'life_membership' => '0',
            'services' => [$seva->id],
        ]);

        $response->assertSessionHasErrors(['initiated_name', 'spiritual_master']);
    }
}
