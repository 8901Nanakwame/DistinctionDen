<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Post;
use App\Models\Exam;
use Livewire\Component;

class HomePage extends Component
{
    public function getFeaturedBooksProperty()
    {
        return Book::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->take(6)
            ->get();
    }

    public function getLatestBlogsProperty()
    {
        return Post::with(['category', 'user'])
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function getFeaturedExamsProperty()
    {
        return Exam::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();
    }

    public function getTotalBooksCountProperty()
    {
        return Book::where('stock', '>', 0)->count();
    }

    public function getTotalExamsCountProperty()
    {
        return Exam::where('is_active', true)->count();
    }

    public function getTotalBlogsCountProperty()
    {
        return Post::whereNotNull('published_at')->count();
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}
