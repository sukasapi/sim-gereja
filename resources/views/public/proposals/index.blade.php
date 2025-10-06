<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Daftar Penerima</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900 py-6 text-center">
                    Cek List Proposal
                </h1>
            </div>
        </header>

        <main>
            <div class="py-12">
                <div class="mx-auto sm:px-6 lg:px-8 p-2">
                    {{-- Status Overview --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-lg font-medium text-gray-900">Pending</h3>
                                        <p class="text-2xl font-semibold text-yellow-600">
                                            {{ $recipients->filter(function($recipient) {
                                                return $recipient->proposal->status === 'pending';
                                            })->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-lg font-medium text-gray-900">Approved</h3>
                                        <p class="text-2xl font-semibold text-green-600">
                                            {{ $recipients->filter(function($recipient) {
                                                return $recipient->proposal->status === 'approved';
                                            })->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-lg font-medium text-gray-900">Rejected</h3>
                                        <p class="text-2xl font-semibold text-red-600">
                                            {{ $recipients->filter(function($recipient) {
                                                return $recipient->proposal->status === 'rejected';
                                            })->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            {{-- Filter dan Pencarian --}}
                            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                                <div class="flex-1">
                                    <input type="text" id="search" placeholder="Cari berdasarkan nama penerima, pemohon, alamat, atau status..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>

                            {{-- Tabel --}}
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                                Nama Penerima
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                                                Alamat
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(3)">
                                                Pemohon
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(5)">
                                                Status
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($recipients as $recipient)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $recipient->recipient_name }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $recipient->recipient_address }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $recipient->proposal->requester }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $recipient->quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($recipient->proposal->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($recipient->proposal->status === 'rejected') bg-red-100 text-red-800
                                                        @else bg-yellow-100 text-yellow-800
                                                        @endif">
                                                        {{ ucfirst($recipient->proposal->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('public.proposals.show', $recipient->proposal) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                                        <span class="inline-flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Detail
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                    Tidak ada data penerima yang tersedia
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Pagination --}}
                            <div class="mt-6">
                                {{ $recipients->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Implementasi pencarian dan filter
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableRows = document.querySelectorAll('tbody tr');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const namaPenerima = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const alamat = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const pemohon = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(6) span').textContent.toLowerCase();
                    
                    const matchesSearch = namaPenerima.includes(searchTerm) || 
                                        alamat.includes(searchTerm) || 
                                        pemohon.includes(searchTerm) || 
                                        status.includes(searchTerm);

                    row.style.display = matchesSearch ? '' : 'none';
                });

                // Update nomor urut setelah filter
                updateRowNumbers();
            }

            searchInput.addEventListener('input', filterTable);
        });

        // Implementasi sorting
        let sortDirection = 1; // 1 untuk ascending, -1 untuk descending
        let currentSortColumn = -1;

        function sortTable(columnIndex) {
            const table = document.querySelector('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const headers = table.querySelectorAll('th');
            
            // Reset semua sort icon
            headers.forEach(header => {
                const icon = header.querySelector('.sort-icon');
                if (icon) icon.textContent = '↕';
            });

            // Update sort icon untuk kolom yang aktif
            const currentHeader = headers[columnIndex];
            const currentIcon = currentHeader.querySelector('.sort-icon');
            
            if (currentSortColumn === columnIndex) {
                sortDirection *= -1;
            } else {
                sortDirection = 1;
                currentSortColumn = columnIndex;
            }
            
            currentIcon.textContent = sortDirection === 1 ? '↑' : '↓';

            // Sort rows
            rows.sort((a, b) => {
                const aValue = a.cells[columnIndex].textContent.trim();
                const bValue = b.cells[columnIndex].textContent.trim();
                
                // Khusus untuk kolom status, ambil teks dari span
                if (columnIndex === 5) {
                    const aStatus = a.cells[columnIndex].querySelector('span').textContent.trim();
                    const bStatus = b.cells[columnIndex].querySelector('span').textContent.trim();
                    return sortDirection * aStatus.localeCompare(bStatus);
                }
                
                return sortDirection * aValue.localeCompare(bValue);
            });

            // Reorder rows
            rows.forEach(row => tbody.appendChild(row));

            // Update nomor urut setelah sorting
            updateRowNumbers();
        }

        // Fungsi untuk memperbarui nomor urut
        function updateRowNumbers() {
            const visibleRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
            visibleRows.forEach((row, index) => {
                row.cells[0].textContent = index + 1;
            });
        }
    </script>
</body>
</html> 