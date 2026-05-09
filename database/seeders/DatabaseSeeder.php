<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        Setting::create([
            'key' => 'default_leave_amount',
            'value' => '12',
            'description' => 'Jumlah kuota cuti tahunan standar'
        ]);
        
        Setting::create([
            'key' => 'reset_month',
            'value' => '6',
            'description' => 'Bulan di mana kuota cuti direset'
        ]);
    }
}
