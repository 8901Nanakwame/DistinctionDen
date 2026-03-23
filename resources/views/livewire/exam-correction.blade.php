<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header with Exam Info --}}
    <div class="rounded-xl border border-border dark:border-zinc-800 bg-surface dark:bg-zinc-950/40 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-ink dark:text-white mb-2">Exam Correction</h1>
                <p class="text-ink-muted dark:text-gray-400">{{ $exam->title }}</p>
            </div>
            <flux:button href="{{ route('exams.take', $exam) }}" variant="ghost" icon="arrow-left">
                Back to Exam
            </flux:button>
        </div>

        {{-- Score Summary --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="text-center p-4 rounded-lg bg-primary-50 dark:bg-primary-900/20">
                <p class="text-3xl font-bold text-primary-800 dark:text-secondary-300">{{ $attempt->score }}%</p>
                <p class="text-sm text-ink-muted dark:text-gray-400 mt-1">Your Score</p>
            </div>
            <div class="text-center p-4 rounded-lg bg-green-50 dark:bg-green-900/20">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $this->correctCount }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Correct</p>
            </div>
            <div class="text-center p-4 rounded-lg bg-red-50 dark:bg-red-900/20">
                <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $this->incorrectCount }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Incorrect</p>
            </div>
            <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-900/20">
                <p class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $this->unansweredCount }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Unanswered</p>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex items-center justify-center gap-6 text-sm">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-green-100 dark:bg-green-900/30 border-2 border-green-500"></div>
            <span class="text-ink-muted dark:text-gray-400">Correct Answer</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-red-100 dark:bg-red-900/30 border-2 border-red-500"></div>
            <span class="text-ink-muted dark:text-gray-400">Your Answer (Incorrect)</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-gray-100 dark:bg-gray-700 border-2 border-gray-400"></div>
            <span class="text-ink-muted dark:text-gray-400">Unanswered</span>
        </div>
    </div>

    {{-- Questions Review --}}
    <div class="space-y-6">
        @foreach($this->questions as $index => $question)
            @php
                $userAnswer = $this->getUserAnswer($question->id);
                $isCorrect = $this->isQuestionCorrect($question->id);
                $isUnanswered = $userAnswer === null;
            @endphp

            <div class="rounded-xl border border-border dark:border-zinc-800 bg-surface dark:bg-zinc-950/40 p-6 shadow-sm
                @if($isCorrect) border-l-4 border-l-green-500
                @elseif($isUnanswered) border-l-4 border-l-gray-400
                @else border-l-4 border-l-red-500
                @endif">

                {{-- Question Header --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-secondary-300 font-semibold text-sm">
                            {{ $index + 1 }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-900 dark:bg-primary-900/30 dark:text-secondary-300">
                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                        </span>
                        @if($isCorrect)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <flux:icon name="check-circle" class="w-3 h-3" />
                                Correct
                            </span>
                        @elseif($isUnanswered)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                <flux:icon name="minus-circle" class="w-3 h-3" />
                                Unanswered
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <flux:icon name="x-circle" class="w-3 h-3" />
                                Incorrect
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Question Text --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">
                        {!! nl2br(e($question->question_text)) !!}
                    </h3>
                </div>

                {{-- Answer Display --}}
                <div class="space-y-4">
                    @if($question->type === 'multiple_choice')
                        {{-- Multiple Choice Display --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(array_values($question->options ?? []) as $optIndex => $option)                                @php
                                    $optionText = is_array($question->options) ? $option : ($question->options[$optIndex] ?? '');
                                    $isUserAnswer = $userAnswer === $optionText;
                                    $isCorrectAnswer = $question->correct_answer === $optionText;
                                @endphp
                                <div class="p-4 rounded-lg border-2
                                    @if($isCorrectAnswer)
                                        border-green-500 bg-green-50 dark:bg-green-900/20
                                    @elseif($isUserAnswer && !$isCorrectAnswer)
                                        border-red-500 bg-red-50 dark:bg-red-900/20
                                    @else
                                        border-gray-200 dark:border-gray-700
                                    @endif">
                                    <div class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                            @if($isCorrectAnswer)
                                                bg-green-500 text-white
                                            @elseif($isUserAnswer && !$isCorrectAnswer)
                                                bg-red-500 text-white
                                            @else
                                                bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                            @endif">
                                            {{ chr(65 + $optIndex) }}
                                        </span>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $optionText }}</p>
                                            @if($isCorrectAnswer)
                                                <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">✓ Correct Answer</p>
                                            @elseif($isUserAnswer && !$isCorrectAnswer)
                                                <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">✗ Your Answer</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($question->type === 'true_false')
                        {{-- True/False Display --}}
                        <div class="grid grid-cols-2 gap-4 max-w-md">
                            @foreach(['true', 'false'] as $option)
                                @php
                                    $isUserAnswer = $userAnswer === $option;
                                    $isCorrectAnswer = $question->correct_answer === $option;
                                @endphp
                                <div class="p-4 rounded-lg border-2
                                    @if($isCorrectAnswer)
                                        border-green-500 bg-green-50 dark:bg-green-900/20
                                    @elseif($isUserAnswer && !$isCorrectAnswer)
                                        border-red-500 bg-red-50 dark:bg-red-900/20
                                    @else
                                        border-gray-200 dark:border-gray-700
                                    @endif">
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ ucfirst($option) }}
                                        </span>
                                        @if($isCorrectAnswer)
                                            <flux:icon name="check-circle" class="w-5 h-5 text-green-500" />
                                        @elseif($isUserAnswer && !$isCorrectAnswer)
                                            <flux:icon name="x-circle" class="w-5 h-5 text-red-500" />
                                        @endif
                                    </div>
                                    @if($isCorrectAnswer)
                                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">✓ Correct Answer</p>
                                    @elseif($isUserAnswer && !$isCorrectAnswer)
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">✗ Your Answer</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @elseif($question->type === 'short_answer')
                        {{-- Short Answer Display --}}
                        <div class="space-y-3">
                            <div class="p-4 rounded-lg border-2
                                @if($isCorrect)
                                    border-green-500 bg-green-50 dark:bg-green-900/20
                                @else
                                    border-red-500 bg-red-50 dark:bg-red-900/20
                                @endif">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Your Answer:</p>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $userAnswer ?? '<em>No answer provided</em>' }}
                                </p>
                                @if(!$isCorrect)
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-2 font-medium">✗ Incorrect</p>
                                @endif
                            </div>
                            <div class="p-4 rounded-lg border-2 border-green-500 bg-green-50 dark:bg-green-900/20">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Correct Answer:</p>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    {{ $question->correct_answer }}
                                </p>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-2 font-medium">✓ Correct Answer</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Bottom Actions --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <flux:button href="{{ route('dashboard') }}" variant="ghost" icon="home">
                Back to Dashboard
            </flux:button>
            <div class="flex gap-3">
                <flux:button href="{{ route('exams.index') }}" variant="ghost">
                    Browse Exams
                </flux:button>
                <flux:button href="{{ route('exams.take', $exam) }}" variant="primary" icon="arrow-path">
                    Retake Exam
                </flux:button>
            </div>
        </div>
    </div>
</div>
