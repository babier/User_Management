<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-xl font-bold mb-6">Persetujuan Cuti Karyawan</h3>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif

        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-3">Nama</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Alasan</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingLeaves as $leave)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $leave->user->name }}</td>
                    <td class="p-3 text-sm">{{ $leave->start_date }} s/d {{ $leave->end_date }}</td>
                    <td class="p-3 text-sm">{{ $leave->reason }}</td>
                    <td class="p-3 text-center space-x-2">
                        <button wire:click="approve({{ $leave->id }})" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Approve</button>
                        <button wire:click="reject({{ $leave->id }})" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Reject</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">Tidak ada pengajuan pending.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $pendingLeaves->links() }}</div>
    </div>
</div>