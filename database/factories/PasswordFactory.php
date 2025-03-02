<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Password>
 */
class PasswordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'password' => fake()->password(),
            'url' => fake()->randomElement(['facebook.com', 'twitter.com', 'instagram.com', 'linkedin.com', 'github.com']),
            'username' => fake()->userName(),
            'title' => fake()->sentence(),
            'user_id' => fake()->numberBetween(1, 10)
        ];
    }
}
