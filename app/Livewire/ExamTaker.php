<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;

/**
 * ExamTaker Component
 *
 * Handles the exam-taking flow as a small state machine:
 * - Not started (shows overview + start button)
 * - In progress (one question at a time + navigation)
 * - Completed (shows results)
 *
 * User progress is persisted in `exam_attempts.answers` so refreshes/resumes
 * can restore the attempt.
 */
class ExamTaker extends Component
{
    // =========================================================================
    // PUBLIC PROPERTIES
    // =========================================================================

    /**
     * The Exam model instance containing all exam details
     * Includes title, description, duration, category, etc.
     */
    public ?Exam $exam = null;

    /**
     * The ID of the exam being taken
     * Passed from the route parameter
     */
    public int $examId;

    /**
     * Current page index (0-based)
     * Used to track which set of questions the user is currently viewing
     */
    public int $currentPage = 0;

    /**
     * Number of questions to display per page
     */
    public int $perPage = 10;

    /**
     * Array storing all user answers
     * Format: [question_id => answer_value]
     */
    public array $answers = [];

    /**
     * Whether the exam has been submitted
     * Once true, shows the results screen
     */
    public bool $submitted = false;

    /**
     * The final score percentage (0-100)
     * Calculated when the exam is submitted
     * Null until exam is submitted
     */
    public ?int $score = null;

    /**
     * The ExamAttempt model instance
     * Represents the user's attempt at this exam
     * Stores answers, score, and completion status in database
     */
    public ?ExamAttempt $attempt = null;

    /**
     * Controls visibility of the "Confirm Submit" modal
     * When true, shows modal asking user to confirm submission
     */
    public bool $showConfirmSubmit = false;

    /**
     * Time remaining in seconds
     * Calculated from exam duration (duration * 60)
     * Displayed as HH:MM:SS countdown timer
     *
     * This property represents the UI's countdown value; it is expected to be
     * updated as the exam runs.
     */
    public ?int $timeRemaining = null;

    /**
     * Whether the user has started the exam
     * Becomes true when user clicks "Start Exam" button
     * Triggers display of questions
     */
    public bool $examStarted = false;

    /**
     * Whether the exam has been completed
     * Becomes true when exam is submitted
     * Triggers display of results screen
     */
    public bool $examCompleted = false;

    // =========================================================================
    // MOUNT METHOD (COMPONENT INITIALIZATION)
    // =========================================================================

    /**
     * Initialize the component when it's first loaded
     *
     * This method runs once when the component is created.
     * It:
     * 1. Loads the exam with all its questions from database
     * 2. Sets up the timer based on exam duration
     * 3. Checks if user has an incomplete attempt to resume
     *
     * @param int $examId The ID of the exam to take (from URL route)
     */
    public function mount(int $examId): void
    {
        // Load the exam + questions up-front so the component can paginate in memory.
        $this->exam = Exam::with('questions')->findOrFail($examId);

        // Store the exam ID for later use (e.g., saving attempts)
        $this->examId = $examId;

        // Default timer if no attempt exists
        $this->timeRemaining = $this->exam->duration * 60;

        // Resume the most recent attempt if it is still incomplete.
        $existingAttempt = ExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $examId)
            ->latest() // Get the most recent attempt
            ->first();

        // If an incomplete attempt exists, restore it
        // This preserves user's previous answers
        if ($existingAttempt && !$existingAttempt->completed_at) {
            $this->attempt = $existingAttempt;
            $this->answers = $existingAttempt->answers ?? [];
            $this->examStarted = true; // Mark as started so they can continue

            // CALCULATE REMAINING TIME: (Total Allowed) - (Time Elapsed since Start)
            $totalSeconds = $this->exam->duration * 60;
            $secondsElapsed = $existingAttempt->created_at->diffInSeconds(now());
            $this->timeRemaining = max(0, $totalSeconds - $secondsElapsed);
            
            // If time has already run out upon resumption, auto-submit
            if ($this->timeRemaining <= 0) {
                $this->submitExam();
            }
        }
    }

    /**
     * Decrement the countdown timer by one second
     *
     * This method is triggered by wire:poll.1s in the Blade view.
     * It:
     * 1. Decreases timeRemaining by 1
     * 2. Automatically submits the exam when time reaches zero
     */
    public function decrementTimer(): void
    {
        // Only run if the exam is active
        if ($this->examStarted && !$this->submitted && !$this->examCompleted) {
            if ($this->timeRemaining > 0) {
                $this->timeRemaining--;
            } else {
                // Time's up! Force submission
                $this->submitExam();
            }
        }
    }

    // =========================================================================
    // COMPUTED PROPERTIES
    // =========================================================================

    /**
     * Get all questions for this exam
     *
     * This is a computed property that returns the exam's questions.
     * Using a computed property allows Livewire to cache the result.
     *
     * @return \Illuminate\Support\Collection Collection of Question models
     */
    #[Computed]
    public function questions()
    {
        return $this->exam->questions;
    }

    /**
     * Get the questions for the current page
     *
     * Returns a slice of questions based on currentPage and perPage.
     *
     * @return \Illuminate\Support\Collection
     */
    #[Computed]
    public function currentQuestions()
    {
        return $this->questions->slice($this->currentPage * $this->perPage, $this->perPage);
    }

    /**
     * Get total number of pages
     *
     * @return int
     */
    #[Computed]
    public function totalPages(): int
    {
        return ceil($this->questions->count() / $this->perPage);
    }

    /**
     * Get total exam time in seconds
     *
     * Used for calculating progress percentage.
     *
     * @return int Total time in seconds
     */
    #[Computed]
    public function totalTime(): int
    {
        return $this->exam->duration * 60;
    }

    /**
     * Calculate progress percentage through the exam
     *
     * Returns how far through the question set the user has progressed.
     * Used for the visual progress bar.
     *
     * @return float Percentage from 0 to 100
     */
    #[Computed]
    public function progressPercentage(): float
    {
        // Prevent division by zero if exam has no questions
        if ($this->questions->isEmpty()) {
            return 0;
        }
        
        $totalQuestions = $this->questions->count();
        $currentProgress = min(($this->currentPage + 1) * $this->perPage, $totalQuestions);
        
        return ($currentProgress / $totalQuestions) * 100;
    }

    /**
     * Count how many questions have been answered
     *
     * Filters the answers array to count only non-empty values.
     * Used to show user how many questions they've completed.
     *
     * @return int Number of answered questions
     */
    #[Computed]
    public function answeredCount(): int
    {
        // array_filter removes empty values, count returns the total
        return count(array_filter($this->answers));
    }

    // =========================================================================
    // ANSWER SELECTION METHODS
    // =========================================================================

    /**
     * Record the user's selected answer for a specific question
     *
     * @param int $questionId The ID of the question
     * @param string $answer The answer value selected by the user
     */
    public function selectAnswer(int $questionId, string $answer): void
    {
        // Store the answer using question ID as the key
        $this->answers[$questionId] = $answer;

        // Automatically save to database so progress isn't lost
        $this->saveProgress();
    }

    // =========================================================================
    // NAVIGATION METHODS
    // =========================================================================

    /**
     * Move to the next page
     */
    public function nextPage(): void
    {
        if ($this->currentPage < $this->totalPages - 1) {
            $this->currentPage++;
        }
    }

    /**
     * Move to the previous page
     */
    public function previousPage(): void
    {
        if ($this->currentPage > 0) {
            $this->currentPage--;
        }
    }

    /**
     * Jump directly to a specific page
     *
     * @param int $page The page index to jump to (0-based)
     */
    public function goToPage(int $page): void
    {
        if ($page >= 0 && $page < $this->totalPages) {
            $this->currentPage = $page;
        }
    }

    // =========================================================================
    // PROGRESS SAVING METHODS
    // =========================================================================

    /**
     * Save current progress to the database
     *
     * This method:
     * 1. Creates a new ExamAttempt if one doesn't exist
     * 2. Updates the answers in the database
     * 3. Marks the exam as started
     *
     * Called automatically when user selects an answer.
     * Ensures user progress is never lost even if they close the browser.
     */
    public function saveProgress(): void
    {
        // If no attempt record exists yet, create one
        if (!$this->attempt) {
            $this->attempt = ExamAttempt::create([
                'user_id' => Auth::id(),           // Currently logged-in user
                'exam_id' => $this->examId,        // Current exam
                'answers' => [],                   // Start with empty answers
            ]);
        }

        // Update the attempt with latest answers
        // This overwrites previous answers with current state
        $this->attempt->update([
            'answers' => $this->answers,
        ]);

        // Mark exam as started (shows question interface)
        $this->examStarted = true;
    }

    // =========================================================================
    // EXAM SUBMISSION METHODS
    // =========================================================================

    /**
     * Open the submission confirmation modal
     *
     * Called when user clicks "Submit Exam" button.
     * Shows a modal asking user to confirm before finalizing.
     */
    public function openSubmitModal(): void
    {
        // Display the confirmation modal
        $this->showConfirmSubmit = true;
    }

    /**
     * Submit the exam and calculate the final score
     *
     * This is the most critical method. It:
     * 1. Loops through all questions
     * 2. Compares user answers with correct answers
     * 3. Calculates percentage score
     * 4. Saves final results to database
     * 5. Shows results screen to user
     */
    public function submitExam(): void
    {
        // Safety check: don't proceed if no attempt exists
        if (!$this->attempt) {
            return;
        }

        // Initialize counter for correct answers
        $correctCount = 0;
        // Get total number of questions for percentage calculation
        $totalQuestions = $this->questions->count();

        // Loop through each question to grade the exam
        foreach ($this->questions as $question) {
            // Get the user's answer for this question (or null if unanswered)
            $userAnswer = $this->answers[$question->id] ?? null;

            // Check if answer is correct
            // Must be: 1) answered, and 2) match correct answer exactly
            if ($userAnswer !== null && $userAnswer === $question->correct_answer) {
                // Increment correct answer counter
                $correctCount++;
            }
        }

        // Calculate final score as percentage (0-100)
        // Formula: (correct / total) * 100, rounded to nearest integer
        // Example: 8 correct out of 10 = (8/10) * 100 = 80%
        $this->score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Save final results to database
        $this->attempt->update([
            'score' => $this->score,              // Final percentage score
            'answers' => $this->answers,          // All user answers
            'completed_at' => now(),              // Timestamp of completion
        ]);

        // Update component state to show results screen
        $this->submitted = true;
        $this->examCompleted = true;
        $this->showConfirmSubmit = false;

        // Send success notification to user
        $this->dispatch('notification', message: 'Exam submitted successfully!', type: 'success');
    }

    /**
     * Cancel the submission and return to exam
     *
     * Called when user clicks "Continue Exam" in confirmation modal.
     * Closes the modal and lets user continue answering questions.
     */
    public function cancelSubmit(): void
    {
        // Hide the confirmation modal
        $this->showConfirmSubmit = false;
    }

    // =========================================================================
    // RENDER METHOD
    // =========================================================================

    /**
     * Render the component's view
     *
     * Returns the Blade template that displays the exam interface.
     * The view handles three states:
     * 1. Before starting (exam info screen)
     * 2. During exam (questions interface)
     * 3. After completion (results screen)
     *
     * @return \Illuminate\View\View The rendered Blade view
     */
    public function render()
    {
        return view('livewire.exam-taker');
    }
}
