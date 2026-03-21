<x-layouts::guest :title="__('Blog')">
    <div class="max-w-7xl mx-auto space-y-8">
        {{-- Header --}}
        <div class="text-center space-y-4">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white">Our Blog</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                News, updates, study tips, and educational insights to help you succeed
            </p>
        </div>

        @php
            $featuredPost = $posts->first();
            $remainingPosts = $posts->skip(1)->values();
        @endphp

        {{-- Featured Post (First Post) --}}
        @if($featuredPost)
            <article class="group relative rounded-2xl overflow-hidden shadow-xl">
                @if(filled($featuredPost->image))
                    <div class="aspect-[21/9] bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
                        <img src="{{ $featuredPost->image }}" alt="{{ $featuredPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                @else
                    <div class="aspect-[21/9] bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                @endif

                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-3">
                        @if($featuredPost->category)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white backdrop-blur-sm">
                                {{ $featuredPost->category->name }}
                            </span>
                        @endif
                        <time datetime="{{ $featuredPost->published_at?->toDateString() }}" class="text-sm text-white/80">
                            {{ $featuredPost->published_at?->format('F j, Y') }}
                        </time>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-3">
                        <a href="{{ route('blog.show', $featuredPost) }}" class="hover:text-indigo-300 transition-colors">
                            {{ $featuredPost->title }}
                        </a>
                    </h2>

                    <p class="text-white/80 text-sm sm:text-base line-clamp-2 mb-4">
                        {{ \Illuminate\Support\Str::limit(strip_tags($featuredPost->content), 200) }}
                    </p>

                    <a href="{{ route('blog.show', $featuredPost) }}" class="inline-flex items-center gap-2 text-white font-medium hover:text-indigo-300 transition-colors">
                        Read Full Article
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </article>
        @endif

        {{-- Blog Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($remainingPosts as $post)
                <article class="group rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                    @if(filled($post->image))
                        <div class="aspect-[16/9] bg-gray-100 dark:bg-gray-800 overflow-hidden">
                            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        </div>
                    @else
                        <div class="aspect-[16/9] bg-gradient-to-br from-indigo-500/20 via-purple-500/10 to-pink-500/20"></div>
                    @endif

                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                            @if($post->category)
                                <span class="inline-flex items-center rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-2 py-0.5">
                                    {{ $post->category->name }}
                                </span>
                            @endif
                            <span>•</span>
                            <time datetime="{{ $post->published_at?->toDateString() }}">
                                {{ $post->published_at?->format('M j, Y') }}
                            </time>
                        </div>

                        <h2 class="text-lg font-semibold leading-snug text-gray-900 dark:text-white">
                            <a href="{{ route('blog.show', $post) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}
                        </p>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ route('blog.show', $post) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                Read more →
                            </a>
                            @if($post->user)
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->user->name }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                @if($posts->count() <= 1)
                    <div class="col-span-full rounded-2xl border border-dashed border-gray-300 dark:border-gray-800 p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No more blog posts</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Check back later for new content.</p>
                    </div>
                @endif
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-layouts::guest>
