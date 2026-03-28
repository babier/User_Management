<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortCollumn = 'name';
    public $sortDirection = 'asc';

    public $name, $email, $password, $role = 'user';
    public $userId;
    public $isEdit = false;

    public function save()
    {
        $rules = [
            'name' => 'required|min:5',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->userId), // validasi unik email, kecuali untuk user yang sedang diedit
            ],
            'role' => 'required',
        ];

        // password hanya wajib saat tambah user baru
        if (!$this->isEdit) {
            $rules['password'] = 'required|min:7';
        }

        $this->validate($rules);

        if ($this->isEdit) {
            $user = User::find($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ]);

            // update password jika diisi
            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            session()->flash('message', 'User berhasil diperbarui!');
        } else {

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);

            session()->flash('message', 'User berhasil ditambahkan!');
        }
        $this->resetInput();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User berhasil dihapus!');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($collumn)
    {
        if ($this->sortCollumn === $collumn) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortCollumn = $collumn;
            $this->sortDirection = 'asc';
        }
    }

    public function resetInput()
    {
        $this->reset(['name', 'email', 'password', 'role', 'userId', 'isEdit']);
    }

    public function render()
    {
        return view('livewire.user-management', [
            'users' => User::where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orderBy($this->sortCollumn, $this->sortDirection)
                ->paginate(20)
        ])->layout('layouts.app');
    }
}
