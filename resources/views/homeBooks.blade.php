@extends('layouts.home')

@section('title', 'Books - ' . config('app.name', 'Laravel'))

@section('content')
    <section class="bg-page py-16 px-4 text-center sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-ink sm:text-5xl">Our Book Collection</h1>
        <p class="mx-auto mt-4 max-w-2xl text-xl text-ink-muted">
            Discover high-quality educational books, guides, and resources for your academic journey.
        </p>

        <div class="mx-auto mt-10 max-w-xl">
            <form action="{{ route('home.books') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search books by title or author..." class="w-full rounded-xl border-none bg-surface py-4 pl-12 pr-4 text-ink shadow-lg focus:ring-2 focus:ring-secondary-400">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </div>
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-primary-800 px-6 py-2 font-bold text-white shadow-sm transition hover:bg-primary-700">
                    Search
                </button>
            </form>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            @forelse($books as $book)
                <div class="group flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-sm transition-all duration-300 hover:shadow-lg">
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        @if($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-50 to-secondary-50 text-primary-200">
                                <i class="fa-solid fa-book-open text-7xl opacity-50"></i>
                            </div>
                        @endif

                        @if($book->category)
                            <span class="absolute left-4 top-4 rounded-full bg-primary-800 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                {{ $book->category->name }}
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-grow flex-col p-6">
                        <h3 class="mb-2 line-clamp-2 text-lg font-bold transition-colors group-hover:text-primary-800">
                            <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                        </h3>

                        <p class="mb-4 text-sm text-ink-muted">By {{ $book->author }}</p>

                        <div class="mt-auto flex items-center justify-between border-t border-border pt-4">
                            <span class="text-xl font-bold text-primary-800">GH₵ {{ number_format($book->price, 2) }}</span>
                            <a href="{{ route('books.show', $book) }}" class="text-sm font-medium text-gray-400 hover:underline">View Details</a>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('books.index') }}" class="block w-full rounded-full bg-primary-800 py-2 text-center font-bold text-white shadow-sm transition-colors duration-300 hover:bg-primary-700">
                                Buy Now
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="mb-4 inline-block rounded-full bg-gray-100 p-6">
                        <i class="fa-solid fa-book-slash text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900">No books found</h3>
                    <p class="mt-2 text-gray-500">Try adjusting your search or check back later.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
