<x-layouts::app :title="__('Exam Details')">
    <div class="max-w-4xl mx-auto">
        <div class="rounded-xl border border-border dark:border-zinc-800 bg-surface dark:bg-zinc-950/40 overflow-hidden">
            {{-- Header with Gradient --}}
            <div class="h-48 bg-gradient-to-br from-primary-950 via-primary-900 to-secondary-600 relative overflow-hidden">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white backdrop-blur-sm mb-3">
                        {{ $exam->category->name ?? 'General' }}
                    </span>
                    <h1 class="text-3xl font-bold text-white">{{ $exam->title }}</h1>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-8">
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-ink dark:text-white mb-4">Description</h2>
                    <p class="text-ink-muted dark:text-gray-400">{{ $exam->description ?? 'No description available.' }}</p>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <div class="flex items-center gap-2 mb-2">
                            <flux:icon name="clock" class="h-5 w-5 text-primary-800 dark:text-secondary-300" />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Duration</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ floor($exam->duration / 60) }}h {{ $exam->duration % 60 }}m
                        </p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <div class="flex items-center gap-2 mb-2">
                            <flux:icon name="document-text" class="h-5 w-5 text-primary-800 dark:text-secondary-300" />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Questions</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $exam->questions->count() }}
                        </p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <div class="flex items-center gap-2 mb-2">
                            <flux:icon name="users" class="h-5 w-5 text-primary-800 dark:text-secondary-300" />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Attempts</span>
                        </div>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $exam->attempts->count() }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <flux:button href="{{ route('exams.take', $exam) }}" variant="primary" size="sm" class="flex-1" icon="play">
                        Start Exam
                    </flux:button>
                    <flux:button href="{{ route('exams.index') }}" variant="primary" size="sm">
                        Back to Exams
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
