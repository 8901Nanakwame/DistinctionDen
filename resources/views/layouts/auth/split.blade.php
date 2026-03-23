@props(['title' => null])

@php
    // Set SEO meta tags
    if ($title) {
        SEOMeta::setTitle($title);
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-page text-ink antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full flex-col overflow-hidden p-10 text-white lg:flex border-e border-white/10">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800"></div>
                <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full bg-secondary-400/30 blur-2xl"></div>
                <div class="absolute -right-24 top-24 h-72 w-72 rounded-full bg-primary-500/25 blur-2xl"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/15 backdrop-blur">
                        <x-app-logo-icon class="h-6 fill-current text-white" />
                    </span>
                    <span class="ms-3 tracking-wide">DD ONLINE TUTORIAL</span>
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <flux:heading size="sm">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading>{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-surface shadow-sm ring-1 ring-border">
                            <x-app-logo-icon class="size-7 fill-current text-primary-900 dark:text-white" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'DD Online Tutorial') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
