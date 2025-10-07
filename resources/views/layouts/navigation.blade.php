<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
    
    @auth
        @if(auth()->user()->isAdminGereja() || auth()->user()->isSuperAdmin())
            <!-- Post Menu with Submenu -->
            <div class="relative group">
                <x-nav-link :active="request()->routeIs('posts.*') || request()->routeIs('categories.*')">
                    {{ __('Post') }}
                    <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </x-nav-link>
                
                <!-- Submenu -->
                <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="py-1">
                        <a href="{{ route('posts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('posts.*') && !request()->routeIs('categories.*') ? 'bg-gray-100' : '' }}">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Data Posting
                        </a>
                        <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('categories.*') ? 'bg-gray-100' : '' }}">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Kategori Posting
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        @if(auth()->user()->isAdminGereja())
            <x-nav-link :href="route('admin.edit-church')" :active="request()->routeIs('admin.edit-church*')">
                {{ __('Data Gereja') }}
            </x-nav-link>
        @endif
        
        @if(auth()->user()->isSuperAdmin())
            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')">
                {{ __('Manajemen User') }}
            </x-nav-link>
        @endif
        
        <x-nav-link :href="route('public.posts.index')" :active="request()->routeIs('public.posts.*')">
            {{ __('Berita Publik') }}
        </x-nav-link>
    @endauth
</div>

<!-- Mobile Navigation Menu -->
<div class="pt-2 pb-3 space-y-1 sm:hidden">
    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-responsive-nav-link>
    
    @auth
        @if(auth()->user()->isAdminGereja() || auth()->user()->isSuperAdmin())
            <div class="space-y-1">
                <div class="px-3 py-2 text-sm font-medium text-gray-500">
                    Post
                </div>
                <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*') && !request()->routeIs('categories.*')">
                    {{ __('Data Posting') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    {{ __('Kategori Posting') }}
                </x-responsive-nav-link>
            </div>
        @endif
        
        @if(auth()->user()->isAdminGereja())
            <x-responsive-nav-link :href="route('admin.edit-church')" :active="request()->routeIs('admin.edit-church*')">
                {{ __('Data Gereja') }}
            </x-responsive-nav-link>
        @endif
        
        @if(auth()->user()->isSuperAdmin())
            <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')">
                {{ __('Manajemen User') }}
            </x-responsive-nav-link>
        @endif
        
        <x-responsive-nav-link :href="route('public.posts.index')" :active="request()->routeIs('public.posts.*')">
            {{ __('Berita Publik') }}
        </x-responsive-nav-link>
    @endauth
</div>