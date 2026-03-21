<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ShoppingCart extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function getCartItems()
    {
        $sessionId = Session::getId();
        return Cart::where('session_id', $sessionId)
            ->with('book')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'book' => $item->book,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->book->price * $item->quantity,
                ];
            });
    }

    public function getCartTotal(): float
    {
        $sessionId = Session::getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('book')->get();

        return $cartItems->sum(function ($item) {
            return $item->book->price * $item->quantity;
        });
    }

    public function getCartCount(): int
    {
        $sessionId = Session::getId();
        return Cart::where('session_id', $sessionId)->sum('quantity');
    }

    public function incrementQuantity(int $cartId): void
    {
        $cartItem = Cart::findOrFail($cartId);

        if ($cartItem->quantity >= $cartItem->book->stock) {
            $this->dispatch('notification', message: 'Maximum stock reached for this book', type: 'error');
            return;
        }

        $cartItem->increment('quantity');
        $this->dispatch('cartUpdated');
        $this->dispatch('notification', message: 'Quantity updated', type: 'success');
    }

    public function decrementQuantity(int $cartId): void
    {
        $cartItem = Cart::findOrFail($cartId);

        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } else {
            $this->removeFromCart($cartId);
            return;
        }

        $this->dispatch('cartUpdated');
    }

    public function removeFromCart(int $cartId): void
    {
        $cartItem = Cart::findOrFail($cartId);
        $bookTitle = $cartItem->book->title;
        $cartItem->delete();

        $this->dispatch('cartUpdated');
        $this->dispatch('notification', message: '"' . $bookTitle . '" removed from cart', type: 'success');
    }

    public function clearCart(): void
    {
        $sessionId = Session::getId();
        Cart::where('session_id', $sessionId)->delete();

        $this->dispatch('cartUpdated');
        $this->dispatch('notification', message: 'Cart cleared', type: 'success');
    }

    public function checkout()
    {
        $sessionId = Session::getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('book')->get();

        if ($cartItems->isEmpty()) {
            $this->dispatch('notification', message: 'Your cart is empty', type: 'error');
            return;
        }

        DB::beginTransaction();
        try {
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->book->price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => 'pending',
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $cartItem->book_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->book->price,
                ]);

                $cartItem->book->decrement('stock', $cartItem->quantity);
                $cartItem->delete();
            }

            DB::commit();

            $this->dispatch('cartUpdated');
            $this->dispatch('notification', message: 'Order placed successfully! Order #' . $order->id, type: 'success');

            return redirect()->route('orders.show', $order);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notification', message: 'Checkout failed. Please try again.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.shopping-cart', [
            'cartItems' => $this->getCartItems(),
            'cartTotal' => $this->getCartTotal(),
            'cartCount' => $this->getCartCount(),
        ]);
    }
}
