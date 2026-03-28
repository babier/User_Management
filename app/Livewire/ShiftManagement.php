<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Shift;
use App\Models\UserShift;
use Livewire\WithPagination;

class ShiftManagement extends Component
{
    use WithPagination;

    public $user_id, $shift_id, $date;
    public $search = '';
    public $sortDirection = 'desc';

    // Reset pagination saat user mengetik di kolom pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Funsgi untuk mengubah arah sorting
    public function toggleSortDirection()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        return view('livewire.shift-management', [
            'users' => User::orderBy('name', 'asc')->get(),
            'shifts' => Shift::all(),
            
            // Query dengan Filter Pencarian dan Sorting
            'userShifts' => UserShift::with(['user', 'shift'])
                ->whereHas('user', function($query) {
                    $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($this->search) . '%']);                })
                ->orderBy('date', $this->sortDirection)
                ->paginate(10)
        ])->layout('layouts.app');
    }

    public function deleteShift($id)
    {
        UserShift::find($id)->delete();
        session()->flash('message', 'Jadwal berhasil dihapus!');
    }

    public function saveShift()
    {
        $this->validate([
            'user_id' => 'required',
            'shift_id' => 'required',
            'date' => 'required|date',
        ]);

        UserShift::updateOrCreate(
            ['user_id' => $this->user_id, 'date' => $this->date],
            ['shift_id' => $this->shift_id]
        );

        session()->flash('message', 'Jadwal shift berhasil disimpan!');
    }
}
