<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Browse Exams</h1>
            <p class="text-gray-600 dark:text-gray-400">Choose from our collection of practice exams</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
        <div class="sm:col-span-2">
            <flux:input
                wire:model.live.debounce.300ms="searchTerm"
                placeholder="Search exams..."
                icon="magnifying-glass"
            />
        </div>

        <flux:select wire:model.live="filterCategory" placeholder="All Categories">
            <flux:select.option value="">All Categories</flux:select.option>
            @foreach($this->categories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="sortBy" placeholder="Sort By">
            <flux:select.option value="latest">Latest</flux:select.option>
            <flux:select.option value="popular">Most Popular</flux:select.option>
        </flux:select>
    </div>

    {{-- Exams Grid --}}
    @if($this->exams->isEmpty())
        <div class="text-center py-16">
            <flux:icon name="academic-cap" class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" />
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No exams found</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Try adjusting your search or filters</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($this->exams as $exam)
                <div class="group relative rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden hover:shadow-lg transition-all duration-300">
                    {{-- Card Header with Gradient --}}
                    <div class="h-32 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                                {{ $exam->category->name ?? 'General' }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $exam->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ $exam->description ?? 'No description available' }}
                        </p>

                        {{-- Stats --}}
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <div class="flex items-center gap-1">
                                <flux:icon name="clock" class="h-4 w-4" />
                                <span>{{ floor($exam->duration / 60) }}h {{ $exam->duration % 60 }}m</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <flux:icon name="document-text" class="h-4 w-4" />
                                <span>{{ $exam->questions->count() }} questions</span>
                            </div>
                        </div>

                        {{-- Attempt Info --}}
                        @if($exam->attempts->isNotEmpty())
                            <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-green-700 dark:text-green-400">Completed</span>
                                    <span class="text-sm font-bold text-green-700 dark:text-green-400">
                                        {{ $exam->attempts->first()->score }}%
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- Action Button --}}
                        <flux:button
                            href="{{ route('exams.take', $exam) }}"
                            variant="primary"
                            class="w-full"
                            icon="{{ $exam->attempts->isNotEmpty() ? 'arrow-path' : 'play' }}"
                        >
                            {{ $exam->attempts->isNotEmpty() ? 'Retake Exam' : 'Start Exam' }}
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($this->exams->hasPages())
            <div class="mt-8">
                {{ $this->exams->links() }}
            </div>
        @endif
    @endif
</div>
