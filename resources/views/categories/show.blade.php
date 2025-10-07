<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kategori: ') . $category->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('categories.edit', $category) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Category Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center mb-4">
                        <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $category->color }};"></div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                        @if(!$category->is_active)
                            <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    
                    @if($category->description)
                        <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                    @endif
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                        <span>Slug: <code class="bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code></span>
                        <span>Dibuat: {{ $category->created_at->format('d M Y H:i') }}</span>
                        <span>Diupdate: {{ $category->updated_at->format('d M Y H:i') }}</span>
                        <span>{{ $posts->total() }} posting</span>
                    </div>
                </div>
            </div>

            <!-- Posts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Posting dalam Kategori Ini</h3>
                    
                    @if($posts->count() > 0)
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start space-x-4">
                                        @if($post->featured_image)
                                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-20 h-20 object-cover rounded-md">
                                        @endif
                                        
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">
                                                    {{ $post->title }}
                                                </a>
                                                @if($post->is_featured)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                        Featured
                                                    </span>
                                                @endif
                                            </h4>
                                            
                                            @if($post->excerpt)
                                                <p class="text-gray-600 mb-2">{{ Str::limit($post->excerpt, 150) }}</p>
                                            @endif
                                            
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                                <span>Oleh: <strong>{{ $post->user->name }}</strong></span>
                                                <span>Gereja: <strong>{{ $post->church->name }}</strong></span>
                                                <span>{{ $post->created_at->format('d M Y') }}</span>
                                                <span>{{ $post->views }} views</span>
                                                
                                                @switch($post->status)
                                                    @case('published')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Published
                                                        </span>
                                                        @break
                                                    @case('draft')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Draft
                                                        </span>
                                                        @break
                                                    @case('archived')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Archived
                                                        </span>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col space-y-2">
                                            <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                Lihat
                                            </a>
                                            <a href="{{ route('posts.edit', $post) }}" class="text-yellow-600 hover:text-yellow-900 text-sm">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada posting</h3>
                                <p class="mt-1 text-sm text-gray-500">Belum ada posting dalam kategori ini.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
