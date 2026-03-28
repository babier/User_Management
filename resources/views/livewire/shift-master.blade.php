<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Tambah Jenis Shift</h3>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <x-input-label value="Nama Shift" />
                        <x-text-input wire:model="name" type="text" class="block mt-1 w-full" placeholder="ex: Shift Pagi" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Jam Mulai" />
                            <x-text-input wire:model="start_time" type="time" class="block mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label value="Jam Selesai" />
                            <x-text-input wire:model="end_time" type="time" class="block mt-1 w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Warna Label" />
                        <input wire:model="color" type="color" class="block mt-1 w-full h-10 p-1 rounded-md border-gray-300 shadow-sm">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition shadow-md">
                        Simpan Master Shift
                    </button>
                </form>
            </div>

            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Jam Kerja</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                                <th class="py-3 px-4">Nama</th>
                                <th class="py-3 px-4">Jam Kerja</th>
                                <th class="py-3 px-4 text-center">Warna</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($shifts as $s)
                            <tr class="text-sm">
                                <td class="py-3 px-4 font-bold">{{ $s->name }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $s->start_time }} - {{ $s->end_time }}</td>
                                <td class="py-3 px-4 flex justify-center">
                                    <div class="w-6 h-6 rounded-full" style="background-color: {{ $s->color }}"></div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <x-danger-button wire:click="delete({{ $s->id }})" wire:confirm="Hapus?">Hapus</x-danger-button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>