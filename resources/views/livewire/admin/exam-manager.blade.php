<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Exam Management</flux:heading>
            <flux:subheading>Create exams, assign categories, and manage availability</flux:subheading>
        </div>
        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
            Add Exam
        </flux:button>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <flux:input
            wire:model.live.debounce.300ms="searchTerm"
            placeholder="Search exams..."
            icon="magnifying-glass"
        />

        <flux:select wire:model.live="filterCategory" placeholder="All Categories">
            <flux:select.option value="">All Categories</flux:select.option>
            @foreach($this->categories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="filterStatus" placeholder="All Statuses">
            <flux:select.option value="">All Statuses</flux:select.option>
            <flux:select.option value="active">Active</flux:select.option>
            <flux:select.option value="inactive">Inactive</flux:select.option>
        </flux:select>
    </div>

    {{-- Exams List --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Questions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attempts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->exams as $exam)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $exam->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $exam->slug }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $exam->category?->name ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ filled($exam->duration) ? $exam->duration.' min' : '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $exam->questions_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $exam->attempts_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($exam->is_active) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                    @endif">
                                    {{ $exam->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="openEditModal({{ $exam->id }})" variant="ghost" size="sm" icon="pencil" />
                                    <flux:button wire:click="openDeleteModal({{ $exam->id }})" variant="ghost" size="sm" icon="trash" class="text-red-600" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <flux:icon name="academic-cap" class="mx-auto h-12 w-12 text-gray-400" />
                                <p class="mt-4 text-gray-500">No exams found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($this->exams->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->exams->links() }}
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    <flux:modal wire:model="showCreateModal" class="max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Create Exam</flux:heading>
                <flux:subheading>Add an exam and assign it to an exam category</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="title" label="Title" placeholder="e.g. JAMB Mathematics (2026)" required />
                <flux:error name="title" />

                <flux:textarea wire:model="description" label="Description" rows="4" placeholder="Short description (optional)" />
                <flux:error name="description" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="duration" type="number" min="1" label="Duration (minutes)" placeholder="e.g. 60" />

                    <flux:select wire:model="categoryId" label="Category" required>
                        <flux:select.option value="">Select Category</flux:select.option>
                        @foreach($this->categories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:label>Cover Image (Optional)</flux:label>
                    <input type="file" wire:model="image" class="mt-2 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-violet-50 file:text-violet-700
                        hover:file:bg-violet-100
                    "/>
                    <flux:error name="image" />
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover rounded-lg">
                    @endif
                </div>

                <flux:checkbox wire:model="isActive" label="Active (visible to users)" />
                <flux:error name="isActive" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <flux:button wire:click="$set('showCreateModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="createExam" variant="primary">Create Exam</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Edit Modal --}}
    <flux:modal wire:model="showEditModal" class="max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Edit Exam</flux:heading>
                <flux:subheading>Update exam details and category</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="title" label="Title" required />
                <flux:error name="title" />

                <flux:textarea wire:model="description" label="Description" rows="4" />
                <flux:error name="description" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="duration" type="number" min="1" label="Duration (minutes)" />

                    <flux:select wire:model="categoryId" label="Category" required>
                        @foreach($this->categories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:label>Cover Image (Optional)</flux:label>
                    <input type="file" wire:model="image" class="mt-2 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-violet-50 file:text-violet-700
                        hover:file:bg-violet-100
                    "/>
                    <flux:error name="image" />
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover rounded-lg">
                    @elseif($existingImage)
                        <img src="{{ asset('storage/' . $existingImage) }}" class="mt-2 w-32 h-32 object-cover rounded-lg">
                    @endif
                </div>

                <flux:checkbox wire:model="isActive" label="Active (visible to users)" />
                <flux:error name="isActive" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <flux:button wire:click="$set('showEditModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="updateExam" variant="primary">Update Exam</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-md">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Delete Exam</flux:heading>
                <flux:subheading>
                    This will also delete all related questions and attempts.
                </flux:subheading>
            </div>

            <div class="rounded-lg border border-red-200 dark:border-red-900/40 bg-red-50 dark:bg-red-900/10 p-4 text-sm text-red-700 dark:text-red-300">
                You are about to delete <span class="font-semibold">{{ $deletingExam?->title }}</span>.
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="deleteExam" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

