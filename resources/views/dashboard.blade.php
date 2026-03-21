<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-8">
        {{-- Welcome Section --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 text-white">
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
                    <flux:button href="{{ route('exams.index') }}" icon="academic-cap" class="bg-white text-indigo-600 hover:bg-white/90">
                        Browse Exams
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- Admin Panel Link --}}
        @if(auth()->user()->isAdmin())
            <div class="rounded-xl border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-indigo-100 dark:bg-indigo-900/40 p-3">
                            <flux:icon name="shield-check" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
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
