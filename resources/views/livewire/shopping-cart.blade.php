<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">🛒 Shopping Cart</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Review your items before checkout</p>
        </div>
        <flux:button href="{{ route('books.index') }}" wire:navigate variant="ghost" icon="arrow-left">
            Continue Shopping
        </flux:button>
    </div>

    @if(count($cartItems) > 0)
        {{-- Cart Items --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($cartItems as $item)
                    <div class="p-6 flex gap-4 sm:gap-6">
                        {{-- Book Cover --}}
                        <div class="flex-shrink-0">
                            <div class="w-20 h-28 sm:w-24 sm:h-32 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                @if($item['book']->image)
                                    <img
                                        src="{{ asset('storage/' . $item['book']->image) }}"
                                        alt="{{ $item['book']->title }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <flux:icon name="book-open" class="w-8 h-8 text-gray-400" />
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Book Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white line-clamp-1">
                                        {{ $item['book']->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item['book']->author }}</p>
                                    @if($item['book']->category)
                                        <flux:badge size="sm" variant="primary" class="mt-2">
                                            {{ $item['book']->category->name }}
                                        </flux:badge>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-primary-800 dark:text-secondary-300">
                                        GH₵ {{ number_format($item['subtotal'], 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        GH₵ {{ number_format($item['book']->price, 2) }} each
                                    </p>
                                </div>
                            </div>

                            {{-- Quantity Controls --}}
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <flux:button
                                        wire:click="decrementQuantity({{ $item['id'] }})"
                                        variant="ghost"
                                        size="sm"
                                        icon="minus"
                                        class="h-8 w-8 p-0"
                                    />
                                    <span class="w-12 text-center font-semibold text-gray-900 dark:text-white">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <flux:button
                                        wire:click="incrementQuantity({{ $item['id'] }})"
                                        variant="ghost"
                                        size="sm"
                                        icon="plus"
                                        class="h-8 w-8 p-0"
                                    />
                                </div>

                                <flux:button
                                    wire:click="removeFromCart({{ $item['id'] }})"
                                    variant="ghost"
                                    size="sm"
                                    class="text-red-600 hover:text-red-700"
                                    icon="trash"
                                >
                                    Remove
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>

            <div class="space-y-3">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Subtotal ({{ $cartCount }} items)</span>
                    <span>GH₵ {{ number_format($cartTotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Shipping</span>
                    <span class="text-green-600 dark:text-green-400">Free</span>
                </div>
                <flux:separator />
                <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                    <span>Total</span>
                    <span class="text-primary-800 dark:text-secondary-300">GH₵ {{ number_format($cartTotal, 2) }}</span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <flux:button
                    wire:click="checkout"
                    variant="primary"
                    icon="credit-card"
                    class="w-full"
                    size="sm"
                >
                    Proceed to Checkout
                </flux:button>
                <flux:button
                    wire:click="clearCart"
                    variant="ghost"
                    icon="trash"
                    class="w-full"
                >
                    Clear Cart
                </flux:button>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-12 text-center shadow-sm">
            <flux:icon name="shopping-cart" class="mx-auto h-20 w-20 text-gray-400" />
            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Your cart is empty</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                Looks like you haven't added any books to your cart yet.
            </p>
            <flux:button
                href="{{ route('books.index') }}"
                wire:navigate
                variant="primary"
                icon="shopping-bag"
                class="mt-6"
            >
                Start Shopping
            </flux:button>
        </div>
    @endif
</div>
