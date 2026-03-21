<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $examCategories = Category::factory()->count(3)->create(['type' => 'exam']);

        foreach ($examCategories as $category) {
            Exam::factory()->count(2)->create([
                'category_id' => $category->id
            ])->each(function ($exam) {
                Question::factory()->count(5)->create([
                    'exam_id' => $exam->id
                ]);
            });
        }

        // Seed books for the bookshop
        $this->call([
            BookSeeder::class,
        ]);
    }
}
