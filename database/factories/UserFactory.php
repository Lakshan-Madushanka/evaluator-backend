<?php

namespace Database\Factories;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
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

    public function admin(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::ADMIN,
            'password' => 'admin123',
            'email' => 'admin@company.com',
        ]);
    }

    public function superAdmin(): UserFactory
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::ADMIN,
            'password' => 'superAdmin123',
            'email' => 'super-admin@company.com',
        ]);
    }
}
