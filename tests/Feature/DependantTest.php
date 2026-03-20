<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Dependant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;

class DependantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Devotee']);
    }

    public function test_admin_can_view_user_with_dependants_without_error()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'Admin')->first());

        $user = User::factory()->create();
        Dependant::create([
            'user_id' => $user->id,
            'name' => 'John Doe',
            'age' => 10,
            'gender' => 'Male',
            'dob' => '2010-01-01',
        ]);

        $response = $this->actingAs($admin)->get("/users/{$user->id}/profile");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('01-01-2010'); // Assuming d-m-Y format in view
    }

    public function test_admin_can_update_dependants()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'Admin')->first());

        $user = User::factory()->create();
        $dependant = Dependant::create([
            'user_id' => $user->id,
            'name' => 'Old Name',
            'age' => 10,
            'gender' => 'Male',
            'dob' => '2010-01-01',
        ]);

        $response = $this->actingAs($admin)->put("/users/{$user->id}/profile", [
            'name' => 'User Name',
            'gender' => 'Male',
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
            'email' => 'user@example.com',
            'mobile_number' => '9886543210',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'pincode' => '123456',
            'education_id' => \App\Models\Education::factory()->create()->id,
            'profession_id' => \App\Models\Profession::factory()->create()->id,
            'blood_group_id' => \App\Models\BloodGroup::factory()->create()->id,
            'languages' => [\App\Models\Language::factory()->create()->id],
            'initiated' => 0,
            'rounds' => 0,
            'second_initiation' => 0,
            'bhakti_sadan_id' => \App\Models\BhaktiSadan::factory()->create()->id,
            'life_membership' => 0,
            'services' => [\App\Models\Seva::factory()->create()->id],
            'dependants' => [
                [
                    'id' => $dependant->id,
                    'name' => 'New Name',
                    'age' => 11,
                    'gender' => 'Female',
                    'dob' => '2011-02-02',
                ],
                [
                    'id' => null,
                    'name' => 'New Dependant',
                    'age' => 40,
                    'gender' => 'Female',
                    'dob' => '1985-05-05',
                ]
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('dependants', [
            'id' => $dependant->id,
            'name' => 'New Name',
            'gender' => 'Female',
        ]);
        $this->assertDatabaseHas('dependants', [
            'name' => 'New Dependant',
            'gender' => 'Female',
        ]);
    }

    public function test_user_can_update_own_dependants()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'Devotee')->first());

        $dependant = Dependant::create([
            'user_id' => $user->id,
            'name' => 'Old Name',
            'age' => 10,
            'gender' => 'Male',
            'dob' => '2010-01-01',
        ]);

        $response = $this->actingAs($user)->put("/profile", [
            'name' => 'User Name',
            'gender' => 'Male',
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
            'email' => 'user@example.com',
            'mobile_number' => '9886543210',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'pincode' => '123456',
            'education_id' => \App\Models\Education::factory()->create()->id,
            'profession_id' => \App\Models\Profession::factory()->create()->id,
            'blood_group_id' => \App\Models\BloodGroup::factory()->create()->id,
            'languages' => [\App\Models\Language::factory()->create()->id],
            'initiated' => 0,
            'rounds' => 0,
            'second_initiation' => 0,
            'bhakti_sadan_id' => \App\Models\BhaktiSadan::factory()->create()->id,
            'life_membership' => 0,
            'services' => [\App\Models\Seva::factory()->create()->id],
            'dependants' => [
                [
                    'id' => $dependant->id,
                    'name' => 'Updated Name',
                    'age' => 10,
                    'gender' => 'Male',
                    'dob' => '2010-01-01',
                ]
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('dependants', [
            'id' => $dependant->id,
            'name' => 'Updated Name',
        ]);
    }
}
