<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
            <div class="p-8">

                <div class="flex items-center justify-between bg-white p-6 rounded-xl shadow-md border-r-4 border-indigo-500 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Pengaturan Aplikasi</h2>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateSettings" class="space-y-6">
                    <div>
                        <x-input-label value="Jatah Cuti Tahunan Default (Hari)" />
                        <x-text-input wire:model="leave_amount" type="number" class="block mt-1 w-full" />
                        <p class="text-xs text-gray-500 mt-1">Jumlah cuti yang akan diberikan kepada karyawan.</p>
                    </div>

                    <div>
                        <x-input-label value="Bulan Reset Kuota" />
                        <select wire:model="reset_month" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Sistem akan mereset sisa cuti otomatis pada bulan yang dipilih.</p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-lg transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>