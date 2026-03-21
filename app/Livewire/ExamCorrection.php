<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;

/**
 * ExamCorrection Component
 *
 * Allows users to review their exam answers after completion.
 * Shows each question with:
 * - User's selected answer
 * - Correct answer
 * - Whether the answer was correct or incorrect
 */
class ExamCorrection extends Component
{
    /**
     * The exam being reviewed
     */
    public ?Exam $exam = null;

    /**
     * The user's exam attempt
     */
    public ?ExamAttempt $attempt = null;

    /**
     * Exam ID from route
     */
    public int $examId;

    /**
     * Attempt ID from route
     */
    public int $attemptId;

    /**
     * Initialize the component
     *
     * Loads the exam and the user's specific attempt
     */
    public function mount(int $examId, int $attemptId): void
    {
        // Load the exam with all its questions
        $this->exam = Exam::with('questions')->findOrFail($examId);

        // Load the user's attempt - verify ownership
        $this->attempt = ExamAttempt::where('user_id', Auth::id())
            ->where('exam_id', $examId)
            ->findOrFail($attemptId);

        // Ensure the attempt is completed
        if (!$this->attempt->completed_at) {
            redirect()->route('exams.take', $examId)
                ->with('error', 'This exam has not been completed yet.');
            return;
        }
    }

    /**
     * Get all questions for this exam
     */
    #[Computed]
    public function questions()
    {
        return $this->exam->questions;
    }

    /**
     * Check if a specific question was answered correctly
     *
     * @param int $questionId
     * @return bool
     */
    public function isQuestionCorrect(int $questionId): bool
    {
        $userAnswer = $this->attempt->answers[$questionId] ?? null;
        $question = $this->questions->find($questionId);

        if (!$question || $userAnswer === null) {
            return false;
        }

        return $userAnswer === $question->correct_answer;
    }

    /**
     * Get the user's answer for a specific question
     *
     * @param int $questionId
     * @return string|null
     */
    public function getUserAnswer(int $questionId): ?string
    {
        return $this->attempt->answers[$questionId] ?? null;
    }

    /**
     * Count correctly answered questions
     *
     * @return int
     */
    #[Computed]
    public function correctCount(): int
    {
        $count = 0;
        foreach ($this->questions as $question) {
            if ($this->isQuestionCorrect($question->id)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Count incorrectly answered questions
     *
     * @return int
     */
    #[Computed]
    public function incorrectCount(): int
    {
        return $this->questions->count() - $this->correctCount;
    }

    /**
     * Count unanswered questions
     *
     * @return int
     */
    #[Computed]
    public function unansweredCount(): int
    {
        $count = 0;
        foreach ($this->questions as $question) {
            if (!isset($this->attempt->answers[$question->id])) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.exam-correction');
    }
}
