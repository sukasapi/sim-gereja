<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Kategori Baru') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror" 
                                       placeholder="Masukkan nama kategori...">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror" 
                                          placeholder="Deskripsi kategori...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Color -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
                                <div class="mt-1 flex items-center space-x-4">
                                    <input type="color" name="color" id="color" value="{{ old('color', '#3B82F6') }}" 
                                           class="h-10 w-20 border border-gray-300 rounded-md cursor-pointer @error('color') border-red-500 @enderror">
                                    <input type="text" name="color_text" id="color_text" value="{{ old('color', '#3B82F6') }}" 
                                           class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('color') border-red-500 @enderror" 
                                           placeholder="#3B82F6" pattern="^#[0-9A-Fa-f]{6}$">
                                </div>
                                @error('color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Pilih warna untuk kategori ini</p>
                            </div>

                            <!-- Active Status -->
                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Kategori Aktif
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Kategori yang tidak aktif tidak akan muncul dalam pilihan saat membuat posting</p>
                            </div>

                            <!-- Preview -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                                <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 rounded-full mr-3" id="preview-color" style="background-color: {{ old('color', '#3B82F6') }};"></div>
                                        <span class="text-sm font-medium" id="preview-name">{{ old('name', 'Nama Kategori') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2" id="preview-description">{{ old('description', 'Deskripsi kategori...') }}</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan Kategori
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sync color picker with text input
        document.getElementById('color').addEventListener('input', function() {
            document.getElementById('color_text').value = this.value;
            updatePreview();
        });

        document.getElementById('color_text').addEventListener('input', function() {
            if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
                document.getElementById('color').value = this.value;
                updatePreview();
            }
        });

        // Update preview when name or description changes
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('description').addEventListener('input', updatePreview);

        function updatePreview() {
            const name = document.getElementById('name').value || 'Nama Kategori';
            const description = document.getElementById('description').value || 'Deskripsi kategori...';
            const color = document.getElementById('color').value;

            document.getElementById('preview-name').textContent = name;
            document.getElementById('preview-description').textContent = description;
            document.getElementById('preview-color').style.backgroundColor = color;
        }
    </script>
</x-app-layout>
