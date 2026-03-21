<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class BookDetail extends Component
{
    public Book $book;
    public bool $showModal = false;

    protected $listeners = ['openBookDetail'];

    public function openBookDetail(int $bookId): void
    {
        $this->book = Book::with('category')->findOrFail($bookId);
        $this->showModal = true;
    }

    public function addToCart(): void
    {
        if ($this->book->stock <= 0) {
            $this->dispatch('notification', message: 'Sorry, this book is out of stock', type: 'error');
            return;
        }

        $sessionId = Session::getId();
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('book_id', $this->book->id)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity >= $this->book->stock) {
                $this->dispatch('notification', message: 'Cannot add more. Maximum stock reached.', type: 'error');
                return;
            }
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'book_id' => $this->book->id,
                'quantity' => 1,
            ]);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('notification', message: '"' . $this->book->title . '" added to cart', type: 'success');
    }

    public function render()
    {
        return view('livewire.book-detail');
    }
}
