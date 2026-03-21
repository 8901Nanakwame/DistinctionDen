<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Computed;

class DashboardExamStats extends Component
{
    /**
     * Total number of attempts (completed or in-progress) by the current user.
     */
    #[Computed]
    public function totalExamsTaken(): int
    {
        return ExamAttempt::where('user_id', Auth::id())->count();
    }

    /**
     * Average score across attempts for the current user.
     */
    #[Computed]
    public function averageScore(): float
    {
        return round(
            ExamAttempt::where('user_id', Auth::id())
                ->avg('score') ?? 0,
            2
        );
    }

    /**
     * Highest score achieved by the current user.
     */
    #[Computed]
    public function highestScore(): int
    {
        return ExamAttempt::where('user_id', Auth::id())
            ->max('score') ?? 0;
    }

    /**
     * The most recent completed attempt (includes the related exam for display).
     */
    #[Computed]
    public function latestAttempt()
    {
        return ExamAttempt::where('user_id', Auth::id())
            ->with('exam')
            ->orderByDesc('completed_at')
            ->first();
    }

    /**
     * Simple breakdown of average score by question type.
     *
     * Uses the query builder for an aggregate view; this is a dashboard-only
     * read path (not used for writes).
     */
    #[Computed]
    public function scoreByQuestionType()
    {
        return DB::table('exam_attempts')
            ->join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
            ->join('questions', 'exams.id', '=', 'questions.exam_id')
            ->where('exam_attempts.user_id', Auth::id())
            ->whereNotNull('exam_attempts.completed_at')
            ->select('questions.type', DB::raw('AVG(exam_attempts.score) as avg_score'))
            ->groupBy('questions.type')
            ->get();
    }

    /**
     * Last 5 attempts (completed or not) for quick dashboard display.
     */
    #[Computed]
    public function recentAttempts()
    {
        return ExamAttempt::where('user_id', Auth::id())
            ->with('exam')
            ->orderByDesc('completed_at')
            ->limit(5)
            ->get();
    }

    /**
     * A small "sparkline" dataset: last 10 completed attempts by time.
     */
    #[Computed]
    public function performanceTrend()
    {
        return ExamAttempt::where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->orderBy('completed_at')
            ->limit(10)
            ->get(['score', 'completed_at']);
    }

    public function render()
    {
        return view('livewire.dashboard-exam-stats');
    }
}
