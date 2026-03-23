<div class="max-w-7xl mx-auto space-y-6">
    {{-- Book Detail Modal --}}
    <livewire:book-detail />

    {{-- Header with Cart Icon --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📚 Bookshop</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Browse and purchase books for your learning journey</p>
        </div>
        <flux:button href="{{ route('cart.index') }}" wire:navigate variant="primary" icon="shopping-cart">
            Cart
            <flux:badge variant="primary" size="sm" class="ml-1">{{ $cartCount }}</flux:badge>
        </flux:button>
    </div>

    {{-- Filters and Search --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Search --}}
        <div class="md:col-span-2">
            <flux:input
                wire:model.live.debounce.300ms="searchTerm"
                placeholder="Search books by title or author..."
                icon="magnifying-glass"
            />
        </div>

        {{-- Category Filter --}}
        <flux:select wire:model.live="selectedCategory" icon="folder">
            <flux:select.option value="">All Categories</flux:select.option>
            @foreach($categories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
            @endforeach
        </flux:select>

        {{-- Sort --}}
        <flux:select wire:model.live="sortBy" icon="chevrons-up-down">
            <flux:select.option value="0">Default</flux:select.option>
            <flux:select.option value="1">Price: Low to High</flux:select.option>
            <flux:select.option value="2">Price: High to Low</flux:select.option>
            <flux:select.option value="3">Title: A to Z</flux:select.option>
        </flux:select>
    </div>

    {{-- Reset Filters Button --}}
    @if($searchTerm || $selectedCategory || $sortBy)
        <flux:button wire:click="resetFilters" variant="ghost" size="sm" icon="x-mark">
            Reset Filters
        </flux:button>
    @endif

    {{-- Books Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 m-12">
        @forelse($books as $book)
            <div class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                {{-- Book Cover --}}
                <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 dark:bg-gray-700 cursor-pointer" wire:click="$dispatch('openBookDetail', {bookId: {{ $book->id }}})">
                    @if($book->image)
                        <img
                            src="{{ asset('storage/' . $book->image) }}"
                            alt="{{ $book->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <flux:icon name="book-open" class="w-16 h-16 text-gray-400" />
                        </div>
                    @endif

                    {{-- Quick Add Button Overlay --}}
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                        <flux:button
                            wire:click.stop="addToCart({{ $book->id }})"
                            wire:loading.attr="disabled"
                            variant="primary"
                            icon="plus"
                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300"
                        >
                            Add to Cart
                        </flux:button>
                        <flux:button
                            wire:click.stop="$dispatch('openBookDetail', {bookId: {{ $book->id }}})"
                            variant="primary"
                            icon="eye"
                            class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300"
                        >
                            View
                        </flux:button>
                    </div>
                </div>

                {{-- Book Info --}}
                <div class="p-4 space-y-3 cursor-pointer" wire:click="$dispatch('openBookDetail', {bookId: {{ $book->id }}})">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white line-clamp-1" title="{{ $book->title }}">
                            {{ $book->title }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $book->author }}</p>
                    </div>

                    @if($book->category)
                        <flux:badge size="sm" variant="primary">
                            {{ $book->category->name }}
                        </flux:badge>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-primary-800 dark:text-secondary-300">
                            GH₵ {{ number_format($book->price, 2) }}
                        </span>
                        @if($book->stock > 0)
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">
                                In Stock ({{ $book->stock }})
                            </span>
                        @else
                            <span class="text-xs text-red-600 dark:text-red-400 font-medium">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    {{-- Mobile Add Button --}}
                    @if($book->stock > 0)
                        <flux:button
                            wire:click.stop="addToCart({{ $book->id }})"
                            wire:loading.attr="disabled"
                            variant="primary"
                            icon="shopping-cart"
                            class="w-full sm:hidden"
                        >
                            Add to Cart
                        </flux:button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16">
                    <flux:icon name="book-open" class="mx-auto h-16 w-16 text-gray-400" />
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No books found</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        @if($searchTerm || $selectedCategory)
                            Try adjusting your search or filters
                        @else
                            Check back later for new arrivals
                        @endif
                    </p>
                    @if($searchTerm || $selectedCategory || $sortBy)
                        <flux:button wire:click="resetFilters" variant="primary" class="mt-4" icon="x-mark">
                            Reset Filters
                        </flux:button>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($books->hasPages())
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @endif
</div>
