@php
    $navItemClasses = function (bool $active): string {
        $base = 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold transition';
        return $active
            ? $base . ' bg-teal-50 text-teal-700'
            : $base . ' text-gray-700 hover:bg-gray-50 hover:text-teal-700';
    };
@endphp

{{-- Mobile backdrop --}}
<div id="sidebar-backdrop" class="fixed inset-0 z-50 hidden bg-black/40 lg:hidden" aria-hidden="true"></div>

{{-- Mobile sidebar (off-canvas) --}}
<aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full overflow-y-auto border-r border-gray-200 bg-white px-4 py-6 transition-transform duration-200 lg:hidden" aria-label="Sidebar navigation">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-lg font-extrabold text-teal-600 tracking-tight">
            DISTINCTION<span class="text-indigo-900">DEN.</span>
        </a>
        <button id="close-sidebar" type="button" class="rounded-lg p-2 text-gray-600 hover:bg-gray-50 hover:text-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500" aria-label="Close navigation">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <nav class="space-y-1">
        <a href="{{ route('home') }}" class="{{ $navItemClasses(request()->routeIs('home')) }}">
            <i class="fa-solid fa-house text-base"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('exams.index') }}" class="{{ $navItemClasses(request()->routeIs('exams.*')) }}">
            <i class="fa-solid fa-graduation-cap text-base"></i>
            <span>Questions</span>
        </a>
        <a href="{{ route('home.books') }}" class="{{ $navItemClasses(request()->routeIs('home.books')) }}">
            <i class="fa-solid fa-book-open text-base"></i>
            <span>Books</span>
        </a>
        <a href="{{ route('blog.index') }}" class="{{ $navItemClasses(request()->routeIs('blog.*')) }}">
            <i class="fa-solid fa-newspaper text-base"></i>
            <span>Blog</span>
        </a>
    </nav>

    <div class="mt-8 border-t border-gray-100 pt-6">
        @guest
            <div class="space-y-3">
                <a href="{{ route('login') }}" class="block w-full rounded-lg border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Login</a>
                <a href="{{ route('register') }}" class="block w-full rounded-lg bg-indigo-900 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-800">Sign Up</a>
            </div>
        @else
            <a href="{{ route('dashboard') }}" class="block w-full rounded-lg bg-indigo-900 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-800">Dashboard</a>
        @endguest
    </div>
</aside>

{{-- Desktop sidebar --}}
<aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-gray-200 lg:bg-white" aria-label="Sidebar navigation">
    <div class="flex h-20 items-center px-6">
        <a href="{{ route('home') }}" class="text-lg font-extrabold text-teal-600 tracking-tight">
            DISTINCTION<span class="text-indigo-900">DEN.</span>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto px-4 pb-6">
        <nav class="space-y-1">
            <a href="{{ route('home') }}" class="{{ $navItemClasses(request()->routeIs('home')) }}">
                <i class="fa-solid fa-house text-base"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('exams.index') }}" class="{{ $navItemClasses(request()->routeIs('exams.*')) }}">
                <i class="fa-solid fa-graduation-cap text-base"></i>
                <span>Questions</span>
            </a>
            <a href="{{ route('home.books') }}" class="{{ $navItemClasses(request()->routeIs('home.books')) }}">
                <i class="fa-solid fa-book-open text-base"></i>
                <span>Books</span>
            </a>
            <a href="{{ route('blog.index') }}" class="{{ $navItemClasses(request()->routeIs('blog.*')) }}">
                <i class="fa-solid fa-newspaper text-base"></i>
                <span>Blog</span>
            </a>
        </nav>
    </div>

    <div class="border-t border-gray-100 p-4">
        @guest
            <div class="space-y-2">
                <a href="{{ route('login') }}" class="block w-full rounded-lg border border-gray-200 px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Login</a>
                <a href="{{ route('register') }}" class="block w-full rounded-lg bg-indigo-900 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-800">Sign Up</a>
            </div>
        @else
            <a href="{{ route('dashboard') }}" class="block w-full rounded-lg bg-indigo-900 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-800">Dashboard</a>
        @endguest
    </div>
</aside>
