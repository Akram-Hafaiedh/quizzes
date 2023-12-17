<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $questions = Question::all();
        return [
            'content' => fake()->sentence(),
            'explanation' => fake()->paragraph(),
            'is_correct' => fake()->boolean(),
            // 'question_id' => $questions->random()->id,
        ];
    }
}
