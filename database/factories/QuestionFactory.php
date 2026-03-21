<?php

namespace Database\Factories;

use App\Models\Exam;
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
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'question_text' => $this->faker->sentence() . '?',
            'options' => [
                'A' => $this->faker->word(),
                'B' => $this->faker->word(),
                'C' => $this->faker->word(),
                'D' => $this->faker->word(),
            ],
            'correct_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'type' => 'multiple_choice',
        ];
    }
}
