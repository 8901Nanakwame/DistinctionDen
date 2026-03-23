<div>
    {{--
        STATE 1: EXAM OVERVIEW (NOT STARTED)
        -----------------------------------------------------------------------
        This section is displayed when the user first visits the exam page.
        It serves as a "Gatekeeper" that shows vital exam statistics (duration,
        question count, category) before the user commits to starting.

        Logic:
        - Triggers 'saveProgress' which initializes the ExamAttempt in the DB.
        - Sets 'examStarted' to true, transitioning the UI to the In-Progress state.
    --}}
    @if(!$examStarted && !$examCompleted)
        <div class="max-w-3xl mx-auto">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8">
                <div class="text-center mb-8">
                    <div class="mx-auto h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mb-4">
                        <flux:icon name="academic-cap" class="h-8 w-8 text-primary-800 dark:text-secondary-300" />
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $exam->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $exam->description }}</p>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->questions->count() }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Questions</p>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ floor($exam->duration / 60) }}h {{ $exam->duration % 60 }}m</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Duration</p>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ ucfirst($exam->category->name ?? 'General') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                    </div>
                </div>

                <flux:button wire:click="saveProgress" variant="primary" class="w-full">
                    Start Exam
                </flux:button>
            </div>
        </div>
    @endif

    {{--
        STATE 2: EXAM IN PROGRESS (PAGINATED)
        -----------------------------------------------------------------------
        The active testing interface. Displays 10 questions per page.
        Uses nextPage/previousPage for navigation.
    --}}
    @if($examStarted && !$submitted && !$examCompleted)
        <div class="max-w-5xl mx-auto space-y-6">

            {{--
                TOP BAR: STATUS & TIMER
            --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 sticky top-0 z-10 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $exam->title }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Page {{ $currentPage + 1 }} of {{ $this->totalPages }}
                            ({{ min(($currentPage * $perPage) + 1, $this->questions->count()) }}-{{ min(($currentPage + 1) * $perPage, $this->questions->count()) }} of {{ $this->questions->count() }})
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-50 dark:bg-primary-900/20">
                            <flux:icon name="clock" class="h-5 w-5 text-primary-800 dark:text-secondary-300" />
                            <span class="text-lg font-mono font-bold text-primary-800 dark:text-secondary-300"
                                  wire:poll.1s="decrementTimer">
                                {{ gmdate('H:i:s', $timeRemaining) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- PROGRESS BAR --}}
                <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div
                        class="h-full bg-gradient-to-r from-primary-800 to-secondary-500 transition-all duration-300"
                        style="width: {{ $this->progressPercentage }}%"
                    ></div>
                </div>
            </div>

            {{--
                QUESTIONS LIST
                Iterates through the current page's questions.
            --}}
            <div class="space-y-6">
                @foreach($this->currentQuestions as $index => $question)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 shadow-sm">
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-900 dark:bg-primary-900/30 dark:text-secondary-300">
                                    Question {{ ($currentPage * $perPage) + $loop->iteration }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white leading-relaxed">
                                {{ $question->question_text }}
                            </h3>
                        </div>

                        {{-- DYNAMIC ANSWER INPUTS --}}
                        <div class="space-y-3">

                            {{-- TYPE: MULTIPLE CHOICE --}}
                            @if($question->type === 'multiple_choice')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach(array_values($question->options ?? []) as $optIndex => $option)                                        @php
                                            $optionText = is_array($question->options) ? $option : ($question->options[$optIndex] ?? '');
                                        @endphp
                                        <button
                                            wire:click="selectAnswer({{ $question->id }}, '{{ $optionText }}')"
                                            class="text-left p-4 rounded-lg border-2 transition-all group
                                                @if(($answers[$question->id] ?? null) === $optionText)
                                                    border-primary-700 bg-primary-50 dark:bg-primary-900/20
                                                @else
                                                    border-border dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700
                                                @endif"
                                        >
                                            <div class="flex items-center gap-3">
                                                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                                    @if(($answers[$question->id] ?? null) === $optionText)
                                                        bg-primary-800 text-white
                                                    @else
                                                        bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/40
                                                    @endif">
                                                   {{ chr(65 +  $optIndex) }}
                                                </span>
                                                <span class="text-gray-900 dark:text-white">{{ $optionText }}</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            {{-- TYPE: TRUE / FALSE --}}
                            @if($question->type === 'true_false')
                                <div class="grid grid-cols-2 gap-4 max-w-md">
                                    <button
                                        wire:click="selectAnswer({{ $question->id }}, 'true')"
                                        class="p-4 rounded-lg border-2 transition-all
                                            @if(($answers[$question->id] ?? null) === 'true')
                                                border-green-500 bg-green-50 dark:bg-green-900/20
                                            @else
                                                border-gray-100 dark:border-gray-700 hover:border-green-300 dark:hover:border-green-700
                                            @endif"
                                    >
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">True</span>
                                    </button>
                                    <button
                                        wire:click="selectAnswer({{ $question->id }}, 'false')"
                                        class="p-4 rounded-lg border-2 transition-all
                                            @if(($answers[$question->id] ?? null) === 'false')
                                                border-red-500 bg-red-50 dark:bg-red-900/20
                                            @else
                                                border-gray-100 dark:border-gray-700 hover:border-red-300 dark:hover:border-red-700
                                            @endif"
                                    >
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">False</span>
                                    </button>
                                </div>
                            @endif

                            {{-- TYPE: SHORT ANSWER --}}
                            @if($question->type === 'short_answer')
                                <flux:input
                                    wire:model.blur="answers.{{ $question->id }}"
                                    wire:change="saveProgress"
                                    placeholder="Type your answer here..."
                                    class="w-full"
                                />
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{--
                NAVIGATION & SUBMISSION CONTROLS
            --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <flux:button
                        wire:click="previousPage"
                        variant="primary"
                        icon="arrow-left"
                        :disabled="$currentPage === 0"
                    >
                        Previous Page
                    </flux:button>

                    {{-- PAGE INDICATOR --}}
                    <div class="hidden md:flex gap-2">
                        @for($p = 0; $p < $this->totalPages; $p++)
                            <button
                                wire:click="goToPage({{ $p }})"
                                class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium transition-all
                                    @if($p === $currentPage)
                                        bg-primary-800 text-white ring-2 ring-secondary-300 dark:ring-primary-800
                                    @else
                                        bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                    @endif"
                            >
                                {{ $p + 1 }}
                            </button>
                        @endfor
                    </div>

                    @if($currentPage < $this->totalPages - 1)
                        <flux:button
                            wire:click="nextPage"
                            variant="primary"
                            icon="arrow-right"
                            icon-trailing
                        >
                            Next Page
                        </flux:button>
                    @else
                        <flux:button
                            wire:click="openSubmitModal"
                            variant="primary"
                            icon="check"
                        >
                            Submit Exam
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>

        {{-- CONFIRMATION MODAL --}}
        <flux:modal wire:model="showConfirmSubmit" class="max-w-md">
            <div class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Submit Exam?</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        You've answered {{ $this->answeredCount }} out of {{ $this->questions->count() }} questions.
                        Are you sure you want to finalize your submission?
                    </p>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <flux:button wire:click="cancelSubmit" variant="ghost">Continue Exam</flux:button>
                    <flux:button wire:click="submitExam" variant="primary">Submit Now</flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    {{--
        STATE 3: EXAM RESULTS
    --}}
    @if($submitted || $examCompleted)
        <div class="max-w-3xl mx-auto">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 shadow-sm">
                <div class="text-center mb-8">
                    <div class="mx-auto h-20 w-20 rounded-full
                        @if($score >= 80) bg-green-100 dark:bg-green-900/30
                        @elseif($score >= 60) bg-yellow-100 dark:bg-yellow-900/30
                        @else bg-red-100 dark:bg-red-900/30
                        @endif flex items-center justify-center mb-4">
                        @if($score >= 80)
                            <flux:icon name="check-circle" class="h-10 w-10 text-green-600 dark:text-green-400" />
                        @elseif($score >= 60)
                            <flux:icon name="check" class="h-10 w-10 text-yellow-600 dark:text-yellow-400" />
                        @else
                            <flux:icon name="x-circle" class="h-10 w-10 text-red-600 dark:text-red-400" />
                        @endif
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Exam Completed!</h1>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">{{ $exam->title }}</p>
                </div>

                {{-- RESULTS SUMMARY GRID --}}
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-6 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <p class="text-4xl font-bold
                            @if($score >= 80) text-green-600 dark:text-green-400
                            @elseif($score >= 60) text-yellow-600 dark:text-yellow-400
                            @else text-red-600 dark:text-red-400
                            @endif">{{ $score }}%</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-wider">Your Score</p>
                    </div>
                    <div class="text-center p-6 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $this->questions->count() }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-wider">Total</p>
                    </div>
                    <div class="text-center p-6 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $this->answeredCount }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-wider">Answered</p>
                    </div>
                </div>

                {{-- CORRECTION REVIEW BUTTON --}}
                <div class="mb-6 p-4 rounded-lg bg-primary-50 dark:bg-primary-900/20 border border-border dark:border-primary-800/60">
                    <div class="flex items-center gap-3">
                        <flux:icon name="magnifying-glass-circle" class="h-6 w-6 text-primary-800 dark:text-secondary-300" />
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-primary-900 dark:text-secondary-200">Review Your Answers</p>
                            <p class="text-xs text-primary-700 dark:text-secondary-300">See which questions you got right or wrong with the correct answers</p>
                        </div>
                        <flux:button href="{{ route('exams.correction', ['exam' => $exam, 'attempt' => $attempt]) }}" variant="primary" icon="eye">
                            View Correction
                        </flux:button>
                    </div>
                </div>

                <div class="flex gap-4">
                    <flux:button href="{{ route('dashboard') }}" variant="primary" class="flex-1">
                        Back to Dashboard
                    </flux:button>
                    <flux:button href="{{ route('exams.index') }}" variant="primary" class="flex-1">
                        Take Another Exam
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
