<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class AdminBlogManager extends Component
{
    use WithPagination;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    public ?Post $editingPost = null;
    public ?Post $deletingPost = null;

    public string $title = '';
    public string $content = '';
    public ?int $categoryId = null;
    public ?string $image = null;
    public ?string $publishedAt = null; // Y-m-d\TH:i for datetime-local

    public string $searchTerm = '';
    public ?int $filterCategory = null;
    public ?string $filterStatus = null; // null|'published'|'draft'

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->whereIn('type', ['blog', 'post'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function posts()
    {
        $query = Post::query()
            ->with(['category', 'user']);

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterStatus === 'published') {
            $query->whereNotNull('published_at');
        } elseif ($this->filterStatus === 'draft') {
            $query->whereNull('published_at');
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
            'content' => 'required|string|min:20',
            'categoryId' => 'required|exists:categories,id',
            'image' => 'nullable|string|max:2048',
            'publishedAt' => 'nullable|date',
        ];
    }

    public function resetForm(): void
    {
        $this->title = '';
        $this->content = '';
        $this->categoryId = null;
        $this->image = null;
        $this->publishedAt = null;
    }

    public function openCreateModal(): void
    {
        $this->editingPost = null;
        $this->resetForm();
        $this->showCreateModal = true;
        $this->dispatch('trix:set-content', id: 'create-post-content', html: $this->content);
    }

    public function createPost(): void
    {
        $validated = $this->validate();

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title']),
            'content' => $this->sanitizeHtml($validated['content']),
            'image' => $validated['image'],
            'category_id' => $validated['categoryId'],
            'user_id' => Auth::id(),
            'published_at' => $validated['publishedAt'],
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        $this->dispatch('notification', message: "Blog \"{$post->title}\" created", type: 'success');
    }

    public function openEditModal(Post $post): void
    {
        $this->editingPost = $post;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->categoryId = $post->category_id;
        $this->image = $post->image;
        $this->publishedAt = $post->published_at?->format('Y-m-d\\TH:i');

        $this->showEditModal = true;
        $this->dispatch('trix:set-content', id: 'edit-post-content', html: $this->content);
    }

    public function updatePost(): void
    {
        if (! $this->editingPost) {
            return;
        }

        $validated = $this->validate();

        $this->editingPost->update([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title'], $this->editingPost->id),
            'content' => $this->sanitizeHtml($validated['content']),
            'image' => $validated['image'],
            'category_id' => $validated['categoryId'],
            'published_at' => $validated['publishedAt'],
        ]);

        $this->showEditModal = false;
        $this->editingPost = null;
        $this->resetForm();
        $this->dispatch('notification', message: 'Blog updated successfully', type: 'success');
    }

    public function openDeleteModal(Post $post): void
    {
        $this->deletingPost = $post;
        $this->showDeleteModal = true;
    }

    public function deletePost(): void
    {
        if ($this->deletingPost) {
            $title = $this->deletingPost->title;
            $this->deletingPost->delete();

            $this->deletingPost = null;
            $this->showDeleteModal = false;
            $this->dispatch('notification', message: "Blog \"{$title}\" deleted", type: 'success');
        }
    }

    public function togglePublish(Post $post): void
    {
        if ($post->published_at) {
            $post->update(['published_at' => null]);
            $this->dispatch('notification', message: "Blog moved to drafts", type: 'success');
        } else {
            $post->update(['published_at' => now()]);
            $this->dispatch('notification', message: "Blog published successfully", type: 'success');
        }
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (
            Post::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function sanitizeHtml(string $html): string
    {
        // Basic hardening for admin-authored HTML. Not a full HTML sanitizer.
        $html = preg_replace('/<\\s*script[^>]*>.*?<\\s*\\/\\s*script\\s*>/is', '', $html) ?? $html;
        $html = preg_replace('/<\\s*style[^>]*>.*?<\\s*\\/\\s*style\\s*>/is', '', $html) ?? $html;
        $html = preg_replace('/on\\w+\\s*=\\s*([\"\\\']).*?\\1/is', '', $html) ?? $html;
        $html = preg_replace('/javascript\\s*:/i', '', $html) ?? $html;

        return $html;
    }

    public function render()
    {
        return view('livewire.admin.blog-manager');
    }
}
