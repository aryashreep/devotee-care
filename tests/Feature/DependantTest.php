<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Dependant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class DependantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Admin']);
    }

    public function test_admin_can_view_user_with_dependants_without_error()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $user = User::factory()->create();
        Dependant::create([
            'user_id' => $user->id,
            'name' => 'John Doe',
            'relationship' => 'Son',
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
        $admin->assignRole('Admin');

        $user = User::factory()->create();
        $dependant = Dependant::create([
            'user_id' => $user->id,
            'name' => 'Old Name',
            'relationship' => 'Son',
            'dob' => '2010-01-01',
        ]);

        $response = $this->actingAs($admin)->put("/users/{$user->id}/profile", [
            'dependants' => [
                [
                    'id' => $dependant->id,
                    'name' => 'New Name',
                    'relationship' => 'Daughter',
                    'dob' => '2011-02-02',
                ],
                [
                    'id' => null,
                    'name' => 'New Dependant',
                    'relationship' => 'Spouse',
                    'dob' => '1985-05-05',
                ]
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('dependants', [
            'id' => $dependant->id,
            'name' => 'New Name',
            'relationship' => 'Daughter',
        ]);
        $this->assertDatabaseHas('dependants', [
            'name' => 'New Dependant',
            'relationship' => 'Spouse',
        ]);
    }

    public function test_user_can_update_own_dependants()
    {
        $user = User::factory()->create();
        $user->assignRole('Devotee'); // Or whatever role is standard

        $dependant = Dependant::create([
            'user_id' => $user->id,
            'name' => 'Old Name',
            'relationship' => 'Son',
            'dob' => '2010-01-01',
        ]);

        $response = $this->actingAs($user)->put("/profile", [
            'dependants' => [
                [
                    'id' => $dependant->id,
                    'name' => 'Updated Name',
                    'relationship' => 'Son',
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
