<header class="sticky top-0 z-40 border-b border-border bg-surface/80 backdrop-blur">
    <div class="mx-auto flex h-20 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">
        <button id="open-sidebar" type="button" class="inline-flex items-center justify-center rounded-lg p-2 text-ink-muted hover:bg-surface-2 hover:text-primary-800 focus:outline-none focus:ring-2 focus:ring-secondary-400 lg:hidden" aria-label="Open navigation">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <a href="{{ route('home') }}" class="flex items-center">
            <span class="text-2xl font-extrabold text-primary-800 tracking-tight">
                DISTINCTION<span class="text-secondary-500">DEN.</span>
            </span>
        </a>

        <div class="flex-1"></div>

        <nav class="hidden items-center gap-6 text-sm font-medium text-ink-muted lg:flex">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary-800' : 'hover:text-primary-800' }}">Home</a>
            <a href="{{ route('exams-list') }}" class="{{ request()->routeIs('exams.*') ? 'text-primary-800' : 'hover:text-primary-800' }}">Questions</a>
            <a href="{{ route('home.books') }}" class="{{ request()->routeIs('home.books') ? 'text-primary-800' : 'hover:text-primary-800' }}">Books</a>
            <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'text-primary-800' : 'hover:text-primary-800' }}">Blog</a>
        </nav>

        <div class="hidden items-center gap-4 lg:flex">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-medium text-ink-muted hover:text-primary-800">Login</a>
                <a href="{{ route('register') }}" class="rounded-full bg-primary-800 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-secondary-400 focus:ring-offset-2 focus:ring-offset-surface">Sign Up</a>
            @else
                <a href="{{ route('dashboard') }}" class="rounded-full bg-primary-800 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-secondary-400 focus:ring-offset-2 focus:ring-offset-surface">Dashboard</a>
            @endguest
        </div>
    </div>
</header>
