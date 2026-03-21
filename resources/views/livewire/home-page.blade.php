<div>
    <flux:sidebar stashable sticky class="lg:hidden bg-white dark:bg-zinc-900 border-e border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:brand href="{{ route('home') }}" logo="Distinction Den" name="DISTINCTION DEN" class="px-2" />
            <flux:sidebar.close class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('home') }}" :current="request()->routeIs('home')">Home</flux:sidebar.item>
            <flux:sidebar.item icon="academic-cap" href="{{ route('exams.index') }}" :current="request()->routeIs('exams.*')">Questions</flux:sidebar.item>
            <flux:sidebar.item icon="book-open" href="{{ route('home.books') }}" :current="request()->routeIs('home.books')">Books</flux:sidebar.item>
            <flux:sidebar.item icon="newspaper" href="{{ route('blog.index') }}" :current="request()->routeIs('blog.*')">Blog</flux:sidebar.item>

            <flux:sidebar.separator />

            @guest
                <flux:sidebar.item icon="arrow-right-start-on-rectangle" href="{{ route('login') }}">Login</flux:sidebar.item>
                <flux:sidebar.item icon="user-plus" href="{{ route('register') }}" variant="primary">Sign Up Free</flux:sidebar.item>
            @else
                <flux:sidebar.item icon="layout-dashboard" href="{{ route('dashboard') }}">Dashboard</flux:sidebar.item>
            @endguest
        </flux:sidebar.nav>
    </flux:sidebar>

    <flux:header sticky class="bg-white border-b border-gray-100 h-20 px-4 sm:px-6 lg:px-8">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <div class="flex-shrink-0 flex items-center ml-4 lg:ml-0">
            <a href="{{ route('home') }}">
                <span class="text-2xl font-extrabold text-teal-600 tracking-tight">DISTINCTION<span class="text-indigo-900">DEN.</span></span>
            </a>
        </div>

        <flux:spacer />

        <flux:navbar class="hidden md:flex space-x-4">
            <flux:navbar.item href="{{ route('home') }}" :current="request()->routeIs('home')">Home</flux:navbar.item>
            <flux:navbar.item href="{{ route('exams.index') }}" :current="request()->routeIs('exams.*')">Questions</flux:navbar.item>
            <flux:navbar.item href="{{ route('home.books') }}" :current="request()->routeIs('home.books')">Books</flux:navbar.item>
            <flux:navbar.item href="{{ route('blog.index') }}" :current="request()->routeIs('blog.*')">Blog</flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <div class="hidden md:flex items-center space-x-6">
            <button class="text-gray-600 hover:text-teal-600 transition"><i class="fa-solid fa-magnifying-glass"></i></button>
            @guest
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-teal-600 font-medium transition">Login</a>
                <a href="{{ route('register') }}" class="bg-indigo-900 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-indigo-800 transition">Sign Up</a>
            @else
                <a href="{{ route('dashboard') }}" class="bg-indigo-900 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-indigo-800 transition">Dashboard</a>
            @endguest
        </div>
    </flux:header>

    <flux:main class="space-y-16 pb-16">

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
                    Welcome to Distinction Den
                </h1>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Your one-stop platform for educational resources, practice exams, and learning materials
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    @auth
                        <flux:button href="{{ route('exams.index') }}" variant="primary" icon="academic-cap" wire:navigate>
                            Browse Exams
                        </flux:button>
                        <flux:button href="{{ route('books.index') }}" variant="primary" icon="shopping-bag" wire:navigate>
                            Visit Bookshop
                        </flux:button>
                    @else
                        <flux:button href="{{ route('login') }}" variant="primary" icon="academic-cap">
                            Browse Exams
                        </flux:button>
                        <flux:button href="{{ route('register') }}" variant="primary" icon="user-plus">
                            Get Started Free
                        </flux:button>
                        <flux:button href="{{ route('login') }}" variant="ghost" icon="arrow-right-start-on-rectangle">
                            Sign In
                        </flux:button>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
    </section>

    {{-- Statistics Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $this->totalBooksCount }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Books Available</div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $this->totalExamsCount }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Practice Exams</div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-pink-600 dark:text-pink-400">{{ $this->totalBlogsCount }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Blog Posts</div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 text-center shadow-lg">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">24/7</div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Access</div>
            </div>
        </div>
    </section>

    {{-- Featured Books Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📚 Featured Books</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Discover our latest educational resources</p>
            </div>
            <flux:button href="{{ route('books.index') }}" variant="ghost" icon="arrow-right" wire:navigate>
                View All Books
            </flux:button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($this->featuredBooks as $book)
                <div class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 dark:bg-gray-700 cursor-pointer" wire:click="$dispatch('openBookDetail', {bookId: {{ $book->id }}})">
                        @if($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <flux:icon name="book-open" class="w-16 h-16 text-gray-400" />
                            </div>
                        @endif
                    </div>
                    <div class="p-4 cursor-pointer" wire:click="$dispatch('openBookDetail', {bookId: {{ $book->id }}})">
                        <h3 class="font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $book->author }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">GH₵ {{ number_format($book->price, 2) }}</span>
                            @if($book->stock > 0)
                                <span class="text-xs text-green-600 dark:text-green-400">In Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Latest Blog Posts Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📝 Latest Blog Posts</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Stay updated with educational insights</p>
            </div>
            <flux:button href="{{ route('blog.index') }}" variant="ghost" icon="arrow-right" wire:navigate>
                View All Posts
            </flux:button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($this->latestBlogs as $post)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                    <a href="{{ route('blog.show', $post) }}" wire:navigate class="block">
                        @if($post->image)
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @endif
                        <div class="p-4">
                            @if($post->category)
                                <flux:badge size="sm" variant="primary">{{ $post->category->name }}</flux:badge>
                            @endif
                            <h3 class="font-semibold text-gray-900 dark:text-white mt-2 line-clamp-2">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                By {{ $post->user->name }} • {{ $post->published_at->format('M d, Y') }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Featured Exams Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📖 Practice Exams</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Test your knowledge with our exams</p>
            </div>
            @auth
                <flux:button href="{{ route('exams.index') }}" variant="ghost" icon="arrow-right" wire:navigate>
                    View All Exams
                </flux:button>
            @else
                <flux:button href="{{ route('login') }}" variant="ghost" icon="arrow-right">
                    Sign In to View
                </flux:button>
            @endauth
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($this->featuredExams as $exam)
                @auth
                    <a href="{{ route('exams.show', $exam) }}" wire:navigate class="block rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                @else
                    <a href="{{ route('login') }}" class="block rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                @endauth
                    @if($exam->category)
                        <flux:badge size="sm" variant="primary">{{ $exam->category->name }}</flux:badge>
                    @endif
                    <h3 class="font-semibold text-gray-900 dark:text-white mt-3 line-clamp-2">{{ $exam->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">{{ $exam->description }}</p>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->questions->count() }} Questions</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ floor($exam->duration / 60) }}h {{ $exam->duration % 60 }}m</span>
                    </div>
                    @guest
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <flux:button href="{{ route('login') }}" variant="primary" size="sm" icon="arrow-right-start-on-rectangle" class="w-full">
                                Sign In to Access
                            </flux:button>
                        </div>
                    @endguest
                </a>
            @endforeach
        </div>
    </section>

    {{-- Call to Action --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 sm:p-12 text-center text-white shadow-xl">
            <h2 class="text-2xl sm:text-3xl font-bold mb-4">Ready to Start Learning?</h2>
            <p class="text-white/90 mb-6 max-w-xl mx-auto">
                Join thousands of students who are already improving their skills with our educational platform.
            </p>
            @guest
                <flux:button href="{{ route('register') }}" variant="primary" size="sm" icon="user-plus">
                    Create Free Account
                </flux:button>
            @else
                <flux:button href="{{ route('dashboard') }}" variant="primary" size="sm" icon="academic-cap" wire:navigate>
                    Go to Dashboard
                </flux:button>
            @endguest
        </div>
    </section>

    {{-- Book Detail Modal --}}
    <livewire:book-detail />
    </flux:main>
</div>
