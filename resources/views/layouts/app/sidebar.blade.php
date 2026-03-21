<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Platform')" class="grid">
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            {{-- Main User Features --}}
            <flux:sidebar.item icon="book-open" :href="route('exams.index')" :current="request()->routeIs('exams.*')" wire:navigate>
                {{ __('Push Shop (Exams)') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="shopping-bag" :href="route('books.index')" :current="request()->routeIs('books.*')" wire:navigate>
                {{ __('Bookshop') }}
                @php
                    $cartCount = \App\Models\Cart::where('session_id', \Illuminate\Support\Facades\Session::getId())->sum('quantity');
                @endphp
                @if($cartCount > 0)
                    <flux:badge variant="primary" size="sm" class="ml-auto">{{ $cartCount }}</flux:badge>
                @endif
            </flux:sidebar.item>

            <flux:sidebar.item icon="newspaper" :href="route('blog.index')" :current="request()->routeIs('blog.*')" wire:navigate>
                {{ __('Blog') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        @if(auth()->user()->isAdmin())
            <flux:sidebar.group :heading="__('Administration')" class="grid">
                <flux:sidebar.item icon="presentation-chart-bar" :href="route('admin.exams')" :current="request()->routeIs('admin.exams')" wire:navigate>
                    {{ __('Manage Exams') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="question-mark-circle" :href="route('admin.questions')" :current="request()->routeIs('admin.questions')" wire:navigate>
                    {{ __('Manage Questions') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="tag" :href="route('admin.categories')" :current="request()->routeIs('admin.categories')" wire:navigate>
                    {{ __('Manage Categories') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open" :href="route('admin.books')" :current="request()->routeIs('admin.books')" wire:navigate>
                    {{ __('Book Uploads') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="newspaper" :href="route('admin.blogs')" :current="request()->routeIs('admin.blogs')" wire:navigate>
                    {{ __('Manage Blog') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
        @endif
    </flux:sidebar.nav>

    <flux:spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="cog" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>
            {{ __('Settings') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

    <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
</flux:sidebar>

<!-- Mobile User Menu -->
<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <flux:spacer />

    <flux:dropdown position="top" align="end">
        <flux:profile
            :initials="auth()->user()->initials()"
            icon-trailing="chevron-down"
        />

        <flux:menu>
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                        />

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <flux:menu.radio.group>
                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                    {{ __('Settings') }}
                </flux:menu.item>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('Log out') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:header>

{{ $slot }}
