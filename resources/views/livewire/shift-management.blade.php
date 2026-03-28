<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        
        <div class="flex items-center justify-between bg-white p-6 rounded-xl shadow-md border-r-4 border-indigo-500 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Shift</h2>
            </div>
            <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Form Assign Shift --}}
            <div class="bg-white p-6 rounded-xl shadow-sm h-fit border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Assign Shift Baru</h3>
                
                <form wire:submit.prevent="saveShift" class="space-y-4">
                    <div>
                        <x-input-label for="user_id" value="Pilih Karyawan" />
                        <select wire:model="user_id" id="user_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Nama --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="shift_id" value="Jenis Shift" />
                        <select wire:model="shift_id" id="shift_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('shift_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="date" value="Tanggal Kerja" />
                        <x-text-input wire:model="date" id="date" type="date" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        Simpan Jadwal
                    </button>
                </form>

                @if (session()->has('message'))
                    <div class="mt-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm">
                        {{ session('message') }}
                    </div>
                @endif
            </div>

            {{-- Roster Mingguan --}}
            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Roster Mingguan</h3>
                
                    {{-- Filter dan Sorting --}}
                    <div class="flex gap-2">
                        <div class="relative">
                            <input wire:model.live="search" type="text" 
                                placeholder="Cari nama karyawan..." 
                                class="text-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full md:w-64">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">Karyawan</th>
                                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">Shift</th>
                                <th wire:click="toggleSortDirection" class="py-3 px-4 text-xs font-bold text-gray-500 uppercase text-center cursor-pointer hover:bg-gray-200">
                                    Tanggal
                                    @if($sortDirection === 'asc') 
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @endif
                                </th>
                                <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($userShifts as $us)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-800">{{ $us->user->name }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded text-[10px] font-bold text-white uppercase" style="background-color: {{ $us->shift->color }}">
                                            {{ $us->shift->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-center text-gray-600">
                                        {{ \Carbon\Carbon::parse($us->date)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <button wire:click="deleteShift({{ $us->id }})" class="text-red-400 hover:text-red-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-gray-400 italic text-sm">Belum ada jadwal shift yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $userShifts->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>