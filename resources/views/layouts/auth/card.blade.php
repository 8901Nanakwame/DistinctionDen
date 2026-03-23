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
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-surface shadow-sm ring-1 ring-border">
                        <x-app-logo-icon class="size-7 fill-current text-primary-900 dark:text-white" />
                    </span>

                    <span class="sr-only">{{ config('app.name', 'DD Online Tutorial') }}</span>
                </a>

                <div class="flex flex-col gap-6">
                    <div class="dd-card dark:bg-zinc-950/40 dark:border-zinc-800">
                        <div class="px-10 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
