<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shift;

class ShiftMaster extends Component
{
    public $name, $start_time, $end_time, $color = '#4f46e5';
    public $shiftId;

    protected $rules = [
        'name' => 'required|min:3',
        'start_time' => 'required',
        'end_time' => 'required',
        'color' => 'required',
    ];

    public function save()
    {
        $this->validate();

        // Array pertama adalah kunci pencarian (ID)
        // Array kedua adalah data yang ingin disimpan/diupdate
        Shift::updateOrCreate(
            ['id' => $this->shiftId],
            [
                'name'       => $this->name,
                'start_time' => $this->start_time,
                'end_time'   => $this->end_time,
                'color'      => $this->color,
            ]
        );

        $this->reset();
        $this->shiftId = null;
        session()->flash('message', 'Jenis Shift berhasil disimpan!');
    }

    public function delete($id)
    {
        Shift::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.shift-master', [
            'shifts' => Shift::all()
        ])->layout('layouts.app');
    }
}
