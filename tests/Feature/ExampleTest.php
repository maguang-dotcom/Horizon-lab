<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_facility_dashboard_renders_for_authenticated_facility_user(): void
    {
        $facilityUser = new User([
            'name' => 'Facility Manager',
            'email' => 'facility@example.test',
            'password' => 'password',
            'role' => 'facility',
        ]);
        $facilityUser->save();
        Facility::create([
            'user_id' => $facilityUser->id,
            'name' => 'Test Facility',
            'address' => 'Test address',
        ]);

        $response = $this->actingAs($facilityUser)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Health Facility Dashboard');
    }
}
