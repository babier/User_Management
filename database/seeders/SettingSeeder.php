<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => 'default_leave_amount'],
            ['value' => '12', 'description' => 'Jumlah kuota cuti tahunan standar']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'reset_month'],
            ['value' => '6', 'description' => 'Bulan di mana kuota cuti direset (1=Januari)']
        );
    }
}
