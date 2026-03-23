<x-layouts::app :title="$book->title">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2 bg-gray-50 flex items-center justify-center p-8">
                    @if($book->image)
                        <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="max-w-full h-auto rounded-lg shadow-md">
                    @else
                        <div class="w-full h-64 flex items-center justify-center bg-primary-50 text-primary-200 rounded-lg">
                            <i class="fa-solid fa-book-open text-9xl opacity-50"></i>
                        </div>
                    @endif
                </div>

                <div class="md:w-1/2 p-8 md:p-12">
                    <div class="mb-4">
                        @if($book->category)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary-100 text-primary-800 uppercase tracking-wide">
                                {{ $book->category->name }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4">{{ $book->title }}</h1>
                    <p class="text-lg text-gray-600 mb-6">By <span class="font-semibold text-gray-900">{{ $book->author }}</span></p>

                    <div class="text-4xl font-black text-primary-800 mb-8">
                        GH₵ {{ number_format($book->price, 2) }}
                    </div>

                    <div class="prose prose-sm text-gray-500 mb-8">
                        <p>{{ $book->description ?? 'No description available for this book.' }}</p>
                    </div>

                    <div class="space-y-4">
                        @if($book->stock > 0)
                            <div class="flex items-center text-green-600 text-sm font-medium mb-4">
                                <i class="fa-solid fa-circle-check mr-2"></i> In Stock ({{ $book->stock }} copies available)
                            </div>

                            <livewire:add-to-cart-button :book-id="$book->id" />

                            @if($book->file_path)
                                <a
                                    href="{{ asset('storage/' . $book->file_path) }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="mt-3 inline-flex w-full items-center justify-center rounded-full border border-border bg-surface px-4 py-2 text-sm font-semibold text-primary-800 shadow-sm hover:bg-surface-2"
                                >
                                    Download Book File
                                </a>
                            @endif
                        @else
                            <div class="flex items-center text-red-600 text-sm font-medium mb-4">
                                <i class="fa-solid fa-circle-xmark mr-2"></i> Out of Stock
                            </div>
                            <flux:button disabled variant="filled" class="w-full">Currently Unavailable</flux:button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('home.books') }}" class="inline-flex items-center text-ink-muted hover:text-primary-800 transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Books
            </a>
        </div>
    </div>
</x-layouts::app>
