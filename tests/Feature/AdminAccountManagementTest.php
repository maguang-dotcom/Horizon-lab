<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function makeUser(string $role, string $email): User
{
    return User::forceCreate(['name' => ucfirst($role), 'email' => $email, 'password' => 'password123', 'role' => $role]);
}

it('lets an admin add another admin', function () {
    $admin = makeUser('admin', 'a@test.com');

    $this->actingAs($admin)->post(route('admin.admins.store'), [
        'name' => 'Second Admin',
        'email' => 'b@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('admin.users.index'));

    expect(User::where('email', 'b@test.com')->value('role'))->toBe('admin');
});

it('lets an admin reject an engineer and delete a user', function () {
    $admin = makeUser('admin', 'a@test.com');
    $engineer = makeUser('engineer', 'e@test.com');
    $facility = makeUser('facility', 'f@test.com');

    $this->actingAs($admin)->delete(route('admin.engineers.reject', $engineer))->assertRedirect();
    $this->actingAs($admin)->delete(route('admin.users.destroy', $facility))->assertRedirect();

    expect(User::whereIn('id', [$engineer->id, $facility->id])->count())->toBe(0);
});

it('protects the current admin from deletion', function () {
    $admin = makeUser('admin', 'a@test.com');

    $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHas('error');

    expect(User::find($admin->id))->not->toBeNull();
});

it('blocks non-admins', function () {
    $engineer = makeUser('engineer', 'e@test.com');

    $this->actingAs($engineer)->delete(route('admin.users.destroy', $engineer))->assertForbidden();
});
