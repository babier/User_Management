<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Leave;
use Livewire\WithPagination;

class LeaveApproval extends Component
{
    use WithPagination;

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);
        session()->flash('message', 'Cuti berhasil disetujui.');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        session()->flash('message', 'Cuti ditolak.');
    }

    public function render()
    {
        return view('livewire.leave-approval', [
            'pendingLeaves' => Leave::with('user')->where('status', 'pending')->latest()->paginate(10)
        ])->layout('layouts.app');
    }
}
