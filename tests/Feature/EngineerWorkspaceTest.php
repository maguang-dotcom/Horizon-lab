<?php

use App\Mail\AccountCreatedMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function makeEngineer(): User
{
    return User::forceCreate([
        'name' => 'Eng One',
        'email' => 'eng@example.com',
        'password' => bcrypt('password'),
        'role' => 'engineer',
    ]);
}

it('shows the engineer workspace pages', function (string $route) {
    $this->actingAs(makeEngineer())->get(route($route))->assertOk();
})->with(['engineer.assignments', 'engineer.facilities', 'engineer.reports.index']);

it('renders the account created email', function () {
    (new AccountCreatedMail(makeEngineer()))->assertSeeInHtml('Eng One');
});
