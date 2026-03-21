<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        // Create regular user for testing
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        // Create categories
        $mathCategory = Category::firstOrCreate(['name' => 'Mathematics', 'slug' => 'mathematics']);
        $scienceCategory = Category::firstOrCreate(['name' => 'Science', 'slug' => 'science']);
        $englishCategory = Category::firstOrCreate(['name' => 'English', 'slug' => 'english']);

        // Create exams
        $mathExam = Exam::firstOrCreate(
            ['slug' => 'basic-algebra'],
            [
                'title' => 'Basic Algebra',
                'description' => 'Test your knowledge of basic algebra concepts including equations, variables, and expressions.',
                'duration' => 30,
                'category_id' => $mathCategory->id,
                'is_active' => true,
            ]
        );

        $scienceExam = Exam::firstOrCreate(
            ['slug' => 'general-science'],
            [
                'title' => 'General Science',
                'description' => 'A comprehensive test covering physics, chemistry, and biology fundamentals.',
                'duration' => 45,
                'category_id' => $scienceCategory->id,
                'is_active' => true,
            ]
        );

        $englishExam = Exam::firstOrCreate(
            ['slug' => 'grammar-basics'],
            [
                'title' => 'Grammar Basics',
                'description' => 'Test your understanding of English grammar rules, punctuation, and sentence structure.',
                'duration' => 25,
                'category_id' => $englishCategory->id,
                'is_active' => true,
            ]
        );

        // Create questions for Math Exam
        Question::firstOrCreate(
            ['exam_id' => $mathExam->id, 'question_text' => 'Solve for x: 2x + 5 = 15'],
            [
                'options' => ['x = 5', 'x = 10', 'x = 7.5', 'x = 2.5'],
                'correct_answer' => 'x = 5',
                'type' => 'multiple_choice',
            ]
        );

        Question::firstOrCreate(
            ['exam_id' => $mathExam->id, 'question_text' => 'What is the value of 3² + 4²?'],
            [
                'options' => ['14', '25', '49', '12'],
                'correct_answer' => '25',
                'type' => 'multiple_choice',
            ]
        );

        Question::firstOrCreate(
            ['exam_id' => $mathExam->id, 'question_text' => 'The sum of angles in a triangle is always 180 degrees.'],
            [
                'options' => null,
                'correct_answer' => 'true',
                'type' => 'true_false',
            ]
        );

        // Create questions for Science Exam
        Question::firstOrCreate(
            ['exam_id' => $scienceExam->id, 'question_text' => 'What is the chemical symbol for water?'],
            [
                'options' => ['H2O', 'CO2', 'O2', 'NaCl'],
                'correct_answer' => 'H2O',
                'type' => 'multiple_choice',
            ]
        );

        Question::firstOrCreate(
            ['exam_id' => $scienceExam->id, 'question_text' => 'What planet is known as the Red Planet?'],
            [
                'options' => ['Venus', 'Mars', 'Jupiter', 'Saturn'],
                'correct_answer' => 'Mars',
                'type' => 'multiple_choice',
            ]
        );

        Question::firstOrCreate(
            ['exam_id' => $scienceExam->id, 'question_text' => 'The speed of light is faster than the speed of sound.'],
            [
                'options' => null,
                'correct_answer' => 'true',
                'type' => 'true_false',
            ]
        );

        // Create questions for English Exam
        Question::firstOrCreate(
            ['exam_id' => $englishExam->id, 'question_text' => 'Identify the noun in the sentence: "The cat sleeps on the mat."'],
            [
                'options' => ['cat', 'sleeps', 'on', 'the'],
                'correct_answer' => 'cat',
                'type' => 'multiple_choice',
            ]
        );

        Question::firstOrCreate(
            ['exam_id' => $englishExam->id, 'question_text' => 'Which word is a verb?'],
            [
                'options' => ['Quickly', 'Beautiful', 'Run', 'Happiness'],
                'correct_answer' => 'Run',
                'type' => 'multiple_choice',
            ]
        );

        Question::firstOrCreate(
            ['exam_id' => $englishExam->id, 'question_text' => 'A sentence must always begin with a capital letter.'],
            [
                'options' => null,
                'correct_answer' => 'true',
                'type' => 'true_false',
            ]
        );
    }
}
