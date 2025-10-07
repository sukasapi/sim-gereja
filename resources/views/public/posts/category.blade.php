<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('public.posts.index') }}" class="text-indigo-600 hover:text-indigo-800">Berita</a>
                <span class="text-gray-400 mx-2">/</span>
                <span class="text-gray-600">{{ $category->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Category Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center mb-4">
                    <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $category->color }};"></div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                </div>
                
                @if($category->description)
                    <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                @endif
                
                <div class="text-sm text-gray-500">
                    {{ $posts->total() }} posting dalam kategori ini
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-3">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada posting</h3>
                                    <p class="mt-1 text-sm text-gray-500">Belum ada posting dalam kategori {{ $category->name }}.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kategori Lainnya</h3>
                        <div class="space-y-2">
                            @foreach($categories as $cat)
                                <a href="{{ route('public.posts.category', $cat) }}" class="flex items-center justify-between p-2 rounded hover:bg-gray-50 {{ $cat->id === $category->id ? 'bg-gray-100' : '' }}">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $cat->color }};"></div>
                                        <span class="text-sm text-gray-700">{{ $cat->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $cat->posts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
