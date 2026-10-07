<?php

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('shows the facility and account names in settings', function () {
    $user = User::factory()->create([
        'name' => 'Facility Manager',
        'role' => 'facility',
    ]);
    $facility = Facility::create([
        'user_id' => $user->id,
        'name' => 'North Clinic',
    ]);

    $this->actingAs($user)
        ->get(route('facility.settings.edit'))
        ->assertOk()
        ->assertViewIs('HealthyFacility.settings')
        ->assertSee('value="Facility Manager"', false)
        ->assertSee('value="North Clinic"', false);
});

it('updates both names only for the signed-in facility', function () {
    $user = User::factory()->create(['role' => 'facility']);
    $facility = Facility::create([
        'user_id' => $user->id,
        'name' => 'Old Facility Name',
    ]);
    $otherUser = User::factory()->create(['role' => 'facility']);
    $otherFacility = Facility::create([
        'user_id' => $otherUser->id,
        'name' => 'Another Facility',
    ]);

    $this->actingAs($user)
        ->put(route('facility.settings.profile.update'), [
            'name' => 'New Account Name',
            'facility_name' => 'New Facility Name',
            'facility_id' => $otherFacility->id,
        ])
        ->assertRedirect()
        ->assertSessionHas('status', 'profile-updated');

    expect($user->fresh()->name)->toBe('New Account Name');
    expect($facility->fresh()->name)->toBe('New Facility Name');
    expect($otherFacility->fresh()->name)->toBe('Another Facility');
});

it('updates the password after verifying the current password', function () {
    $user = User::factory()->create([
        'role' => 'facility',
        'password' => Hash::make('OldPassword123!'),
    ]);

    $this->actingAs($user)
        ->put(route('facility.settings.password.update'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewPassword456!',
            'password_confirmation' => 'NewPassword456!',
        ])
        ->assertRedirect()
        ->assertSessionHas('status', 'password-updated');

    expect(Hash::check('NewPassword456!', $user->fresh()->password))->toBeTrue();
});

it('keeps the existing password when the current password is incorrect', function () {
    $user = User::factory()->create([
        'role' => 'facility',
        'password' => Hash::make('OldPassword123!'),
    ]);

    $this->actingAs($user)
        ->from(route('facility.settings.edit'))
        ->put(route('facility.settings.password.update'), [
            'current_password' => 'WrongPassword123!',
            'password' => 'NewPassword456!',
            'password_confirmation' => 'NewPassword456!',
        ])
        ->assertRedirect(route('facility.settings.edit'))
        ->assertSessionHasErrors('current_password');

    expect(Hash::check('OldPassword123!', $user->fresh()->password))->toBeTrue();
});

it('prevents non-facility accounts from accessing facility settings', function () {
    $engineer = User::factory()->create(['role' => 'engineer']);

    $this->actingAs($engineer)
        ->get(route('facility.settings.edit'))
        ->assertForbidden();
});
