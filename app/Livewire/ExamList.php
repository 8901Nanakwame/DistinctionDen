<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ExamList extends Component
{
    use WithPagination;

    /**
     * UI state for filtering/sorting the exam catalog.
     *
     * These are bound directly from the Blade view via `wire:model`.
     */
    public string $searchTerm = '';
    public ?int $filterCategory = null;
    public string $sortBy = 'latest';

    /**
     * Paginated list of active exams for the current user to browse.
     *
     * Notes:
     * - Eager-load category for display.
     * - Eager-load attempts *for the current user only* so we can show completion/score.
     */
    #[Computed]
    public function exams()
    {
        $query = Exam::with(['category', 'attempts' => function($q) {
            $q->where('user_id', Auth::id());
        }])->where('is_active', true);

        // Free-text search across title/description.
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Optional category filter (null/empty means "all").
        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        // Sorting options for the UI.
        if ($this->sortBy === 'latest') {
            $query->latest();
        } elseif ($this->sortBy === 'popular') {
            $query->withCount('attempts')->orderBy('attempts_count', 'desc');
        }

        return $query->paginate(12);
    }

    /**
     * Available categories for the category filter dropdown.
     */
    #[Computed]
    public function categories()
    {
        return \App\Models\Category::all();
    }

    public function render()
    {
        return view('livewire.exam-list');
    }
}
