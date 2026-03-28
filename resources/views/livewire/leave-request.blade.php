<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-bold mb-4">Form Pengajuan Cuti</h3>
        
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <form wire:submit.prevent="submitRequest" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Tanggal Mulai" />
                    <x-text-input 
                        type="date" 
                        wire:model.live="start_date" 
                        min="{{ date('Y-m-d') }}" 
                        class="w-full" 
                    />
                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                </div>
                
                <div>
                    <x-input-label value="Tanggal Selesai" />
                    <x-text-input 
                        type="date" 
                        wire:model="end_date" 
                        min="{{ $start_date ? $start_date : date('Y-m-d') }}" 
                        class="w-full" 
                        :disabled="!$start_date"
                    />
                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                </div>
            </div>
            <div>
                <x-input-label value="Alasan Cuti" />
                <textarea wire:model="reason" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                <x-input-error :messages="$errors->get('reason')" class="mt-2" />
            </div>
            <x-primary-button>Kirim Pengajuan</x-primary-button>
        </form>
    </div>

    {{-- Riwayat Cuti Saya --}}
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-bold mb-4">Riwayat Cuti Saya</h3>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="py-2">Tanggal</th>
                    <th class="py-2">Alasan</th>
                    <th class="py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myLeaves as $leave)
                <tr class="border-b">
                    <td class="py-2 text-sm">{{ $leave->start_date }} s/d {{ $leave->end_date }}</td>
                    <td class="py-2 text-sm">{{ $leave->reason }}</td>
                    <td class="py-2">
                        <span class="px-2 py-1 rounded text-xs {{ $leave->status == 'approved' ? 'bg-green-100 text-green-700' : ($leave->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ strtoupper($leave->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="py-10 text-center text-gray-400 italic" colspan="3">
                        Anda belum mengajukan cuti, Segera ajukan cuti untuk menikmati waktu istirahat Anda!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $myLeaves->links() }}</div>
    </div>

    {{-- Riwayat Cuti Semua Karyawan (untuk Admin) --}}
    @if(auth()->user()->role === 'admin')
    <div class="bg-white p-6 rounded-lg shadow-sm mt-6">
        <h3 class="text-lg font-bold mb-4">Riwayat Cuti Semua Karyawan</h3>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="py-2">Nama Karyawan</th>
                    <th class="py-2">Tanggal</th>
                    <th class="py-2">Durasi</th>
                    <th class="py-2">Alasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allLeaves as $leave)
                <tr class="border-b">
                    <td class="py-2 text-sm">{{ $leave->user->name }}</td>
                    <td class="py-2 text-sm">
                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} - 
                        {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                    </td>
                    <td class="py-2 text-sm">
                        @php
                            $duration = \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
                        @endphp
                        {{ $duration }} hari</td>
                    <td class="py-2 text-sm">{{ $leave->reason }}</td>
                </tr>
                @empty
                <tr>
                    <td class="py-10 text-center text-gray-400 italic" colspan="4">
                        Saat ini tidak ada karyawan yang sedang cuti.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

</div>