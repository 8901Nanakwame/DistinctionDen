@extends('layouts.home')

@section('title', config('app.name', 'Laravel'))

@section('content')

@php
    $stats = \Illuminate\Support\Facades\Cache::remember('homepage_stats', 300, function() {
        return [
            'books' => \App\Models\Book::where('stock', '>', 0)->count(),
            'questions' => \App\Models\Question::count(),
            'exams' => \App\Models\Exam::where('is_active', true)->count(),
            'users' => \App\Models\User::count(),
        ];
    });

    $featuredBooks = \Illuminate\Support\Facades\Cache::remember('homepage_books', 300, function() {
        return \App\Models\Book::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->take(4)
            ->get();
    });

    $latestBlogs = \Illuminate\Support\Facades\Cache::remember('homepage_blogs', 300, function() {
        return \App\Models\Post::with(['category', 'user'])
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(3)
            ->get();
    });

    $featuredExams = \Illuminate\Support\Facades\Cache::remember('homepage_exams', 300, function() {
        return \App\Models\Exam::with(['category', 'questions'])
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();
    });

     $categories = \Illuminate\Support\Facades\Cache::remember('homepage_categories', 300, function() {
         return \App\Models\Category::withCount('exams')->inRandomOrder()->take(8)->get();
    });
@endphp

<section class="relative bg-slate-50 px-10 py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 space-y-6">
            <p class="text-teal-600 font-semibold flex items-center">
                <span class="mr-2 text-yellow-500">✨</span> Learn From {{ $stats['exams'] }}+ Quality Exams
            </p>
            <h1 class="text-6xl font-extrabold leading-tight">
                Best Platform to <br> <span class="text-indigo-900">Empower Skills</span>
            </h1>
            <a href="{{ route('exams.index') }}" class="inline-block bg-teal-500 text-white px-8 py-4 rounded-md font-bold text-lg hover:bg-teal-600 transition">
                Start Learning Now
            </a>
            <p class="text-gray-500 text-sm italic">Start Your Education Journey, For a Better Future</p>
        </div>

        <div class="md:w-1/2 mt-12 md:mt-0 relative">
            <div class="bg-gray-200 rounded-full w-[500px] h-[500px] absolute -top-10 -right-10 opacity-20"></div>
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=800" alt="Student" class="relative z-10 rounded-lg">

            <div class="absolute bottom-10 -left-10 z-20 bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center">
                <div class="flex -space-x-3 mb-2">
                    <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?u=1" alt="">
                    <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?u=2" alt="">
                    <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?u=3" alt="">
                    <div class="w-10 h-10 rounded-full border-2 border-white bg-orange-400 flex items-center justify-center text-white text-xs">{{ $stats['users'] > 1000 ? floor($stats['users']/1000).'K+' : $stats['users'] }}+</div>
                </div>
                <p class="text-xl font-bold">{{ $stats['users'] }}+</p>
                <p class="text-xs text-gray-400">Total Enrolled Students</p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 px-10 max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-12">Top Categories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <div class="flex items-center p-4 bg-purple-50 rounded-xl hover:shadow-md transition cursor-pointer">
            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 text-purple-600 shadow-sm">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold">{{ $category->name }}</span>
                <span class="text-xs text-gray-500">{{ $category->exams_count }} Exams</span>
            </div>
        </div>
        @endforeach
        {{-- Fill up to 8 if fewer categories --}}
        @for($i = $categories->count(); $i < 4; $i++)
             <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:shadow-md transition cursor-pointer opacity-50">
                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 text-gray-600 shadow-sm">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <span class="font-bold">Coming Soon</span>
            </div>
        @endfor
    </div>
</section>

<section class="py-10 px-10 max-w-7xl mx-auto grid md:grid-cols-2 gap-8">
    <div class="bg-slate-50 p-10 rounded-2xl flex items-center justify-between border border-gray-100">
        <div>
            <p class="text-teal-600 font-medium">Learn together with</p>
            <h3 class="text-3xl font-bold mb-4">Expert Teacher</h3>
            <p class="text-gray-500 mb-6 text-sm">If you've been researching exactly what skill you want</p>
            <a href="{{ route('exams.index') }}" class="bg-teal-600 text-white px-6 py-2 rounded-md font-bold">View All Questions</a>
        </div>
        <div class="w-32 h-32 bg-gray-300 rounded-lg"></div>
    </div>
    <div class="bg-blue-50 p-10 rounded-2xl flex items-center justify-between border border-gray-100">
        <div>
            <p class="text-blue-600 font-medium">Get the skills</p>
            <h3 class="text-3xl font-bold mb-4">For Individuals</h3>
            <p class="text-gray-500 mb-6 text-sm">If you've been researching exactly what skill you want</p>
            <a href="{{ route('books.index') }}" class="bg-teal-600 text-white px-6 py-2 rounded-md font-bold">Find Your Book</a>
        </div>
        <div class="w-32 h-32 bg-gray-300 rounded-lg"></div>
    </div>
</section>

<section class="py-20 px-10 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-3xl font-bold">Popular Exams</h2>
        <a href="{{ route('exams.index') }}" class="border border-teal-600 text-teal-600 px-4 py-2 rounded hover:bg-teal-600 hover:text-white transition">View All Exams</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($featuredExams as $exam)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="relative">
                @if($exam->image)
                    <img src="{{ asset('storage/' . $exam->image) }}" alt="{{ $exam->title }}" class="w-full h-48 object-cover">
                @else
                    <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=400" alt="Course" class="w-full h-48 object-cover">
                @endif
                @if($exam->category)
                    <span class="absolute top-4 left-4 bg-teal-600 text-white text-xs px-3 py-1 rounded">{{ $exam->category->name }}</span>
                @endif
                <button class="absolute top-4 right-4 bg-white/80 p-2 rounded-full text-gray-600 hover:text-red-500"><i class="fa-regular fa-heart"></i></button>
            </div>
            <div class="p-5">
                <div class="flex items-center mb-3">
                   {{-- <img class="w-6 h-6 rounded-full mr-2" src="https://i.pravatar.cc/50?u=9" alt="">
                    <span class="text-xs text-gray-500">Lucas Vaughn</span> --}}
                </div>
                <h4 class="font-bold text-lg mb-4 group-hover:text-teal-600 transition">{{ $exam->title }}</h4>
                <div class="flex items-center text-xs text-gray-400 mb-4">
                    <i class="fa-regular fa-calendar mr-2"></i> {{ $exam->questions_count ?? 0 }} Questions
                </div>
                <hr class="mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-orange-500 font-bold">Free</span>
                    <a href="{{ route('exams.show', $exam) }}" class="text-gray-400 text-sm hover:underline font-medium">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="py-20 px-10 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-3xl font-bold">Featured Books</h2>
        <a href="{{ route('books.index') }}" class="border border-teal-600 text-teal-600 px-4 py-2 rounded hover:bg-teal-600 hover:text-white transition">View All Books</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($featuredBooks as $book)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
            <div class="relative">
                 @if($book->image)
                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-book text-3xl"></i>
                    </div>
                @endif

                @if($book->category)
                    <span class="absolute top-4 left-4 bg-blue-600 text-white text-xs px-3 py-1 rounded">{{ $book->category->name }}</span>
                @endif
            </div>
            <div class="p-5">
                <h4 class="font-bold text-lg mb-4 group-hover:text-teal-600 transition truncate">{{ $book->title }}</h4>
                <div class="flex items-center text-xs text-gray-400 mb-4">
                    <i class="fa-solid fa-pen-nib mr-2"></i> {{ $book->author }}
                </div>
                <hr class="mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-orange-500 font-bold">GH₵ {{ number_format($book->price, 2) }}</span>
                    <a href="{{ route('books.show', $book) }}" class="text-gray-400 text-sm hover:underline font-medium">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

@endsection
