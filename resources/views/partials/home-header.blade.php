<header class="sticky top-0 z-40 border-b border-gray-100 bg-white">
    <div class="mx-auto flex h-20 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">
        <button id="open-sidebar" type="button" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-600 hover:bg-gray-50 hover:text-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 lg:hidden" aria-label="Open navigation">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <a href="{{ route('home') }}" class="flex items-center">
            <span class="text-2xl font-extrabold text-teal-600 tracking-tight">
                DISTINCTION<span class="text-indigo-900">DEN.</span>
            </span>
        </a>

        <div class="flex-1"></div>

        <nav class="hidden items-center gap-6 text-sm font-medium text-gray-600 lg:flex">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-teal-600' : 'hover:text-teal-600' }}">Home</a>
            <a href="{{ route('exams.index') }}" class="{{ request()->routeIs('exams.*') ? 'text-teal-600' : 'hover:text-teal-600' }}">Questions</a>
            <a href="{{ route('home.books') }}" class="{{ request()->routeIs('home.books') ? 'text-teal-600' : 'hover:text-teal-600' }}">Books</a>
            <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'text-teal-600' : 'hover:text-teal-600' }}">Blog</a>
        </nav>

        <div class="hidden items-center gap-4 lg:flex">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-teal-600">Login</a>
                <a href="{{ route('register') }}" class="rounded-lg bg-indigo-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-800">Sign Up</a>
            @else
                <a href="{{ route('dashboard') }}" class="rounded-lg bg-indigo-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-800">Dashboard</a>
            @endguest
        </div>
    </div>
</header>
