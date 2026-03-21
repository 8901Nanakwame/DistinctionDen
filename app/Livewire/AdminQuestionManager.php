<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class AdminQuestionManager extends Component
{
    use WithPagination;

    /**
     * Modal visibility flags.
     *
     * Flux/Livewire modals bind to these booleans to open/close.
     */
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    /**
     * The currently selected row for edit/delete actions.
     *
     * Keeping the model instance here lets the modal render details and makes
     * update/delete operations straightforward.
     */
    public ?Question $editingQuestion = null;
    public ?Question $deletingQuestion = null;
    public ?int $selectedExam = null;

    /**
     * Category selection (used to narrow exams when creating/editing questions).
     */
    public ?int $examCategoryId = null;

    /**
     * Form fields used for create/update.
     */
    public string $questionText = '';
    public string $type = 'multiple_choice';
    public array $wrongAnswers = ['', '', '']; // Start with 3 wrong answers as requested
    public string $correctAnswer = '';
    public ?int $examId = null;

    public function addWrongAnswer(): void
    {
        if (count($this->wrongAnswers) < 31) { // Allow up to 31 wrong answers
            $this->wrongAnswers[] = '';
        }
    }

    public function removeWrongAnswer(int $index): void
    {
        if (count($this->wrongAnswers) > 1) {
            unset($this->wrongAnswers[$index]);
            $this->wrongAnswers = array_values($this->wrongAnswers);
        }
    }

    /**
     * List filtering controls.
     */
    public string $searchTerm = '';
    public ?int $filterCategory = null;
    public ?int $filterExam = null;
    public ?string $filterType = null;

    #[Computed]
    public function questions()
    {
        // Base query for the table view; filters are applied based on UI state.
        $query = Question::query()->with('exam');

        if ($this->filterCategory) {
            $query->whereHas('exam', fn ($examQuery) => $examQuery->where('category_id', $this->filterCategory));
        }

        if ($this->filterExam) {
            $query->where('exam_id', $this->filterExam);
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->searchTerm) {
            $query->where('question_text', 'like', '%' . $this->searchTerm . '%');
        }

        return $query->latest()->paginate(20);
    }

    #[Computed]
    public function exams()
    {
        // Used to populate exam dropdowns in filters and create/edit forms.
        return Exam::all();
    }

    #[Computed]
    public function examCategories()
    {
        return Category::query()
            ->where('type', 'exam')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function filteredExams()
    {
        return Exam::query()
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->orderBy('title')
            ->get();
    }

    #[Computed]
    public function formExams()
    {
        return Exam::query()
            ->when($this->examCategoryId, fn ($q) => $q->where('category_id', $this->examCategoryId))
            ->orderBy('title')
            ->get();
    }

    #[Computed]
    public function questionTypes()
    {
        // Single source of truth for "type" values used by UI + validation.
        return ['multiple_choice', 'true_false', 'short_answer'];
    }

    public function resetForm(): void
    {
        // Reset create/edit fields so old data does not bleed into a new modal session.
        $this->examCategoryId = null;
        $this->questionText = '';
        $this->type = 'multiple_choice';
        $this->wrongAnswers = ['', '', ''];
        $this->correctAnswer = '';
        $this->examId = null;
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function updatedFilterCategory($value): void
    {
        $this->filterExam = null;
        $this->resetPage();
    }

    public function updatedExamCategoryId($value): void
    {
        if (! $value) {
            return;
        }

        if ($this->examId && ! Exam::query()->whereKey($this->examId)->where('category_id', $value)->exists()) {
            $this->examId = null;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'questionText' => 'required|string|min:10',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'correctAnswer' => 'required|string',
            'examId' => 'required|exists:exams,id',
        ];

        if ($this->type === 'multiple_choice') {
            $rules['wrongAnswers'] = 'required|array|min:1';
            $rules['wrongAnswers.*'] = 'required|string|min:1';
        }

        return $rules;
    }

    public function createQuestion(): void
    {
        $validated = $this->validate();

        // Filter out empty wrong answers before saving
        $filteredWrongAnswers = $this->type === 'multiple_choice'
            ? array_values(array_filter($this->wrongAnswers, fn($answer) => trim($answer) !== ''))
            : [];

        // Combine correct and wrong answers for the options column
        $options = $this->type === 'multiple_choice'
            ? array_merge([$this->correctAnswer], $filteredWrongAnswers)
            : null;

        Question::create([
            'exam_id' => $this->examId,
            'question_text' => $this->questionText,
            'options' => $options,
            'correct_answer' => $this->correctAnswer,
            'type' => $this->type,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        $this->dispatch('notification', message: 'Question created successfully', type: 'success');
    }

    public function openEditModal(Question $question): void
    {
        // Populate the form from the selected question so the modal can edit in-place.
        $question->loadMissing('exam');
        $this->editingQuestion = $question;
        $this->questionText = $question->question_text;
        $this->type = $question->type;
        
        // Extract wrong answers by filtering out the correct one from options
        if ($question->type === 'multiple_choice' && is_array($question->options)) {
            $this->wrongAnswers = array_values(array_filter($question->options, fn($opt) => $opt !== $question->correct_answer));
        } else {
            $this->wrongAnswers = ['', '', ''];
        }

        $this->correctAnswer = $question->correct_answer;
        $this->examId = $question->exam_id;
        $this->examCategoryId = $question->exam?->category_id;
        $this->showEditModal = true;
    }

    public function updateQuestion(): void
    {
        if (!$this->editingQuestion) {
            return;
        }

        $validated = $this->validate();

        // Filter out empty wrong answers before saving
        $filteredWrongAnswers = $this->type === 'multiple_choice'
            ? array_values(array_filter($this->wrongAnswers, fn($answer) => trim($answer) !== ''))
            : [];

        // Combine correct and wrong answers for the options column
        $options = $this->type === 'multiple_choice'
            ? array_merge([$this->correctAnswer], $filteredWrongAnswers)
            : null;

        $this->editingQuestion->update([
            'exam_id' => $this->examId,
            'question_text' => $this->questionText,
            'options' => $options,
            'correct_answer' => $this->correctAnswer,
            'type' => $this->type,
        ]);

        $this->showEditModal = false;
        $this->editingQuestion = null;
        $this->resetForm();
        $this->dispatch('notification', message: 'Question updated successfully', type: 'success');
    }

    public function openDeleteModal(Question $question): void
    {
        // Set the row being deleted and let the confirmation modal show details.
        $this->deletingQuestion = $question;
        $this->showDeleteModal = true;
    }

    public function deleteQuestion(): void
    {
        if ($this->deletingQuestion) {
            $this->deletingQuestion->delete();
            $this->deletingQuestion = null;
            $this->showDeleteModal = false;
            $this->dispatch('notification', message: 'Question deleted successfully', type: 'success');
        }
    }

    public function cancelEdit(): void
    {
        $this->showEditModal = false;
        $this->editingQuestion = null;
        $this->resetForm();
    }

    public function cancelCreate(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.question-manager');
    }
}
