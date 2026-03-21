@props(['title' => null])

@php
    // Set SEO meta tags
    if ($title) {
        SEOMeta::setTitle($title);
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            .blog-content :where(h1,h2,h3){ font-weight: 700; letter-spacing: -0.01em; }
            .blog-content h1{ font-size: 1.875rem; line-height: 2.25rem; margin: 1.25rem 0 0.75rem; }
            .blog-content h2{ font-size: 1.5rem; line-height: 2rem; margin: 1.25rem 0 0.5rem; }
            .blog-content h3{ font-size: 1.25rem; line-height: 1.75rem; margin: 1rem 0 0.5rem; }
            .blog-content p{ margin: 0.75rem 0; }
            .blog-content ul{ list-style: disc; padding-left: 1.25rem; margin: 0.75rem 0; }
            .blog-content ol{ list-style: decimal; padding-left: 1.25rem; margin: 0.75rem 0; }
            .blog-content a{ text-decoration: underline; text-underline-offset: 2px; }
            .blog-content blockquote{ border-left: 3px solid rgb(99 102 241 / 0.6); padding-left: 0.9rem; color: rgb(100 116 139); }
            .dark .blog-content blockquote{ color: rgb(148 163 184); }
            .blog-content code{ background: rgb(15 23 42 / 0.06); padding: 0.15rem 0.35rem; border-radius: 0.375rem; }
            .dark .blog-content code{ background: rgb(15 23 42 / 0.5); }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100">
        <header class="sticky top-0 z-40 border-b border-zinc-200/80 dark:border-zinc-800 bg-white/80 dark:bg-zinc-950/70 backdrop-blur">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 py-3 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-app-logo-icon class="size-7 fill-current text-zinc-900 dark:text-white" />
                    <span class="font-semibold">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <nav class="flex items-center gap-4 text-sm">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Home</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Blog</a>

                    @guest
                        <a href="{{ route('login') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Login</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-500">Sign up</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-500">Dashboard</a>
                    @endguest
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 sm:px-6 py-10">
            {{ $slot }}
        </main>

        @fluxScripts
    </body>
</html>

