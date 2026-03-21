<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Exam;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdminExamManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $image;
    public $existingImage;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    public ?Exam $editingExam = null;
    public ?Exam $deletingExam = null;

    public string $title = '';
    public string $description = '';
    public ?int $duration = null;
    public ?int $categoryId = null;
    public bool $isActive = true;

    public string $searchTerm = '';
    public ?int $filterCategory = null;
    public ?string $filterStatus = null; // null|'active'|'inactive'

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->where('type', 'exam')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function exams()
    {
        $query = Exam::query()
            ->with('category')
            ->withCount(['questions', 'attempts']);

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterStatus === 'active') {
            $query->where('is_active', true);
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('is_active', false);
        }

        if ($this->searchTerm) {
            $query->where('title', 'like', '%' . $this->searchTerm . '%');
        }

        return $query->latest()->paginate(15);
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:5000',
            'duration' => 'nullable|integer|min:1|max:1440',
            'categoryId' => 'required|exists:categories,id',
            'isActive' => 'boolean',
            'image' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    public function resetForm(): void
    {
        $this->title = '';
        $this->description = '';
        $this->duration = null;
        $this->categoryId = null;
        $this->isActive = true;
        $this->image = null;
        $this->existingImage = null;
    }

    public function openCreateModal(): void
    {
        $this->editingExam = null;
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function createExam(): void
    {
        $validated = $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('exams', 'public');
        }

        $exam = Exam::create([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title']),
            'description' => $validated['description'] ?: null,
            'duration' => $validated['duration'],
            'file_path' => null,
            'image' => $imagePath,
            'category_id' => $validated['categoryId'],
            'is_active' => $validated['isActive'],
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('notification', "Exam \"{$exam->title}\" created successfully");
    }

    public function openEditModal(Exam $exam): void
    {
        $this->editingExam = $exam;
        $this->title = $exam->title;
        $this->description = $exam->description ?? '';
        $this->duration = $exam->duration;
        $this->categoryId = $exam->category_id;
        $this->isActive = (bool) $exam->is_active;
        $this->existingImage = $exam->image;
        $this->image = null;
        $this->showEditModal = true;
    }

    public function updateExam(): void
    {
        if (! $this->editingExam) {
            return;
        }

        $validated = $this->validate();

        $imagePath = $this->editingExam->image;
        if ($this->image) {
            $imagePath = $this->image->store('exams', 'public');
        }

        $this->editingExam->update([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title'], $this->editingExam->id),
            'description' => $validated['description'] ?: null,
            'duration' => $validated['duration'],
            'image' => $imagePath,
            'category_id' => $validated['categoryId'],
            'is_active' => $validated['isActive'],
        ]);

        $this->showEditModal = false;
        $this->editingExam = null;
        $this->resetForm();
        session()->flash('notification', 'Exam updated successfully');
    }


    public function openDeleteModal(Exam $exam): void
    {
        $this->deletingExam = $exam;
        $this->showDeleteModal = true;
    }

    public function deleteExam(): void
    {
        if ($this->deletingExam) {
            $title = $this->deletingExam->title;
            $this->deletingExam->delete();

            $this->deletingExam = null;
            $this->showDeleteModal = false;
            $this->dispatch('notification', message: "Exam \"{$title}\" deleted", type: 'success');
        }
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (
            Exam::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function render()
    {
        return view('livewire.admin.exam-manager');
    }
}

