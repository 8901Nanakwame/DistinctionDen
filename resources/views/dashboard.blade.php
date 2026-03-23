<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-8">
        {{-- Welcome Section --}}
        <div class="rounded-2xl border border-border dark:border-zinc-800 bg-gradient-to-r from-primary-900 via-primary-800 to-secondary-600 p-8 text-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="text-white/80">Ready to continue your learning journey?</p>
                </div>
                <div class="flex gap-4">
                    {{-- Theme Toggle Button --}}
                    <flux:button
                        x-on:click="$flux.appearance = $flux.appearance === 'dark' ? 'light' : 'dark'"
                        x-bind:icon="$flux.appearance === 'dark' ? 'sun' : 'moon'"
                        variant="subtle"
                        class="bg-white/20 text-white hover:bg-white/30 border-none"
                    />
                    <flux:button href="{{ route('exams.index') }}" icon="academic-cap" class="bg-white text-primary-900 hover:bg-white/90">
                        Browse Exams
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- Admin Panel Link --}}
        @if(auth()->user()->isAdmin())
            <div class="rounded-2xl border border-border dark:border-zinc-800 bg-surface-2 dark:bg-zinc-950/40 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-primary-100 dark:bg-primary-900/30 p-3">
                            <flux:icon name="shield-check" class="h-6 w-6 text-primary-800 dark:text-secondary-300" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Admin Panel</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage exams and questions</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <flux:button href="{{ route('admin.exams') }}" variant="primary" size="sm">
                            Manage Exams
                        </flux:button>
                        <flux:button href="{{ route('admin.questions') }}" variant="primary" size="sm" icon="plus">
                            Add Questions
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Exam Statistics --}}
        <livewire:dashboard-exam-stats />
    </div>
</x-layouts::app>
