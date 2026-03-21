<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class AdminCategoryManager extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    public ?Category $editingCategory = null;
    public ?Category $deletingCategory = null;

    public string $name = '';
    public string $type = 'exam'; // 'exam', 'book', 'blog'
    public string $searchTerm = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // Support legacy "post" type and normalize it to "blog" on save.
            'type' => 'required|in:exam,book,blog,post',
        ];
    }

    #[Computed]
    public function categories()
    {
        $query = Category::query();

        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        return $query->latest()->paginate(10);
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->type = 'exam';
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function createCategory(): void
    {
        $validated = $this->validate();
        $validated['slug'] = Str::slug($this->name);
        $validated['type'] = $validated['type'] === 'post' ? 'blog' : $validated['type'];

        Category::create($validated);

        $this->showCreateModal = false;
        $this->dispatch('notification', message: 'Category created successfully', type: 'success');
    }

    public function openEditModal(Category $category): void
    {
        $this->editingCategory = $category;
        $this->name = $category->name;
        $this->type = $category->type === 'post' ? 'blog' : $category->type;
        $this->showEditModal = true;
    }

    public function updateCategory(): void
    {
        if (!$this->editingCategory) {
            return;
        }

        $validated = $this->validate();
        $validated['slug'] = Str::slug($this->name);
        $validated['type'] = $validated['type'] === 'post' ? 'blog' : $validated['type'];

        $this->editingCategory->update($validated);

        $this->showEditModal = false;
        $this->editingCategory = null;
        $this->dispatch('notification', message: 'Category updated successfully', type: 'success');
    }

    public function openDeleteModal(Category $category): void
    {
        $this->deletingCategory = $category;
        $this->showDeleteModal = true;
    }

    public function deleteCategory(): void
    {
        if ($this->deletingCategory) {
            $this->deletingCategory->delete();
            $this->deletingCategory = null;
            $this->showDeleteModal = false;
            $this->dispatch('notification', message: 'Category deleted successfully', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.admin.category-manager');
    }
}
