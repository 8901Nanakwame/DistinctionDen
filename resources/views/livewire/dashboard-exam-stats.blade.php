<div class="space-y-6">


    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Exam Dashboard</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Track your exam performance and progress</p>
        </div>
        <flux:button href="{{ route('exams.index') }}" variant="primary" icon="plus">
            Start New Exam
        </flux:button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Exams Taken --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Exams</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $this->totalExamsTaken }}</p>
                </div>
                <div class="rounded-full bg-indigo-100 dark:bg-indigo-900/30 p-3">
                    <flux:icon name="academic-cap" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">Lifetime attempts</p>
            </div>
        </div>

        {{-- Average Score --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Average Score</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $this->averageScore }}%</p>
                </div>
                <div class="rounded-full bg-green-100 dark:bg-green-900/30 p-3">
                    <flux:icon name="chart-bar" class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">Across all exams</p>
            </div>
        </div>

        {{-- Highest Score --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Best Score</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $this->highestScore }}%</p>
                </div>
                <div class="rounded-full bg-yellow-100 dark:bg-yellow-900/30 p-3">
                    <flux:icon name="trophy" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">Your personal best</p>
            </div>
        </div>

        {{-- Latest Attempt --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Latest Exam</p>
                    @if($this->latestAttempt)
                        <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white truncate max-w-[150px]">
                            {{ Str::limit($this->latestAttempt->exam->title ?? 'N/A', 20) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Score: {{ $this->latestAttempt->score }}%
                        </p>
                    @else
                        <p class="mt-2 text-sm text-gray-400">No attempts yet</p>
                    @endif
                </div>
                <div class="rounded-full bg-blue-100 dark:bg-blue-900/30 p-3">
                    <flux:icon name="clock" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">Most recent activity</p>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Performance Trend Chart --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Trend</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                @forelse($this->performanceTrend as $index => $attempt)
                    <div class="flex-1 flex flex-col items-center">
                        <div
                            class="w-full bg-gradient-to-t from-indigo-500 to-indigo-400 rounded-t transition-all hover:from-indigo-600 hover:to-indigo-500"
                            style="height: {{ max($attempt->score, 10) }}%;"
                            title="Score: {{ $attempt->score }}%"
                        ></div>
                        <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            {{ $attempt->completed_at?->format('M d') ?? 'N/A' }}
                        </span>
                    </div>
                @empty
                    <div class="flex-1 flex items-center justify-center">
                        <p class="text-gray-500 dark:text-gray-400">No exam data yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Score by Question Type --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance by Question Type</h3>
            <div class="space-y-4">
                @forelse($this->scoreByQuestionType as $typeData)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ ucfirst(str_replace('_', ' ', $typeData->type)) }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ round($typeData->avg_score, 1) }}%
                            </span>
                        </div>
                        <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div
                                class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full transition-all"
                                style="width: {{ max($typeData->avg_score, 10) }}%;"
                            ></div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No question type data available</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Attempts Table --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Exam Attempts</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Exam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->recentAttempts as $attempt)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $attempt->exam->title ?? 'Unknown Exam' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($attempt->score >= 80) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($attempt->score >= 60) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @endif">
                                    {{ $attempt->score }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="h-2 w-2 rounded-full
                                        @if($attempt->completed_at) bg-green-500 @else bg-yellow-500 @endif">
                                    </span>
                                    {{ $attempt->completed_at ? 'Completed' : 'In Progress' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $attempt->completed_at?->format('M d, Y H:i') ?? 'Not completed' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <flux:button href="{{ route('exams.show', $attempt->exam) }}" variant="ghost" size="sm">
                                    Review
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <flux:icon name="academic-cap" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" />
                                <p class="mt-4 text-gray-500 dark:text-gray-400">No exam attempts yet</p>
                                <flux:button href="{{ route('exams.index') }}" variant="primary" size="sm" class="mt-4">
                                    Browse Exams
                                </flux:button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
