<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Main test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        // Additional test users for testers
        $testUsers = [
            ['email' => 'tester1@example.com', 'name' => 'Tester One'],
            ['email' => 'tester2@example.com', 'name' => 'Tester Two'],
            ['email' => 'demo@example.com', 'name' => 'Demo User'],
        ];

        foreach ($testUsers as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
