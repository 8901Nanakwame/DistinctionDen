<?php

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

new class extends Component
{
    public int $bookId;

    public function addToCart(): void
    {
        $book = Book::findOrFail($this->bookId);

        if ($book->stock <= 0) {
            $this->dispatch('notification', message: 'Sorry, this book is out of stock', type: 'error');
            return;
        }

        $sessionId = Session::getId();
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('book_id', $this->bookId)
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
                'book_id' => $this->bookId,
                'quantity' => 1,
            ]);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('notification', message: '"' . $book->title . '" added to cart', type: 'success');
    }
};
?>

<div class="w-full">
    <flux:button wire:click="addToCart" variant="primary" class="w-full" icon="shopping-cart">
        Add to Cart
    </flux:button>
</div>
