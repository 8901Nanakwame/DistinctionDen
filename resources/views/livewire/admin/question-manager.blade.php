<div class="max-w-7xl mx-auto space-y-6">
    {{-- Trix Editor with Header and Font Size Options --}}
    <link rel="stylesheet" href="https://unpkg.com/trix/dist/trix.css" data-navigate-once>
    <script src="https://unpkg.com/trix/dist/trix.umd.min.js" data-navigate-once></script>
    <style data-navigate-once>
        trix-editor {
            min-height: 200px;
            background: white !important;
            color: #1f2937;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
        }
        trix-toolbar {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        trix-toolbar .trix-button-group {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        trix-toolbar .trix-button {
            background: white;
            color: #374151;
        }
        trix-toolbar .trix-button:hover {
            background: #f3f4f6;
        }
        /* Custom font size buttons */
        .trix-button--icon-font-size-increase::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' /%3E%3C/svg%3E");
        }
        .trix-button--icon-font-size-decrease::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' /%3E%3C/svg%3E");
        }
    </style>
    <script data-navigate-once>
        document.addEventListener('trix-before-initialize', () => {
            Trix.config.toolbar.getDefaultHTML = () => {
                return `
                    <div class="trix-button-row">
                        <span class="trix-button-group trix-button-group--text-tools" data-trix-button-group="text-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-bold" data-trix-attribute="bold" data-trix-key="b" title="Bold" tabindex="-1">Bold</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-italic" data-trix-attribute="italic" data-trix-key="i" title="Italic" tabindex="-1">Italic</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-strike" data-trix-attribute="strike" title="Strike" tabindex="-1">Strike</button>
                        </span>

                        <span class="trix-button-group trix-button-group--heading-tools" data-trix-button-group="heading-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-heading-1" data-trix-attribute="heading1" title="Heading 1" tabindex="-1">H1</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-heading-2" data-trix-attribute="heading2" title="Heading 2" tabindex="-1">H2</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-quote" data-trix-attribute="quote" title="Quote" tabindex="-1">Quote</button>
                        </span>

                        <span class="trix-button-group trix-button-group--font-size-tools" data-trix-button-group="font-size-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-font-size-increase" data-trix-attribute="increaseFontSize" title="Increase Font Size" tabindex="-1">A+</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-font-size-decrease" data-trix-attribute="decreaseFontSize" title="Decrease Font Size" tabindex="-1">A-</button>
                        </span>

                        <span class="trix-button-group trix-button-group--list-tools" data-trix-button-group="list-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-bullet-list" data-trix-attribute="bullet" title="Bullet List" tabindex="-1">Bullet List</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-number-list" data-trix-attribute="number" title="Numbered List" tabindex="-1">Numbered List</button>
                        </span>

                        <span class="trix-button-group trix-button-group--link-tools" data-trix-button-group="link-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-link" data-trix-attribute="href" data-trix-action="link" data-trix-key="k" title="Link" tabindex="-1">Link</button>
                        </span>
                    </div>
                `;
            };
        });
    </script>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Question Management</flux:heading>
            <flux:subheading>Define questions with one correct answer and multiple wrong options</flux:subheading>
        </div>
        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
            Add Question
        </flux:button>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
        <flux:input
            wire:model.live.debounce.300ms="searchTerm"
            placeholder="Search questions..."
            icon="magnifying-glass"
        />

        <flux:select wire:model.live="filterCategory" placeholder="All Categories">
            <flux:select.option value="">All Categories</flux:select.option>
            @foreach($this->examCategories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="filterExam" placeholder="All Exams">
            <flux:select.option value="">All Exams</flux:select.option>
            @foreach($this->filteredExams as $exam)
                <flux:select.option value="{{ $exam->id }}">{{ $exam->title }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="filterType" placeholder="All Types">
            <flux:select.option value="">All Types</flux:select.option>
            @foreach($this->questionTypes as $type)
                <flux:select.option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    {{-- Questions List --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Exam</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->questions as $question)
                        <tr wire:key="question-{{ $question->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="max-w-xl">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2">
                                        {{ $question->question_text }}
                                    </p>
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-semibold">Correct: {{ Str::limit($question->correct_answer, 50) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $question->exam->title ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="openEditModal({{ $question->id }})" variant="ghost" size="sm" icon="pencil" />
                                    <flux:button wire:click="openDeleteModal({{ $question->id }})" variant="ghost" size="sm" icon="trash" class="text-red-600" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <flux:icon name="question-mark-circle" class="mx-auto h-12 w-12 text-gray-400" />
                                <p class="mt-4 text-gray-500">No questions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($this->questions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->questions->links() }}
            </div>
        @endif
    </div>


    {{-- Create Modal --}}
    <flux:modal wire:model="showCreateModal" class="max-w-4xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Create New Question</flux:heading>
                <flux:subheading>Input the correct answer and separate wrong options</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:select wire:model.live="examCategoryId" label="Exam Category">
                        <flux:select.option value="">Select Category</flux:select.option>
                        @foreach($this->examCategories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="examId" label="Exam" required>
                        <flux:select.option value="">Select Exam</flux:select.option>
                        @foreach($this->formExams as $exam)
                            <flux:select.option value="{{ $exam->id }}">{{ $exam->title }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="examId" />

                    <flux:select wire:model.live="type" label="Question Type" required>
                        <flux:select.option value="multiple_choice">Multiple Choice</flux:select.option>
                        <flux:select.option value="true_false">True/False</flux:select.option>
                        <flux:select.option value="short_answer">Short Answer</flux:select.option>
                    </flux:select>
                    <flux:error name="type" />

                    <flux:textarea
                        wire:model="questionText"
                        label="Question Text"
                        placeholder="Enter the question..."
                        rows="5"
                        required
                    />
                    <flux:error name="questionText" />
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:input wire:model="correctAnswer" label="Correct Answer" placeholder="The actual correct answer" required />
                        <flux:error name="correctAnswer" />
                    </div>

{{--                    @if($type === 'multiple_choice')--}}
                        <div class="flex items-center justify-between">
                            <flux:label>Wrong Answers</flux:label>
                            <flux:button wire:click="addWrongAnswer" variant="ghost" size="sm" icon="plus">Add Wrong Option</flux:button>
                        </div>
                        <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                            @foreach($wrongAnswers as $index => $wrong)
                                <div wire:key="wrong-answer-{{ $index }}" class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <flux:input wire:model="wrongAnswers.{{ $index }}" placeholder="Wrong Option {{ $index + 1 }}" class="flex-1" />
                                        <flux:button wire:click="removeWrongAnswer({{ $index }})" variant="ghost" size="sm" icon="trash" class="text-red-500" />
                                    </div>
                                    <flux:error name="wrongAnswers.{{ $index }}" />
                                </div>
                            @endforeach
                            <flux:error name="wrongAnswers" />
                        </div>
{{--                    @endif--}}
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <flux:button wire:click="cancelCreate" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="createQuestion" variant="primary">Create Question</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Edit Modal --}}
    <flux:modal wire:model="showEditModal" class="max-w-4xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Edit Question</flux:heading>
                <flux:subheading>Update question properties</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:select wire:model.live="examCategoryId" label="Exam Category">
                        <flux:select.option value="">Select Category</flux:select.option>
                        @foreach($this->examCategories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="examId" label="Exam" required>
                        @foreach($this->formExams as $exam)
                            <flux:select.option value="{{ $exam->id }}">{{ $exam->title }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="type" label="Question Type" required>
                        <flux:select.option value="multiple_choice">Multiple Choice</flux:select.option>
                        <flux:select.option value="true_false">True/False</flux:select.option>
                        <flux:select.option value="short_answer">Short Answer</flux:select.option>
                    </flux:select>

                    <flux:textarea wire:model="questionText" label="Question Text" rows="5" required />
                </div>

                <div class="space-y-4">
                    <flux:input wire:model="correctAnswer" label="Correct Answer" required />

                    @if($type === 'multiple_choice')
                        <div class="flex items-center justify-between">
                            <flux:label>Wrong Answers</flux:label>
                            <flux:button wire:click="addWrongAnswer" variant="ghost" size="sm" icon="plus">Add Wrong Option</flux:button>
                        </div>
                        <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                            @foreach($wrongAnswers as $index => $wrong)
                                <div wire:key="edit-wrong-answer-{{ $index }}" class="flex items-center gap-2">
                                    <flux:input wire:model="wrongAnswers.{{ $index }}" placeholder="Wrong Option {{ $index + 1 }}" class="flex-1" />
                                    <flux:button wire:click="removeWrongAnswer({{ $index }})" variant="ghost" size="sm" icon="trash" class="text-red-500" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <flux:button wire:click="cancelEdit" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="updateQuestion" variant="primary">Update Question</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-md">
        <div class="space-y-4">
            <div>
                <flux:heading size="sm">Delete Question</flux:heading>
                <flux:subheading>This action cannot be undone.</flux:subheading>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="deleteQuestion" variant="danger">Delete Permanently</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
