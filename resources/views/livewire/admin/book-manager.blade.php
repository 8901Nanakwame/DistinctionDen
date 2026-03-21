<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Book Inventory</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your e-commerce book catalog</p>
        </div>
        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
            Upload New Book
        </flux:button>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <flux:input
            wire:model.live.debounce.300ms="searchTerm"
            placeholder="Search books by title or author..."
            icon="magnifying-glass"
        />
    </div>

    {{-- Books List --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Book</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->books as $book)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($book->image)
                                        <img src="{{ asset('storage/' . $book->image) }}" class="w-10 h-14 object-cover rounded shadow-sm">
                                    @else
                                        <div class="w-10 h-14 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                                            <flux:icon name="book-open" class="w-5 h-5 text-gray-400" />
                                        </div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $book->author }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">GH₵ {{ number_format($book->price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm @if($book->stock < 5) text-red-600 font-bold @else text-gray-600 dark:text-gray-400 @endif">
                                    {{ $book->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $book->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="openEditModal({{ $book->id }})" variant="ghost" size="sm" icon="pencil" />
                                    <flux:button wire:click="openDeleteModal({{ $book->id }})" variant="ghost" size="sm" icon="trash" class="text-red-600" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <flux:icon name="book-open" class="mx-auto h-12 w-12 text-gray-400" />
                                <p class="mt-4 text-gray-500">No books found in inventory</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($this->books->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->books->links() }}
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    <flux:modal wire:model="showCreateModal" class="max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Upload New Book</flux:heading>
                <flux:subheading>Add a new book to the e-commerce store</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="title" label="Title" placeholder="Book title" required />
                <flux:input wire:model="author" label="Author" placeholder="Author name" required />
                <div class="md:col-span-2">
                    <flux:textarea wire:model="description" label="Description" placeholder="Book summary..." rows="4" />
                </div>
                <flux:input wire:model="price" type="number" step="0.01" label="Price (GH₵)" required />
                <flux:input wire:model="stock" type="number" label="Stock Quantity" required />
                <flux:select wire:model="categoryId" label="Category">
                    <flux:select.option value="">Select Category</flux:select.option>
                    @foreach($this->categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="image" type="file" label="Book Cover" />
            </div>

            @if ($image)
                <div class="mt-4">
                    <p class="text-xs text-gray-500 mb-2">Image Preview:</p>
                    <img src="{{ $image->temporaryUrl() }}" class="w-32 h-44 object-cover rounded shadow">
                </div>
            @endif

            <div class="flex justify-end gap-3">
                <flux:button wire:click="$set('showCreateModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="createBook" variant="primary">Upload Book</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Edit Modal --}}
    <flux:modal wire:model="showEditModal" class="max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Edit Book</flux:heading>
                <flux:subheading>Update book information</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="title" label="Title" required />
                <flux:input wire:model="author" label="Author" required />
                <div class="md:col-span-2">
                    <flux:textarea wire:model="description" label="Description" rows="4" />
                </div>
                <flux:input wire:model="price" type="number" step="0.01" label="Price (GH₵)" required />
                <flux:input wire:model="stock" type="number" label="Stock Quantity" required />
                <flux:select wire:model="categoryId" label="Category">
                    <flux:select.option value="">Select Category</flux:select.option>
                    @foreach($this->categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="image" type="file" label="Change Book Cover" />
            </div>

            <div class="flex justify-end gap-3">
                <flux:button wire:click="$set('showEditModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="updateBook" variant="primary">Update Details</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-sm">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Delete Book</flux:heading>
                <flux:subheading>Are you sure you want to remove this book from the catalog?</flux:subheading>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="deleteBook" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
