<?php

namespace Database\Factories;

use App\Enums\Difficulty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'difficulty' => $this->faker->randomElement(Difficulty::cases()),
            'text' => $this->faker->paragraph(random_int(1, 2), true),
            'no_of_answers' => $this->faker->randomElement([2, 4]),
        ];
    }
}
