<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class AdminBookManager extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    public ?Book $editingBook = null;
    public ?Book $deletingBook = null;

    public string $title = '';
    public string $author = '';
    public string $description = '';
    public $price = 0;
    public int $stock = 0;
    public ?int $categoryId = null;
    public $image;

    public string $searchTerm = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoryId' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    #[Computed]
    public function books()
    {
        $query = Book::query()->with('category');

        if ($this->searchTerm) {
            $query->where('title', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('author', 'like', '%' . $this->searchTerm . '%');
        }

        return $query->latest()->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return Category::where('type', 'book')->get();
    }

    public function resetForm(): void
    {
        $this->title = '';
        $this->author = '';
        $this->description = '';
        $this->price = 0;
        $this->stock = 0;
        $this->categoryId = null;
        $this->image = null;
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function createBook(): void
    {
        $validated = $this->validate();
        $validated['slug'] = Str::slug($this->title) . '-' . Str::random(5);

        if ($this->image) {
            $validated['image'] = $this->image->store('books', 'public');
        }

        Book::create($validated);

        $this->showCreateModal = false;
        $this->dispatch('notification', message: 'Book created successfully', type: 'success');
    }

    public function openEditModal(Book $book): void
    {
        $this->editingBook = $book;
        $this->title = $book->title;
        $this->author = $book->author;
        $this->description = $book->description;
        $this->price = $book->price;
        $this->stock = $book->stock;
        $this->categoryId = $book->category_id;
        $this->showEditModal = true;
    }

    public function updateBook(): void
    {
        if (!$this->editingBook) {
            return;
        }

        $validated = $this->validate();
        $validated['slug'] = Str::slug($this->title) . '-' . Str::random(5);

        if ($this->image) {
            $validated['image'] = $this->image->store('books', 'public');
        }

        $this->editingBook->update($validated);

        $this->showEditModal = false;
        $this->editingBook = null;
        $this->dispatch('notification', message: 'Book updated successfully', type: 'success');
    }

    public function openDeleteModal(Book $book): void
    {
        $this->deletingBook = $book;
        $this->showDeleteModal = true;
    }

    public function deleteBook(): void
    {
        if ($this->deletingBook) {
            $this->deletingBook->delete();
            $this->deletingBook = null;
            $this->showDeleteModal = false;
            $this->dispatch('notification', message: 'Book deleted successfully', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.admin.book-manager');
    }
}
