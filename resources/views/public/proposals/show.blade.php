<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Detail Proposal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Detail Proposal
                    </h1>
                    <a href="{{ route('public.proposals.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Kembali
                    </a>
                </div>
            </div>
        </header>

        <main>
            <div class="py-12">
                <div class="mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="space-y-6">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900">Informasi Proposal</h2>
                                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Judul</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $proposal->judul }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <div class="mt-1">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($proposal->status === 'diterima') bg-green-100 text-green-800
                                                    @elseif($proposal->status === 'ditolak') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($proposal->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $proposal->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $proposal->updated_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h2 class="text-lg font-medium text-gray-900">Deskripsi</h2>
                                    <div class="mt-4 text-sm text-gray-900">
                                        {{ $proposal->deskripsi }}
                                    </div>
                                </div>

                                @if($proposal->file_path)
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900">File Proposal</h2>
                                    <div class="mt-4">
                                        <a href="{{ Storage::url($proposal->file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                            Download File
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 