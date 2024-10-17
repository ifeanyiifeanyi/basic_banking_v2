<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(15)->create();

        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'other_name' => 'Admin User',
            'username' => '00001admin',
            'email' => 'admin@admin.com',
            'phone' => '09475675585',
            'photo' => 'https://placehold.co/600x400.png',
            'city' => fake()->city(),
            'state' => fake()->citySuffix(),
            'country' => fake()->country(),
            'address' => fake()->address(),
            'zip' => fake()->postcode(),
            'dob' => fake()->date('Y-m-d'),
            'gender' => 'Male',
            'role' => 'admin',
            'occupation' => fake()->jobTitle(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),

        ]);
    }
}
