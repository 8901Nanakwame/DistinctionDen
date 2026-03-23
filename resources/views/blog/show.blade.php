<x-layouts::guest :title="$post->title">
    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Back Link --}}
        <div>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm text-primary-800 dark:text-secondary-200 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Blog
            </a>
        </div>

        {{-- Article Header --}}
        <article class="space-y-6">
            {{-- Category & Date --}}
            <div class="flex flex-wrap items-center gap-3">
                @if($post->category)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 dark:bg-primary-900/40 text-primary-900 dark:text-secondary-200">
                        {{ $post->category->name }}
                    </span>
                @endif
                <span class="text-gray-400">•</span>
                <time datetime="{{ $post->published_at?->toDateString() }}" class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $post->published_at?->format('F j, Y') ?? 'Unpublished' }}
                </time>
                @if($post->user)
                    <span class="text-gray-400">•</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">By {{ $post->user->name }}</span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                {{ $post->title }}
            </h1>

            {{-- Featured Image --}}
            @if(filled($post->image))
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-auto max-h-[500px] object-cover">
                </div>
            @endif
        </article>

        {{-- Article Content --}}
            <div class="prose prose-lg dark:prose-invert max-w-none">
            <div class="bg-surface dark:bg-zinc-950/40 rounded-2xl border border-border dark:border-zinc-800 shadow-lg p-6 sm:p-8 lg:p-10">
                <div class="blog-content text-gray-800 dark:text-gray-200 leading-relaxed space-y-4">
                    {!! $post->content !!}
                </div>
            </div>
        </div>

        {{-- Share Section --}}
        <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Share this article:</span>
            <div class="flex gap-2">
                <button class="p-2 rounded-full bg-blue-500 text-white hover:bg-blue-600 transition-colors" title="Share on Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                </button>
                <button class="p-2 rounded-full bg-sky-500 text-white hover:bg-sky-600 transition-colors" title="Share on Twitter">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path></svg>
                </button>
                <button class="p-2 rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors" title="Share on WhatsApp">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </button>
            </div>
        </div>

        {{-- Related Posts --}}
        @php
            $relatedPosts = \App\Models\Post::with(['category', 'user'])
                ->where('id', '!=', $post->id)
                ->whereNotNull('published_at')
                ->where('category_id', $post->category_id)
                ->latest('published_at')
                ->take(3)
                ->get();

            if ($relatedPosts->count() < 3) {
                $morePosts = \App\Models\Post::with(['category', 'user'])
                    ->where('id', '!=', $post->id)
                    ->whereNotNull('published_at')
                    ->where('category_id', '!=', $post->category_id)
                    ->latest('published_at')
                    ->take(3 - $relatedPosts->count())
                    ->get();
                $relatedPosts = $relatedPosts->concat($morePosts);
            }
        @endphp

        @if($relatedPosts->count() > 0)
            <section class="pt-8 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <a href="{{ route('blog.show', $relatedPost) }}" class="group block bg-surface dark:bg-zinc-950/40 rounded-xl overflow-hidden border border-border dark:border-zinc-800 shadow-sm hover:shadow-lg transition-shadow">
                            @if($relatedPost->image)
                                <div class="aspect-video bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                    <img src="{{ $relatedPost->image }}" alt="{{ $relatedPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                </div>
                            @endif
                            <div class="p-4">
                                @if($relatedPost->category)
                                    <span class="text-xs px-2 py-1 bg-primary-100 dark:bg-primary-900/40 text-primary-800 dark:text-secondary-200 rounded">{{ $relatedPost->category->name }}</span>
                                @endif
                                <h3 class="font-semibold text-gray-900 dark:text-white mt-2 line-clamp-2 group-hover:text-primary-800 dark:group-hover:text-secondary-200">{{ $relatedPost->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    {{ $relatedPost->published_at?->format('M d, Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    {{-- Custom Blog Content Styles --}}
    <style>
        .blog-content h1,
        .blog-content h2,
        .blog-content h3,
        .blog-content h4,
        .blog-content h5,
        .blog-content h6 {
            font-weight: 700;
            line-height: 1.3;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            color: #111827;
        }
        .dark .blog-content h1,
        .dark .blog-content h2,
        .dark .blog-content h3,
        .dark .blog-content h4,
        .dark .blog-content h5,
        .dark .blog-content h6 {
            color: #f9fafb;
        }
        .blog-content h1 { font-size: 2.25rem; }
        .blog-content h2 { font-size: 1.875rem; }
        .blog-content h3 { font-size: 1.5rem; }
        .blog-content h4 { font-size: 1.25rem; }
        .blog-content h5 { font-size: 1.125rem; }
        .blog-content h6 { font-size: 1rem; }

        .blog-content p {
            margin-bottom: 1.25em;
            line-height: 1.8;
        }

        .blog-content ul,
        .blog-content ol {
            margin-bottom: 1.25em;
            padding-left: 1.5em;
        }

        .blog-content li {
            margin-bottom: 0.5em;
            line-height: 1.7;
        }

        .blog-content blockquote {
            border-left: 4px solid #6366f1;
            padding-left: 1em;
            margin: 1.5em 0;
            font-style: italic;
            color: #4b5563;
        }
        .dark .blog-content blockquote {
            color: #9ca3af;
        }

        .blog-content pre {
            background: #1f2937;
            color: #f9fafb;
            padding: 1em;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.5em 0;
        }

        .blog-content code {
            background: #f3f4f6;
            color: #dc2626;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }
        .dark .blog-content code {
            background: #374151;
            color: #fca5a5;
        }

        .blog-content pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .blog-content a {
            color: #4f46e5;
            text-decoration: underline;
        }
        .dark .blog-content a {
            color: #818cf8;
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5em 0;
        }

        .blog-content strong {
            font-weight: 700;
        }

        .blog-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5em 0;
        }

        .blog-content th,
        .blog-content td {
            border: 1px solid #e5e7eb;
            padding: 0.75em;
            text-align: left;
        }
        .dark .blog-content th,
        .dark .blog-content td {
            border-color: #374151;
        }

        .blog-content th {
            background: #f9fafb;
            font-weight: 600;
        }
        .dark .blog-content th {
            background: #374151;
        }
    </style>
</x-layouts::guest>
