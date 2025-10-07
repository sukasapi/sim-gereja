<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Kategori') }}
                @if(auth()->user()->isSuperAdmin())
                    <span class="text-sm text-gray-500 ml-2">(Mode Superadmin - Hanya dapat melihat)</span>
                @endif
            </h2>
            @if(!auth()->user()->isSuperAdmin())
                <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Buat Kategori Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($categories as $category)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }};"></div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="toggleActive({{ $category->id }})" class="text-sm px-2 py-1 rounded {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if($category->description)
                                        <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                        <span>{{ $category->posts_count }} posting</span>
                                        <span>Slug: {{ $category->slug }}</span>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('categories.show', $category) }}" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 text-center py-2 px-3 rounded text-sm font-medium">
                                            Lihat
                                        </a>
                                        
                                        @if(!auth()->user()->isSuperAdmin())
                                            <!-- Admin gereja dapat melakukan CRUD -->
                                            <a href="{{ route('categories.edit', $category) }}" class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-center py-2 px-3 rounded text-sm font-medium">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-800 py-2 px-3 rounded text-sm font-medium">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kategori</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat kategori pertama Anda.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleActive(categoryId) {
            fetch(`/categories/${categoryId}/toggle-active`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</x-app-layout>
