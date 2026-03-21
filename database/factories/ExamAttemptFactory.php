<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAttempt>
 */
class ExamAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'exam_id' => Exam::factory(),
            'score' => $this->faker->numberBetween(0, 100),
            'answers' => [], // Can populate with fake answers if needed
            'completed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
