@extends('layouts.home')

@section('title', 'Exams - ' . config('app.name', 'Laravel'))

@section('content')
    <section class="bg-slate-50 py-16 px-4 text-center sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">Explore Our Exams</h1>
        <p class="mx-auto mt-4 max-w-2xl text-xl text-gray-500">
            Prepare for your future with our comprehensive collection of practice exams across various categories.
        </p>

        <div class="mx-auto mt-10 max-w-xl">
            <form action="{{ route('exams.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search exams by title or description..." class="w-full rounded-xl border-none py-4 pl-12 pr-4 text-gray-900 shadow-lg focus:ring-2 focus:ring-teal-500">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </div>
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg bg-teal-600 px-6 py-2 font-bold text-white transition hover:bg-teal-700">
                    Search
                </button>
            </form>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @forelse($categories as $category)
            <div class="mb-16">
                <div class="mb-8 flex items-center justify-between border-b border-gray-200 pb-4">
                    <h2 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h2>
                    <span class="text-sm font-medium text-gray-500">{{ $category->exams->count() }} Exams</span>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                    @foreach($category->exams as $exam)
                        <div class="group flex flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-lg">
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                @if($exam->image)
                                    <img src="{{ asset('storage/' . $exam->image) }}" alt="{{ $exam->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-teal-50 to-blue-50 text-teal-200">
                                        <i class="fa-solid fa-file-lines text-6xl opacity-50"></i>
                                    </div>
                                @endif

                                <span class="absolute left-4 top-4 rounded bg-teal-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                    {{ $category->name }}
                                </span>
                            </div>

                            <div class="flex flex-grow flex-col p-6">
                                <h3 class="mb-3 line-clamp-2 text-xl font-bold transition-colors group-hover:text-teal-600">
                                    <a href="{{ route('exams.show', $exam) }}">{{ $exam->title }}</a>
                                </h3>

                                <p class="mb-4 line-clamp-2 text-sm text-gray-500">{{ $exam->description }}</p>

                                <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4 text-xs text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fa-regular fa-clock mr-2 text-teal-500"></i>
                                        <span>{{ $exam->duration }} mins</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fa-regular fa-circle-question mr-2 text-teal-500"></i>
                                        <span>{{ $exam->questions_count }} Questions</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4">
                                    <a href="{{ route('exams.show', $exam) }}" class="block w-full rounded-lg border-2 border-teal-500 bg-white py-2 text-center font-bold text-teal-600 transition-colors duration-300 hover:bg-teal-600 hover:text-white">
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
