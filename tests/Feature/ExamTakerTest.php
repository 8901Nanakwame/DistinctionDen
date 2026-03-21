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

/**
 * ExamTaker Component Tests
 *
 * These tests verify the ExamTaker Livewire component works correctly.
 * They cover:
 * - Component initialization and rendering
 * - Answer selection and saving
 * - Navigation between questions
 * - Exam submission and scoring
 * - Resuming incomplete attempts
 */
class ExamTakerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected Exam $exam;

    /**
     * Set up test data before each test
     *
     * Creates a user, category, and exam that can be reused across tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create(['is_admin' => false]);

        // Create a category for exams
        $this->category = Category::factory()->create();

        // Create an exam for testing
        $this->exam = Exam::factory()->create([
            'category_id' => $this->category->id,
            'duration' => 30, // 30 minutes
        ]);
    }

    /**
     * Test: Component renders correctly for authenticated user
     *
     * Verifies that:
     * - The exam take page loads successfully
     * - Exam title is displayed
     * - "Start Exam" button is visible
     */
    public function test_exam_take_page_renders(): void
    {
        // Act as authenticated user and visit the exam take page
        $response = $this->actingAs($this->user)->get("/exams/{$this->exam->id}/take");

        // Assert the page loaded successfully (HTTP 200)
        $response->assertStatus(200);

        // Assert the exam title is shown on the page
        $response->assertSee($this->exam->title);

        // Assert the "Start Exam" button is visible
        $response->assertSee('Start Exam');
    }

    /**
     * Test: Component initializes with correct exam data
     *
     * Verifies that:
     * - The Livewire component loads the correct exam
     * - Exam properties are set correctly
     */
    public function test_component_initializes_with_exam_data(): void
    {
        // Create the Livewire component with the exam ID
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            // Assert the exam title matches
            ->assertSet('exam.title', $this->exam->title)
            // Assert exam hasn't been started yet
            ->assertSet('examStarted', false);
    }

    /**
     * Test: User can start an exam
     *
     * Verifies that:
     * - Calling saveProgress creates an ExamAttempt record
     * - examStarted flag becomes true
     */
    public function test_user_can_start_exam(): void
    {
        // Create the Livewire component and call the start method
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->call('saveProgress');

        // Assert an ExamAttempt was created in the database
        $this->assertDatabaseHas('exam_attempts', [
            'user_id' => $this->user->id,
            'exam_id' => $this->exam->id,
        ]);
    }

    /**
     * Test: User can select an answer for a multiple choice question
     *
     * Verifies that:
     * - Answer is stored in the answers array
     * - Progress is saved to database
     */
    public function test_user_can_select_answer(): void
    {
        // Create a multiple choice question
        $question = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'type' => 'multiple_choice',
            'options' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct_answer' => 'Paris',
        ]);

        // Create the Livewire component and select an answer
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->call('selectAnswer', 'Paris');

        // Assert the answer was saved in the database
        $this->assertDatabaseHas('exam_attempts', [
            'user_id' => $this->user->id,
            'exam_id' => $this->exam->id,
        ]);

        // Verify the answer is stored correctly
        $attempt = ExamAttempt::where('user_id', $this->user->id)->first();
        $this->assertEquals(['Paris'], array_values($attempt->answers));
    }

    /**
     * Test: User can navigate between questions
     *
     * Verifies that:
     * - nextQuestion() moves to the next question
     * - previousQuestion() moves to the previous question
     * - goToQuestion() jumps to a specific question
     */
    public function test_user_can_navigate_between_questions(): void
    {
        // Create multiple questions for the exam
        Question::factory()->count(5)->create(['exam_id' => $this->exam->id]);

        // Create the Livewire component
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            // Start on question 0
            ->assertSet('currentQuestionIndex', 0)

            // Move to next question (should be 1)
            ->call('nextQuestion')
            ->assertSet('currentQuestionIndex', 1)

            // Move back to previous question (should be 0)
            ->call('previousQuestion')
            ->assertSet('currentQuestionIndex', 0)

            // Jump directly to question 4
            ->call('goToQuestion', 4)
            ->assertSet('currentQuestionIndex', 4);
    }

    /**
     * Test: Navigation respects question boundaries
     *
     * Verifies that:
     * - Cannot go before question 0
     * - Cannot go beyond the last question
     */
    public function test_navigation_respects_boundaries(): void
    {
        // Create 3 questions
        Question::factory()->count(3)->create(['exam_id' => $this->exam->id]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            // Try to go back from first question (should stay at 0)
            ->call('previousQuestion')
            ->assertSet('currentQuestionIndex', 0)

            // Jump to last question (index 2)
            ->call('goToQuestion', 2)
            // Try to go past last question (should stay at 2)
            ->call('nextQuestion')
            ->assertSet('currentQuestionIndex', 2);
    }

    /**
     * Test: Progress percentage is calculated correctly
     *
     * Verifies that:
     * - Progress is 0% at the start
     * - Progress increases as user advances
     * - Progress is 100% at the last question
     */
    public function test_progress_percentage_is_calculated(): void
    {
        // Create 10 questions for easy percentage calculation
        Question::factory()->count(10)->create(['exam_id' => $this->exam->id]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            // First question = 10% progress (1/10)
            ->assertSet('progressPercentage', 10.0)

            // Move to 5th question = 50% progress (5/10)
            ->call('goToQuestion', 4)
            ->assertSet('progressPercentage', 50.0)

            // Move to last question = 100% progress (10/10)
            ->call('goToQuestion', 9)
            ->assertSet('progressPercentage', 100.0);
    }

    /**
     * Test: Exam submission calculates correct score
     *
     * Verifies that:
     * - Score is calculated based on correct answers
     * - Score is stored as percentage (0-100)
     * - Exam is marked as completed
     */
    public function test_exam_submission_calculates_score(): void
    {
        // Create questions with known correct answers
        $question1 = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'A',
        ]);

        $question2 = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'B',
        ]);

        $question3 = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'C',
        ]);

        // Create attempt with 2 correct out of 3 answers (67%)
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->set('answers', [
                $question1->id => 'A', // Correct
                $question2->id => 'B', // Correct
                $question3->id => 'D', // Wrong
            ])
            ->call('submitExam')
            // Assert score is 67% (2/3 * 100 rounded)
            ->assertSet('score', 67)
            // Assert exam is marked as completed
            ->assertSet('examCompleted', true)
            // Assert submitted flag is set
            ->assertSet('submitted', true);
    }

    /**
     * Test: Perfect score is calculated correctly
     *
     * Verifies that:
     * - 100% score is awarded for all correct answers
     */
    public function test_perfect_score(): void
    {
        $question1 = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'Correct',
        ]);

        $question2 = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'Right',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->set('answers', [
                $question1->id => 'Correct',
                $question2->id => 'Right',
            ])
            ->call('submitExam')
            ->assertSet('score', 100);
    }

    /**
     * Test: Zero score for all wrong answers
     *
     * Verifies that:
     * - 0% score is given when all answers are wrong
     */
    public function test_zero_score_for_wrong_answers(): void
    {
        $question = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'Paris',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->set('answers', [
                $question->id => 'London', // Wrong answer
            ])
            ->call('submitExam')
            ->assertSet('score', 0);
    }

    /**
     * Test: Submit confirmation modal opens correctly
     *
     * Verifies that:
     * - openSubmitModal() sets showConfirmSubmit to true
     */
    public function test_submit_modal_opens(): void
    {
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->call('openSubmitModal')
            ->assertSet('showConfirmSubmit', true);
    }

    /**
     * Test: Submit can be cancelled
     *
     * Verifies that:
     * - cancelSubmit() closes the modal
     * - User can continue the exam
     */
    public function test_submit_can_be_cancelled(): void
    {
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->set('showConfirmSubmit', true)
            ->call('cancelSubmit')
            ->assertSet('showConfirmSubmit', false);
    }

    /**
     * Test: Answered count is calculated correctly
     *
     * Verifies that:
     * - answeredCount returns correct number of answered questions
     */
    public function test_answered_count(): void
    {
        Question::factory()->count(5)->create(['exam_id' => $this->exam->id]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->set('answers', [
                1 => 'Answer 1',
                2 => 'Answer 2',
                3 => 'Answer 3',
                // Questions 4 and 5 not answered
            ])
            ->assertSet('answeredCount', 3);
    }

    /**
     * Test: Incomplete attempt can be resumed
     *
     * Verifies that:
     * - Existing incomplete attempt is loaded
     * - Previous answers are restored
     */
    public function test_incomplete_attempt_can_be_resumed(): void
    {
        // Create a question
        $question = Question::factory()->create([
            'exam_id' => $this->exam->id,
        ]);

        // Create an incomplete attempt with an answer
        $attempt = ExamAttempt::create([
            'user_id' => $this->user->id,
            'exam_id' => $this->exam->id,
            'answers' => [$question->id => 'Saved Answer'],
            'completed_at' => null, // Not completed
        ]);

        // Load the component - it should restore the attempt
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            // Assert exam is marked as started (resumed)
            ->assertSet('examStarted', true)
            // Assert previous answer is restored
            ->assertSet('answers', [$question->id => 'Saved Answer']);
    }

    /**
     * Test: Completed attempt is not resumed
     *
     * Verifies that:
     * - Completed attempts are not loaded for resumption
     * - User starts fresh for a retake
     */
    public function test_completed_attempt_not_resumed(): void
    {
        // Create a completed attempt
        ExamAttempt::create([
            'user_id' => $this->user->id,
            'exam_id' => $this->exam->id,
            'answers' => [1 => 'Old Answer'],
            'completed_at' => now(), // Completed
            'score' => 80,
        ]);

        // Load the component - should start fresh
        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            // Assert exam is NOT marked as started
            ->assertSet('examStarted', false)
            // Assert answers are empty (fresh start)
            ->assertSet('answers', []);
    }

    /**
     * Test: Score is saved to database on submission
     *
     * Verifies that:
     * - Score is persisted in exam_attempts table
     * - completed_at timestamp is set
     */
    public function test_score_saved_to_database(): void
    {
        $question = Question::factory()->create([
            'exam_id' => $this->exam->id,
            'correct_answer' => 'Correct',
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\ExamTaker::class, ['examId' => $this->exam->id])
            ->set('answers', [$question->id => 'Correct'])
            ->call('submitExam');

        // Verify score was saved in database
        $this->assertDatabaseHas('exam_attempts', [
            'user_id' => $this->user->id,
            'exam_id' => $this->exam->id,
            'score' => 100,
        ]);

        // Verify completed_at was set
        $attempt = ExamAttempt::where('user_id', $this->user->id)->first();
        $this->assertNotNull($attempt->completed_at);
    }
}
