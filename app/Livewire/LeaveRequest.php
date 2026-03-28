<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Leave;
use Carbon\Carbon;

class LeaveRequest extends Component
{
    public $start_date, $end_date, $reason;

    public function submitRequest()
    {        
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|min:5',
        ]);

        // Hitung durasi hari (termasuk hari terakhir)
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $duration = $start->diffInDays($end) + 1;

        // Cek sisa kuota (menggunakan accessor di Model User yang kita buat sebelumnya)
        if ($duration > auth()->user()->remaining_leave) {
            session()->flash('error', "Kuota tidak cukup. Sisa: " . auth()->user()->remaining_leave . " hari.");
            return;
        }

        Leave::create([
            'user_id' => auth()->id(),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        $this->reset(['start_date', 'end_date', 'reason']);
        session()->flash('message', 'Pengajuan cuti berhasil dikirim!');
    }

    public function render()
    {
        $query = auth()->user()->leaves()->latest();
        
        // Data tambahan untuk Admin
        $allLeaves = [];
        if (auth()->user()->role === 'admin') {
            $allLeaves = Leave::with('user')
                ->where('status', 'approved')
                ->whereDate('end_date', '>=', now()) // Hanya tampilkan yang sedang/akan cuti
                ->orderBy('start_date', 'asc')
                ->get();
        }

        return view('livewire.leave-request', [
            'myLeaves' => $query->paginate(5),
            'allLeaves' => $allLeaves
        ])->layout('layouts.app');
    }
}