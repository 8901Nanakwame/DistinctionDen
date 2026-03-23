<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-page text-ink dark:bg-zinc-950 dark:text-zinc-100">
        <x-layouts::app.sidebar :title="$title ?? null">
            <flux:main>
                {{ $slot }}
            </flux:main>
        </x-layouts::app.sidebar>

        @fluxScripts
    </body>
</html>
