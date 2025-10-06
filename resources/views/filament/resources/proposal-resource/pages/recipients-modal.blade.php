<div class="space-y-4">
    {{-- Informasi Proposal --}}
    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyek</p>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $proposal->project->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pemohon</p>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $proposal->requester }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Permintaan</p>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $proposal->request_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Proposal</p>
                <p class="mt-1">
                    <span @class([
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $proposal->status === 'pending',
                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $proposal->status === 'approved',
                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => $proposal->status === 'rejected',
                    ])>
                        {{ match($proposal->status) {
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default => $proposal->status,
                        } }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Tabel Penerima --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Alamat</th>
                    <th scope="col" class="px-6 py-3">Jumlah</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recipients as $recipient)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ $recipient->recipient_name }}</td>
                        <td class="px-6 py-4">{{ $recipient->recipient_address }}</td>
                        <td class="px-6 py-4">{{ $recipient->quantity }}</td>
                        <td class="px-6 py-4">
                            <span @class([
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $recipient->status === 'pending',
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $recipient->status === 'approved',
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => $recipient->status === 'rejected',
                            ])>
                                {{ match($recipient->status) {
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => $recipient->status,
                                } }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 