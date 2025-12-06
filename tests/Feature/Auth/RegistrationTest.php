<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Education;
use App\Models\Profession;
use App\Models\Language;
use App\Models\ShikshaLevel;
use App\Models\BhaktiSadan;
use App\Models\Seva;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

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

        $response = $this->post(route('register.step1.store'), [
            'full_name' => 'Test User',
            'gender' => 'Male',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('register.step2.show'));

        $response = $this->withSession(['step1' => session('step1')])->post(route('register.step2.store'), [
            'mobile_number' => '1234567890',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'Anystate',
            'pincode' => '12345',
        ]);

        $response->assertRedirect(route('register.step3.show'));

        $bloodGroup = \App\Models\BloodGroup::factory()->create();
        $response = $this->withSession(session()->all())->post(route('register.step3.store'), [
            'education_id' => $education->id,
            'profession_id' => $profession->id,
            'languages' => [$language->id],
            'blood_group_id' => $bloodGroup->id,
        ]);

        $response->assertRedirect(route('register.step4.show'));

        $response = $this->withSession(session()->all())->post(route('register.step4.store'), [
            'initiated' => false,
            'rounds' => 16,
            'shiksha_levels' => [$shikshaLevel->id],
            'second_initiation' => false,
            'bhakti_sadan_id' => $bhaktiSadan->id,
            'life_membership' => false,
            'services' => [$seva->id],
        ]);

        $response->assertRedirect(route('register.step5.show'));

        $response = $this->withSession(session()->all())->post(route('register.step5.store'), [
            'disclaimer' => true,
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'mobile_number' => '1234567890',
        ]);

        $user = User::where('mobile_number', '1234567890')->first();
        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas('user_shiksha_level', [
            'user_id' => $user->id,
            'shiksha_level_id' => $shikshaLevel->id,
        ]);
    }

    /** @test */
    public function conditional_validation_rules_are_enforced()
    {
        $bhaktiSadan = BhaktiSadan::factory()->create();
        $seva = Seva::factory()->create();
        $language = Language::factory()->create();
        $education = Education::factory()->create();
        $profession = Profession::factory()->create();

        $sessionData = [
            'step1' => ['full_name' => 'Test User', 'gender' => 'Male', 'photo' => 'photo.jpg', 'date_of_birth' => '1990-01-01', 'marital_status' => 'Single', 'password' => 'Password123'],
            'step2' => ['mobile_number' => '1234567891', 'address' => '123 Main St', 'city' => 'Anytown', 'state' => 'Anystate', 'pincode' => '12345'],
            'step3' => ['education_id' => $education->id, 'profession_id' => $profession->id, 'languages' => [$language->id]],
        ];

        // Test missing spiritual_master_name when initiated
        session()->put($sessionData);
        session()->put('url.previous', route('register.step3.show'));
        $response = $this->post(route('register.step4.store'), [
                'initiated' => true,
                'rounds' => null,
                'shiksha_levels' => [],
                'second_initiation' => false,
                'bhakti_sadan_id' => $bhaktiSadan->id,
                'life_membership' => false,
                'services' => [$seva->id],
            ]);
        $response->assertSessionHasErrors('spiritual_master_name');

        // Test missing rounds when not initiated
        session()->put($sessionData);
        session()->put('url.previous', route('register.step3.show'));
        $response = $this->post(route('register.step4.store'), [
                'initiated' => false,
                'spiritual_master_name' => null,
                'shiksha_levels' => [],
                'second_initiation' => false,
                'bhakti_sadan_id' => $bhaktiSadan->id,
                'life_membership' => false,
                'services' => [$seva->id],
            ]);
        $response->assertSessionHasErrors('rounds');

        // Test missing life_member_no and temple when life_membership is true
        session()->put($sessionData);
        session()->put('url.previous', route('register.step3.show'));
        $response = $this->post(route('register.step4.store'), [
                'initiated' => false,
                'rounds' => 16,
                'shiksha_levels' => [],
                'second_initiation' => false,
                'bhakti_sadan_id' => $bhaktiSadan->id,
                'life_membership' => true,
                'services' => [$seva->id],
            ]);
        $response->assertSessionHasErrors(['life_member_no', 'temple']);
    }
}
