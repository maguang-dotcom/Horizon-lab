<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SiteMetric;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'admin@horizonlab.com'],
            [
                'name' => 'Horizon Lab Admin',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
            ]
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        SiteMetric::upsert([
            ['key' => 'hospitals', 'value' => '120+', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'engineers', 'value' => '450+', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'uptime', 'value' => '99.4%', 'created_at' => now(), 'updated_at' => now()],
        ], ['key'], ['value', 'updated_at']);
    }
}
