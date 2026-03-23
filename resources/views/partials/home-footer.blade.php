<footer class="mt-12 bg-primary-950 py-12 text-primary-100/80">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 grid grid-cols-1 gap-8 md:grid-cols-4">
            <div class="col-span-1 md:col-span-2">
                <span class="mb-4 block text-2xl font-extrabold tracking-tight text-white">
                    DISTINCTION<span class="text-secondary-400">DEN.</span>
                </span>
                <p class="max-w-xs text-sm">
                    Empowering students with quality educational resources and practice exams to achieve academic excellence.
                </p>
            </div>

            <div>
                <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">Resources</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('exams.index') }}" class="transition hover:text-secondary-200">Exams</a></li>
                    <li><a href="{{ route('home.books') }}" class="transition hover:text-secondary-200">Books</a></li>
                    <li><a href="{{ route('blog.index') }}" class="transition hover:text-secondary-200">Blog</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="transition hover:text-secondary-200">Contact Us</a></li>
                    <li><a href="#" class="transition hover:text-secondary-200">FAQ</a></li>
                    <li><a href="#" class="transition hover:text-secondary-200">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} Distinction Den Online Tutorial. All rights reserved.</p>
        </div>
    </div>
</footer>
