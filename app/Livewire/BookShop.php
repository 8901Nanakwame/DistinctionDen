<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class BookShop extends Component
{
    use WithPagination;

    public string $searchTerm = '';
    public ?int $selectedCategory = null;
    public int $sortBy = 0; // 0: default, 1: price low-high, 2: price high-low, 3: title A-Z

    protected $listeners = ['cartUpdated' => '$refresh'];

    #[Computed]
    public function books()
    {
        $query = Book::query()->with('category')->where('stock', '>', 0);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('author', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        switch ($this->sortBy) {
            case 1:
                $query->orderBy('price', 'asc');
                break;
            case 2:
                $query->orderBy('price', 'desc');
                break;
            case 3:
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->latest();
        }

        return $query->paginate(12);
    }

    #[Computed]
    public function categories()
    {
        return Category::where('type', 'book')->get();
    }

    public function addToCart(int $bookId): void
    {
        $book = Book::findOrFail($bookId);

        if ($book->stock <= 0) {
            $this->dispatch('notification', message: 'Sorry, this book is out of stock', type: 'error');
            return;
        }

        $sessionId = Session::getId();
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('book_id', $bookId)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity >= $book->stock) {
                $this->dispatch('notification', message: 'Cannot add more. Maximum stock reached.', type: 'error');
                return;
            }
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'book_id' => $bookId,
                'quantity' => 1,
            ]);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('notification', message: '"' . $book->title . '" added to cart', type: 'success');
    }

    public function getCartCount(): int
    {
        $sessionId = Session::getId();
        return Cart::where('session_id', $sessionId)->sum('quantity');
    }

    public function resetFilters(): void
    {
        $this->searchTerm = '';
        $this->selectedCategory = null;
        $this->sortBy = 0;
    }

    public function render()
    {
        return view('livewire.book-shop', [
            'books' => $this->books,
            'categories' => $this->categories,
            'cartCount' => $this->getCartCount(),
        ]);
    }
}
