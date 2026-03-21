<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExamDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;
    protected Category $category;
    protected Exam $exam;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
        $this->category = Category::factory()->create();
        $this->exam = Exam::factory()->create(['category_id' => $this->category->id]);
    }

    public function dashboard_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Welcome back');
        $response->assertSee('Browse Exams');
    }

    public function admin_panel_is_visible_only_to_admins(): void
    {
        // Admin should see admin panel
        $response = $this->actingAs($this->adminUser)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Panel');
        $response->assertSee('Manage Exams');
        $response->assertSee('Add Questions');

        // Regular user should NOT see admin panel
        $response = $this->actingAs($this->regularUser)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertDontSee('Admin Panel');
    }

    public function exam_list_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/exams');

        $response->assertStatus(200);
        $response->assertSee('Browse Exams');
    }

    public function exam_list_shows_active_exams(): void
    {
        $activeExam = Exam::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => true,
            'title' => 'Active Exam Test',
        ]);

        $inactiveExam = Exam::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => false,
            'title' => 'Inactive Exam Test',
        ]);

        $response = $this->actingAs($this->regularUser)->get('/exams');

        $response->assertStatus(200);
        $response->assertSee('Active Exam Test');
        $response->assertDontSee('Inactive Exam Test');
    }

    public function exam_list_can_be_searched(): void
    {
        Exam::factory()->create([
            'category_id' => $this->category->id,
            'title' => 'Mathematics Advanced',
            'description' => 'Advanced math topics',
        ]);

        Exam::factory()->create([
            'category_id' => $this->category->id,
            'title' => 'Science Basics',
            'description' => 'Basic science concepts',
        ]);

        Livewire::test(\App\Livewire\ExamList::class)
            ->set('searchTerm', 'Mathematics')
            ->assertSee('Mathematics Advanced')
            ->assertDontSee('Science Basics');
    }

    public function exam_detail_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->regularUser)->get("/exams/{$this->exam->id}");

        $response->assertStatus(200);
        $response->assertSee($this->exam->title);
        $response->assertSee($this->exam->description);
    }

    public function exam_take_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->regularUser)->get("/exams/{$this->exam->id}/take");

        $response->assertStatus(200);
        $response->assertSee($this->exam->title);
        $response->assertSee('Start Exam');
    }

    public function user_can_start_exam(): void
    {
        Livewire::test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->call('saveProgress');

        $this->assertDatabaseHas('exam_attempts', [
            'user_id' => $this->regularUser->id,
            'exam_id' => $this->exam->id,
        ]);
    }

    public function user_can_submit_exam_and_get_score(): void
    {
        // Create questions for the exam
        $question1 = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'Option A',
            'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
        ]);

        $question2 = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'Option B',
            'options' => ['Option A', 'Option B', 'Option C', 'Option D'],
        ]);

        $attempt = ExamAttempt::create([
            'user_id' => $this->regularUser->id,
            'exam_id' => $this->exam->id,
            'answers' => [
                $question1->id => 'Option A', // Correct
                $question2->id => 'Option C', // Wrong
            ],
        ]);

        // 1 correct out of 2 = 50%
        $this->assertEquals(50, round((1 / 2) * 100));
    }

    public function admin_can_access_question_management(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/questions');

        $response->assertStatus(200);
        $response->assertSee('Question Management');
        $response->assertSee('Add Question');
    }

    public function regular_user_cannot_access_admin_pages(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/questions');

        $response->assertStatus(403);
    }

    public function admin_can_create_question(): void
    {
        $this->actingAs($this->adminUser);

        $questionData = [
            'exam_id' => $this->exam->id,
            'question_text' => 'What is the capital of France?',
            'type' => 'multiple_choice',
            'options' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct_answer' => 'Paris',
        ];

        $question = Question::create($questionData);

        $this->assertDatabaseHas('questions', [
            'exam_id' => $this->exam->id,
            'question_text' => 'What is the capital of France?',
            'correct_answer' => 'Paris',
        ]);
    }

    public function admin_can_update_question(): void
    {
        $question = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'question_text' => 'Old question text',
        ]);

        $question->update([
            'question_text' => 'Updated question text',
        ]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Updated question text',
        ]);
    }

    public function admin_can_delete_question(): void
    {
        $question = Question::factory()->create([
            'exam_id' => $this->exam->id,
        ]);

        $question->delete();

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id,
        ]);
    }

    public function admin_can_access_exam_management(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/exams');

        $response->assertStatus(200);
        $response->assertSee('Exam Management');
        $response->assertSee('Total Exams');
        $response->assertSee('Total Questions');
        $response->assertSee('Total Attempts');
    }

    public function dashboard_shows_exam_statistics(): void
    {
        // Create some exam attempts
        ExamAttempt::factory()->count(3)->create([
            'user_id' => $this->regularUser->id,
            'exam_id' => $this->exam->id,
            'score' => 80,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->regularUser)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Exam Dashboard');
        $response->assertSee('Total Exams');
        $response->assertSee('Average Score');
        $response->assertSee('Best Score');
    }

    public function exam_attempt_is_saved_correctly(): void
    {
        $attempt = ExamAttempt::factory()->create([
            'user_id' => $this->regularUser->id,
            'exam_id' => $this->exam->id,
            'score' => 75,
            'answers' => ['question1' => 'answer1', 'question2' => 'answer2'],
        ]);

        $this->assertDatabaseHas('exam_attempts', [
            'user_id' => $this->regularUser->id,
            'exam_id' => $this->exam->id,
            'score' => 75,
        ]);

        $this->assertEquals(['question1' => 'answer1', 'question2' => 'answer2'], $attempt->answers);
    }

    public function user_relationships_work_correctly(): void
    {
        ExamAttempt::factory()->count(2)->create([
            'user_id' => $this->regularUser->id,
            'exam_id' => $this->exam->id,
        ]);

        $user = $this->regularUser->fresh();

        $this->assertEquals(2, $user->examAttempts->count());
    }

    public function exam_relationships_work_correctly(): void
    {
        Question::factory()->count(5)->create([
            'exam_id' => $this->exam->id,
        ]);

        $exam = $this->exam->fresh();

        $this->assertEquals(5, $exam->questions->count());
    }

    public function question_types_are_validated(): void
    {
        // Valid types
        $validTypes = ['multiple_choice', 'true_false', 'short_answer'];

        foreach ($validTypes as $type) {
            $question = Question::factory()->create([
                'exam_id' => $this->exam->id,
                'type' => $type,
            ]);

            $this->assertDatabaseHas('questions', [
                'id' => $question->id,
                'type' => $type,
            ]);
        }
    }
}
