<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('public.posts.index') }}" class="text-indigo-600 hover:text-indigo-800">Berita</a>
                <span class="text-gray-400 mx-2">/</span>
                <span class="text-gray-600">{{ $post->title }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Post Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }};">
                            {{ $post->category->name }}
                        </span>
                        <span class="ml-3 text-sm text-gray-500">{{ $post->published_at->format('d M Y H:i') }}</span>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
                    
                    <div class="flex items-center text-sm text-gray-500">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-indigo-600">
                                    {{ strtoupper(substr($post->user->name, 0, 2)) }}
                                </span>
                            </div>
                            <span>Oleh: <strong>{{ $post->user->name }}</strong></span>
                        </div>
                        <span class="mx-4">•</span>
                        <span>{{ $post->church->name }}</span>
                        <span class="mx-4">•</span>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $post->views }} views
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($post->featured_image)
                    <div class="w-full">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 md:h-96 object-cover">
                    </div>
                @endif

                <!-- Post Content -->
                <div class="p-6">
                    @if($post->excerpt)
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <p class="text-lg text-gray-700 italic">{{ $post->excerpt }}</p>
                        </div>
                    @endif

                    <div class="prose max-w-none">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>

                <!-- Post Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Dipublikasi pada {{ $post->published_at->format('d F Y') }} pukul {{ $post->published_at->format('H:i') }}
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('public.posts.category', $post->category) }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                                Lihat semua {{ $post->category->name }}
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Berita Terkait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedPosts as $relatedPost)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                @if($relatedPost->featured_image)
                                    <img src="{{ Storage::url($relatedPost->featured_image) }}" alt="{{ $relatedPost->title }}" class="w-full h-32 object-cover">
                                @endif
                                <div class="p-4">
                                    <div class="flex items-center mb-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $relatedPost->category->color }}20; color: {{ $relatedPost->category->color }};">
                                            {{ $relatedPost->category->name }}
                                        </span>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('public.posts.show', $relatedPost) }}" class="hover:text-indigo-600">
                                            {{ Str::limit($relatedPost->title, 60) }}
                                        </a>
                                    </h4>
                                    <div class="text-xs text-gray-500">
                                        {{ $relatedPost->published_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
