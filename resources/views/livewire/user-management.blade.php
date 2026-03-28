<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded">{{ session('message') }}</div>
        @endif

        <div class="flex items-center justify-between bg-white p-6 rounded-xl shadow-md border-r-4 border-indigo-500 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen User</h2>
            </div>
            <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>

        {{-- Form Tambah User --}}
        <form wire:submit.prevent="save" class="mb-8 bg-white p-4 shadow rounded">
            <div class="mb-5">
                <h2>Tambah User</h2>
            </div>
            <div class="mb-4">
                <label>Nama</label>
                <input type="text" wire:model="name" class="w-full border-gray-300 rounded">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mb-4">
                <label>Email</label>
                <input type="email" wire:model="email" class="w-full border-gray-300 rounded">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mb-4">
                <label>Password</label>
                <input type="password" wire:model="password" class="w-full border-gray-300 rounded">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mb-4">
                <x-input-label for="role" value="Role" />
                <select wire:model="role" class="border-gray-300 rounded-md shadow-sm w-full">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <x-primary-button class="mr-1">{{ $isEdit ? 'Update User' : 'Simpan User' }}</x-primary-button>
            @if($isEdit)
                <x-secondary-button wire:click="resetInput" class="text-gray-500">Batal</x-secondary-button>
            @endif
        </form>

        {{-- Tabel User --}}
        <div>
            <div class="mb-4">
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..." class="w-full" />
            </div>

            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-2 py-3 text-left w-16">ID</th>
                        <th wire:click="sortBy('name')" class="px-6 py-3 cursor-pointer hover:bg-gray-200">
                            Nama 
                            @if($sortCollumn === 'name')
                                @if($sortDirection === 'asc') 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            @endif
                        </th>
                        <th wire:click="sortBy('email')" class="px-6 py-3 cursor-pointer hover:bg-gray-200">
                            Email
                            @if($sortCollumn === 'email')
                                @if($sortDirection === 'asc') 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            @endif
                        </th>
                        <th wire:click="sortBy('role')" class="px-6 py-3 cursor-pointer hover:bg-gray-200">
                            Role
                            @if($sortCollumn === 'role')
                                @if($sortDirection === 'asc') 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-t">
                        <td class="p-2 text-left">{{ $loop->iteration }}</td>
                        <td class="p-2">{{ $user->name }}</td>
                        <td class="p-2">{{ $user->email }}</td>
                        <td class="p-2">{{ $user->role }}</td>
                        <td class="p-2">
                            <x-primary-button wire:click="edit({{ $user->id }})" class="mr-1">Edit</x-primary-button>
                            <x-danger-button wire:click="delete({{ $user->id }})" wire:confirm="Hapus?">Hapus</x-danger-button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
                </table>
        </div>
    </div>
</div>