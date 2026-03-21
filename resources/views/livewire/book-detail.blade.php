<div>
    <flux:modal wire:model="showModal" class="max-w-3xl">
        <div class="space-y-6">
            @if($book)
                {{-- Header with Book Cover and Info --}}
                <div class="flex gap-6">
                    {{-- Book Cover --}}
                    <div class="flex-shrink-0">
                        <div class="w-40 h-56 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 shadow-lg">
                            @if($book->image)
                                <img
                                    src="{{ asset('storage/' . $book->image) }}"
                                    alt="{{ $book->title }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <flux:icon name="book-open" class="w-16 h-16 text-gray-400" />
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Book Info --}}
                    <div class="flex-1">
                        @if($book->category)
                            <flux:badge size="sm" variant="primary">
                                {{ $book->category->name }}
                            </flux:badge>
                        @endif

                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $book->title }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            by <span class="font-semibold">{{ $book->author }}</span>
                        </p>

                        <div class="mt-4 flex items-center gap-4">
                            <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                GH₵ {{ number_format($book->price, 2) }}
                            </span>
                            @if($book->stock > 0)
                                <span class="text-sm text-green-600 dark:text-green-400 font-medium">
                                    ✓ In Stock ({{ $book->stock }} available)
                                </span>
                            @else
                                <span class="text-sm text-red-600 dark:text-red-400 font-medium">
                                    ✕ Out of Stock
                                </span>
                            @endif
                        </div>

                        <flux:button
                            wire:click="addToCart"
                            wire:loading.attr="disabled"
                            variant="primary"
                            icon="shopping-cart"
                            class="mt-4"
                            :disabled="$book->stock <= 0"
                        >
                            Add to Cart
                        </flux:button>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Description</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ $book->description ?? 'No description available.' }}
                    </p>
                </div>

                {{-- Book Details Grid --}}
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Author</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $book->author }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Category</span>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $book->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Availability</span>
                        <p class="font-medium {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $book->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Price</span>
                        <p class="font-medium text-indigo-600 dark:text-indigo-400">GH₵ {{ number_format($book->price, 2) }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">
                        Close
                    </flux:button>
                    <flux:button
                        href="{{ route('books.index') }}"
                        wire:navigate
                        variant="primary"
                        icon="shopping-bag"
                    >
                        Browse More Books
                    </flux:button>
                </div>
            @endif
        </div>
    </flux:modal>
</div>
