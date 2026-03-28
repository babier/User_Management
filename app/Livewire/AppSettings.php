<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class AppSettings extends Component
{
    public $leave_amount;
    public $reset_month;

    public function mount()
    {
        $this->leave_amount = Setting::where('key', 'default_leave_amount')->first()->value;
        $this->reset_month = Setting::where('key', 'reset_month')->first()->value;
    }

    public function updateSettings()
    {
        Setting::where('key', 'default_leave_amount')->update(['value' => $this->leave_amount]);
        Setting::where('key', 'reset_month')->update(['value' => $this->reset_month]);

        session()->flash('message', 'Pengaturan berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.app-settings')->layout('layouts.app');
    }
}