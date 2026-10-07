<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_confirmation_must_match(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test Facility',
            'email' => 'facility@example.com',
            'address' => '123 Main St',
            'password' => 'secret123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);
        $this->assertStringContainsString('confirmation', session('errors')->first('password'));
    }
}
