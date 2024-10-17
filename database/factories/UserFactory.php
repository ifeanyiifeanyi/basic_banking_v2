<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first_name = fake()->firstNameMale();
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'other_name' => $first_name,
            'username' => fake()->userName(),
            'phone' => fake()->phoneNumber(),
            'photo' => 'https://placehold.co/600x400.png',
            'city' => fake()->city(),
            'state' => fake()->citySuffix(),
            'country' => fake()->country(),
            'address' => fake()->address(),
            'zip' => fake()->postcode(),
            'dob' => fake()->date('Y-m-d'),
            'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
            'role' => 'member',
            'occupation' => fake()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
