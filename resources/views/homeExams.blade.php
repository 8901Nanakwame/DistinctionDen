@extends('layouts.home')

@section('title', 'Exams - ' . config('app.name', 'Laravel'))

@section('content')
    <section class="bg-page py-16 px-4 text-center sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-ink sm:text-5xl">Explore Our Exams</h1>
        <p class="mx-auto mt-4 max-w-2xl text-xl text-ink-muted">
            Prepare for your future with our comprehensive collection of practice exams across various categories.
        </p>

        <div class="mx-auto mt-10 max-w-xl">
            <form action="{{ route('exams.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search exams by title or description..." class="w-full rounded-xl border-none bg-surface py-4 pl-12 pr-4 text-ink shadow-lg focus:ring-2 focus:ring-secondary-400">
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
        @forelse($categories as $category)
            <div class="mb-16">
                <div class="mb-8 flex items-center justify-between border-b border-border pb-4">
                    <h2 class="text-3xl font-bold text-ink">{{ $category->name }}</h2>
                    <span class="text-sm font-medium text-ink-muted">{{ $category->exams->count() }} Exams</span>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                    @foreach($category->exams as $exam)
                        <div class="group flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-sm transition-all duration-300 hover:shadow-lg">
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                @if($exam->image)
                                    <img src="{{ asset('storage/' . $exam->image) }}" alt="{{ $exam->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-50 to-secondary-50 text-primary-200">
                                        <i class="fa-solid fa-file-lines text-6xl opacity-50"></i>
                                    </div>
                                @endif

                                <span class="absolute left-4 top-4 rounded-full bg-primary-800 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                    {{ $category->name }}
                                </span>
                            </div>

                            <div class="flex flex-grow flex-col p-6">
                                <h3 class="mb-3 line-clamp-2 text-xl font-bold transition-colors group-hover:text-primary-800">
                                    <a href="{{ route('exams.show', $exam) }}">{{ $exam->title }}</a>
                                </h3>

                                <p class="mb-4 line-clamp-2 text-sm text-ink-muted">{{ $exam->description }}</p>

                                <div class="mt-auto flex items-center justify-between border-t border-border pt-4 text-xs text-ink-muted">
                                    <div class="flex items-center">
                                        <i class="fa-regular fa-clock mr-2 text-secondary-600"></i>
                                        <span>{{ $exam->duration }} mins</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fa-regular fa-circle-question mr-2 text-secondary-600"></i>
                                        <span>{{ $exam->questions_count }} Questions</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4">
                                    <a href="{{ route('exams.show', $exam) }}" class="block w-full rounded-full border-2 border-primary-700 bg-surface py-2 text-center font-bold text-primary-800 transition-colors duration-300 hover:bg-primary-800 hover:text-white">
                                        Start Exam
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-20 text-center">
                <div class="mb-4 inline-block rounded-full bg-gray-100 p-6">
                    <i class="fa-solid fa-box-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900">No exams found</h3>
                <p class="mt-2 text-gray-500">Check back later for new content.</p>
            </div>
        @endforelse
    </div>
@endsection
