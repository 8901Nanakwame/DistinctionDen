<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Category Management</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage subjects and categories for exams, books, and posts</p>
        </div>
        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
            Add Category
        </flux:button>
    </div>

    {{-- Info Session --}}
    <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-900/40 dark:bg-blue-900/10">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <flux:icon name="information-circle" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="space-y-3">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">About Categories</h3>
                <p class="text-sm text-blue-800 dark:text-blue-200 leading-relaxed">
                    Categories help organize your content across the platform. For exams, these typically represent educational levels or certification bodies.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                    <div class="bg-white/50 dark:bg-black/20 p-3 rounded-lg border border-blue-100 dark:border-blue-800">
                        <span class="font-bold text-blue-900 dark:text-blue-300 block mb-1">BECE</span>
                        <p class="text-xs text-blue-700 dark:text-blue-400">Basic Education Certificate Examination for JHS students.</p>
                    </div>
                    <div class="bg-white/50 dark:bg-black/20 p-3 rounded-lg border border-blue-100 dark:border-blue-800">
                        <span class="font-bold text-blue-900 dark:text-blue-300 block mb-1">WASSCE</span>
                        <p class="text-xs text-blue-700 dark:text-blue-400">West African Senior School Certificate Examination for SHS students.</p>
                    </div>
                    <div class="bg-white/50 dark:bg-black/20 p-3 rounded-lg border border-blue-100 dark:border-blue-800">
                        <span class="font-bold text-blue-900 dark:text-blue-300 block mb-1">NTC</span>
                        <p class="text-xs text-blue-700 dark:text-blue-400">National Teaching Council certification for teachers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <flux:input
            wire:model.live.debounce.300ms="searchTerm"
            placeholder="Search categories..."
            icon="magnifying-glass"
        />
    </div>

    {{-- Categories List --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $category->slug }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($category->type === 'exam') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($category->type === 'book') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @endif">
                                    {{ ucfirst($category->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="openEditModal({{ $category->id }})" variant="ghost" size="sm" icon="pencil">
                                        Edit
                                    </flux:button>
                                    <flux:button wire:click="openDeleteModal({{ $category->id }})" variant="ghost" size="sm" icon="trash" class="text-red-600">
                                        Delete
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <flux:icon name="tag" class="mx-auto h-12 w-12 text-gray-400" />
                                <p class="mt-4 text-gray-500">No categories found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($this->categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->categories->links() }}
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    <flux:modal wire:model="showCreateModal" class="max-w-md">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Create Category</flux:heading>
                <flux:subheading>Add a new subject or category</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="name" label="Name" placeholder="e.g. Mathematics, English" required />
                <flux:select wire:model="type" label="Type">
                    <flux:select.option value="exam">Exam</flux:select.option>
                    <flux:select.option value="book">Book</flux:select.option>
                    <flux:select.option value="blog">Blog</flux:select.option>
                </flux:select>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button wire:click="$set('showCreateModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="createCategory" variant="primary">Create</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Edit Modal --}}
    <flux:modal wire:model="showEditModal" class="max-w-md">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Edit Category</flux:heading>
                <flux:subheading>Update category details</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="name" label="Name" required />
                <flux:select wire:model="type" label="Type">
                    <flux:select.option value="exam">Exam</flux:select.option>
                    <flux:select.option value="book">Book</flux:select.option>
                    <flux:select.option value="blog">Blog</flux:select.option>
                </flux:select>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button wire:click="$set('showEditModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="updateCategory" variant="primary">Update</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-sm">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Delete Category</flux:heading>
                <flux:subheading>Are you sure you want to delete this category? This might affect related items.</flux:subheading>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="deleteCategory" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
