<div class="max-w-7xl mx-auto space-y-6">
    {{-- Trix (free WYSIWYG) with Header and Expandable Support --}}
    <link rel="stylesheet" href="https://unpkg.com/trix/dist/trix.css" data-navigate-once>
    <script src="https://unpkg.com/trix/dist/trix.umd.min.js" data-navigate-once></script>
    <style data-navigate-once>
        trix-editor {
            min-height: 350px;
            max-height: none;
            height: auto;
            background: white !important;
            color: #000000 !important;
            border: none;
            padding: 1rem;
            overflow-y: auto;
        }
        trix-toolbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
            border-radius: 0.5rem 0.5rem 0 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        trix-toolbar .trix-button-group {
            border-radius: 0.375rem;
            overflow: hidden;
            margin: 0.25rem;
        }
        trix-toolbar .trix-button {
            background: white !important;
            color: #000000 !important;
            border: 1px solid #e5e7eb !important;
        }
        trix-toolbar .trix-button:hover {
            background: #f3f4f6 !important;
        }
        trix-toolbar .trix-button::before {
            /*filter: brightness(0);*/
        }


        trix-toolbar .trix-button::before {
            filter: invert(100%) brightness(0);   /* Very reliable way to force black */
            /* Alternative if invert causes issues: filter: brightness(0) contrast(1000%); */
        }
        /* Heading buttons styling */
        .trix-button--icon-heading-1::before {
            content: 'H1';
            font-weight: bold;
            font-size: 16px;
            background-image: none !important;
            color: #000000 !important;
        }
        .trix-button--icon-heading-2::before {
            content: 'H2';
            font-weight: bold;
            font-size: 14px;
            background-image: none !important;
            color: #000000 !important;
        }
        .trix-button--icon-heading-3::before {
            content: 'H3';
            font-weight: bold;
            font-size: 12px;
            background-image: none !important;
            color: #000000 !important;
        }
        .trix-button--icon-quote::before {
            content: '"';
            font-weight: bold;
            font-size: 18px;
            background-image: none !important;
            color: #000000 !important;
        }
        /* Font size buttons */
        .trix-button--icon-font-size-increase::before {
            content: 'A+';
            font-weight: bold;
            font-size: 14px;
            background-image: none !important;
            color: #000000 !important;
        }
        .trix-button--icon-font-size-decrease::before {
            content: 'A-';
            font-weight: bold;
            font-size: 14px;
            background-image: none !important;
            color: #000000 !important;
        }
        /* Auto-expand trix container */
        trix-editor[contenteditable]:empty:before {
            content: attr(placeholder);
            color: #9ca3af;
        }
    </style>
    <script data-navigate-once>
        document.addEventListener('trix-before-initialize', () => {
            Trix.config.toolbar.getDefaultHTML = () => {
                return `
                    <div class="trix-button-row">
                        <span class="trix-button-group trix-button-group--text-tools" data-trix-button-group="text-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-bold" data-trix-attribute="bold" data-trix-key="b" title="Bold (Ctrl+B)" tabindex="-1">B</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-italic" data-trix-attribute="italic" data-trix-key="i" title="Italic (Ctrl+I)" tabindex="-1">I</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-strike" data-trix-attribute="strike" title="Strike" tabindex="-1">S</button>
                        </span>

                        <span class="trix-button-group trix-button-group--heading-tools" data-trix-button-group="heading-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-heading-2" data-trix-attribute="heading2" title="Heading 2" tabindex="-1">H2</button>
                            <button type="button" class="trix-button text-black  trix-button--icon trix-button--icon-heading-1" data-trix-attribute="heading1" title="Heading 1" tabindex="-1">H1</button>
                            <button type="button" class="trix-button text-black  trix-button--icon trix-button--icon-heading-3" data-trix-attribute="heading3" title="Heading 3" tabindex="-1">H3</button>
                            <button type="button" class="trix-button text-black  trix-button--icon trix-button--icon-quote" data-trix-attribute="quote" title="Quote" tabindex="-1">"</button>
                        </span>

                        <span class="trix-button-group trix-button-group--font-size-tools" data-trix-button-group="font-size-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-font-size-increase" data-trix-attribute="increaseFontSize" title="Increase Font Size" tabindex="-1">A+</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-font-size-decrease" data-trix-attribute="decreaseFontSize" title="Decrease Font Size" tabindex="-1">A-</button>
                        </span>

                        <span class="trix-button-group trix-button-group--list-tools" data-trix-button-group="list-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-bullet-list" data-trix-attribute="bullet" title="Bullet List" tabindex="-1">• List</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-number-list" data-trix-attribute="number" title="Numbered List" tabindex="-1">1. List</button>
                        </span>

                        <span class="trix-button-group trix-button-group--link-tools" data-trix-button-group="link-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-link" data-trix-attribute="href" data-trix-action="link" data-trix-key="k" title="Link (Ctrl+K)" tabindex="-1">🔗</button>
                        </span>

                        <span class="trix-button-group trix-button-group--attachment-tools" data-trix-button-group="attachment-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-attach" data-trix-action="attachFiles" title="Attach Files" tabindex="-1">📎</button>
                        </span>
                    </div>
                    <div class="trix-button-row">
                        <span class="trix-button-group trix-button-group--history-tools" data-trix-button-group="history-tools">
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-undo" data-trix-action="undo" data-trix-key="z" title="Undo (Ctrl+Z)" tabindex="-1">↶ Undo</button>
                            <button type="button" class="trix-button trix-button--icon trix-button--icon-redo" data-trix-action="redo" data-trix-key="shift+z" title="Redo (Ctrl+Shift+Z)" tabindex="-1">↷ Redo</button>
                        </span>
                    </div>
                `;
            };
        });

        // Auto-expand editor based on content
        document.addEventListener('DOMContentLoaded', () => {
            const setupAutoExpand = (editor) => {
                const autoExpand = () => {
                    editor.style.height = 'auto';
                    const newHeight = Math.max(editor.scrollHeight, 350);
                    editor.style.height = newHeight + 'px';
                };

                editor.addEventListener('input', autoExpand);
                editor.addEventListener('trix-change', autoExpand);

                // Initial setup
                setTimeout(autoExpand, 100);
            };

            // Setup for create modal
            const createEditor = document.querySelector('#create-post-content + trix-editor');
            if (createEditor) setupAutoExpand(createEditor);

            // Setup for edit modal (after modal opens)
            document.addEventListener('livewire:navigated', () => {
                const editEditor = document.querySelector('#edit-post-content + trix-editor');
                if (editEditor) setupAutoExpand(editEditor);
            });
        });
    </script>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Blog Management</flux:heading>
            <flux:subheading>Add, edit, publish, and delete blog posts</flux:subheading>
        </div>
        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
            Add Blog
        </flux:button>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <flux:input
            wire:model.live.debounce.300ms="searchTerm"
            placeholder="Search blogs..."
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
            <flux:select.option value="published">Published</flux:select.option>
            <flux:select.option value="draft">Draft</flux:select.option>
        </flux:select>
    </div>

    {{-- Posts List --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->posts as $post)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">/{{ $post->slug }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $post->category?->name ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $post->user?->name ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($post->published_at) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                        @endif">
                                        {{ $post->published_at ? 'Published' : 'Draft' }}
                                    </span>
                                    <flux:button
                                        wire:click="togglePublish({{ $post->id }})"
                                        variant="ghost"
                                        size="sm"
                                        icon="{{ $post->published_at ? 'eye-slash' : 'eye' }}"
                                        class="h-6 w-6 p-0"
                                        title="{{ $post->published_at ? 'Unpublish' : 'Publish' }}"
                                    />
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $post->updated_at?->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="openEditModal({{ $post->id }})" variant="ghost" size="sm" icon="pencil" />
                                    <flux:button wire:click="openDeleteModal({{ $post->id }})" variant="ghost" size="sm" icon="trash" class="text-red-600" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <flux:icon name="newspaper" class="mx-auto h-12 w-12 text-gray-400" />
                                <p class="mt-4 text-gray-500">No blog posts found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($this->posts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->posts->links() }}
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    <flux:modal wire:model="showCreateModal" class="max-w-5xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Create Blog</flux:heading>
                <flux:subheading>Write a new post and publish now or later</flux:subheading>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:input wire:model="title" label="Title" required />
                    <flux:error name="title" />

                    <flux:select wire:model="categoryId" label="Category" required>
                        <flux:select.option value="">Select Category</flux:select.option>
                        @foreach($this->categories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="categoryId" />

                    <flux:input wire:model="image" label="Image (URL or path)" placeholder="Optional" />
                    <flux:error name="image" />

                    <flux:input wire:model="publishedAt" type="datetime-local" label="Published At (optional)" />
                    <flux:error name="publishedAt" />
                </div>

                <div class="space-y-2">
                    <flux:label>Content</flux:label>

                    <div
                        wire:ignore
                        x-data
                        x-init="
                            const input = document.getElementById('create-post-content');
                            const editor = $el.querySelector('trix-editor');

                            editor.addEventListener('trix-change', () => $wire.set('content', input.value));

                            window.addEventListener('trix:set-content', (e) => {
                                if (e.detail?.id !== 'create-post-content') return;
                                editor.editor.loadHTML(e.detail?.html || '');
                                $wire.set('content', input.value);
                            });
                        "
                        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3"
                    >
                        <input id="create-post-content" type="hidden" value="{{ $content }}">
                        <trix-editor input="create-post-content" class="trix-content text-sm text-gray-900 bg-white"></trix-editor>
                    </div>

                    <flux:error name="content" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <flux:button wire:click="$set('showCreateModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="createPost" variant="primary">Create Blog</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Edit Modal --}}
    <flux:modal wire:model="showEditModal" class="max-w-5xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Edit Blog</flux:heading>
                <flux:subheading>Update the post and re-publish if needed</flux:subheading>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:input wire:model="title" label="Title" required />
                    <flux:error name="title" />

                    <flux:select wire:model="categoryId" label="Category" required>
                        @foreach($this->categories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="categoryId" />

                    <flux:input wire:model="image" label="Image (URL or path)" placeholder="Optional" />
                    <flux:error name="image" />

                    <flux:input wire:model="publishedAt" type="datetime-local" label="Published At (optional)" />
                    <flux:error name="publishedAt" />
                </div>

                <div class="space-y-2">
                    <flux:label>Content</flux:label>

                    <div
                        wire:ignore
                        x-data
                        x-init="
                            const input = document.getElementById('edit-post-content');
                            const editor = $el.querySelector('trix-editor');

                            editor.addEventListener('trix-change', () => $wire.set('content', input.value));

                            window.addEventListener('trix:set-content', (e) => {
                                if (e.detail?.id !== 'edit-post-content') return;
                                editor.editor.loadHTML(e.detail?.html || '');
                                $wire.set('content', input.value);
                            });
                        "
                        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3"
                    >
                        <input id="edit-post-content" type="hidden" value="{{ $content }}">
                        <trix-editor input="edit-post-content" class="trix-content text-sm text-gray-900 bg-white"></trix-editor>
                    </div>

                    <flux:error name="content" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <flux:button wire:click="$set('showEditModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="updatePost" variant="primary">Update Blog</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-md">
        <div class="space-y-6">
            <div>
                <flux:heading size="sm">Delete Blog</flux:heading>
                <flux:subheading>This action cannot be undone.</flux:subheading>
            </div>

            <div class="rounded-lg border border-red-200 dark:border-red-900/40 bg-red-50 dark:bg-red-900/10 p-4 text-sm text-red-700 dark:text-red-300">
                You are about to delete <span class="font-semibold">{{ $deletingPost?->title }}</span>.
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="deletePost" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>

    <script data-navigate-once>
        document.addEventListener('livewire:init', () => {
            Livewire.on('trix:set-content', (payload) => {
                window.dispatchEvent(new CustomEvent('trix:set-content', { detail: payload }));
            });
        });
    </script>
</div>

