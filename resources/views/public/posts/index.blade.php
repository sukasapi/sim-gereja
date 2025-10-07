<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Berita & Informasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Featured Posts -->
                    @if($featuredPosts->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Berita Utama</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($featuredPosts as $post)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                        @if($post->featured_image)
                                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                        @endif
                                        <div class="p-4">
                                            <div class="flex items-center mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                                                    {{ $post->category->name }}
                                                </span>
                                                <span class="ml-2 text-xs text-gray-500">{{ $post->published_at->format('d M Y') }}</span>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                <a href="{{ route('public.posts.show', $post) }}" class="hover:text-indigo-600">
                                                    {{ $post->title }}
                                                </a>
                                            </h4>
                                            @if($post->excerpt)
                                                <p class="text-gray-600 text-sm">{{ Str::limit($post->excerpt, 100) }}</p>
                                            @endif
                                            <div class="mt-3 flex items-center text-xs text-gray-500">
                                                <span>{{ $post->user->name }}</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $post->church->name }}</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $post->views }} views</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- All Posts -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Semua Berita</h3>
                        <div class="space-y-6">
                            @forelse($posts as $post)
                                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                    <div class="md:flex">
                                        @if($post->featured_image)
                                            <div class="md:w-1/3">
                                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 md:h-full object-cover">
                                            </div>
                                        @endif
                                        <div class="p-6 {{ $post->featured_image ? 'md:w-2/3' : 'w-full' }}">
                                            <div class="flex items-center mb-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                                                    {{ $post->category->name }}
                                                </span>
                                                <span class="ml-3 text-sm text-gray-500">{{ $post->published_at->format('d M Y') }}</span>
                                            </div>
                                            <h2 class="text-xl font-semibold text-gray-900 mb-3">
                                                <a href="{{ route('public.posts.show', $post) }}" class="hover:text-indigo-600">
                                                    {{ $post->title }}
                                                </a>
                                            </h2>
                                            @if($post->excerpt)
                                                <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt, 200) }}</p>
                                            @endif
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <span>Oleh: <strong>{{ $post->user->name }}</strong></span>
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $post->church->name }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    {{ $post->views }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="text-center py-12">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada berita</h3>
                                        <p class="mt-1 text-sm text-gray-500">Belum ada berita yang dipublikasi.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kategori</h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <a href="{{ route('public.posts.category', $category) }}" class="flex items-center justify-between p-2 rounded hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }};"></div>
                                        <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $category->posts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
