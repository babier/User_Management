<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Menghitung sisa cuti untuk user
    public function getRemainingLeaveAttribute()
    {
        // Ambil Pengaturan dari Database
        $settings = \App\Models\Setting::whereIn('key', ['default_leave_amount', 'reset_month'])
            ->pluck('value', 'key');

        $defaultQuota = (int) ($settings['default_leave_amount'] ?? 12);
        $resetMonth = (int) ($settings['reset_month'] ?? 1);

        $now = now();

        // Tentukan siklus cuti berdasarkan bulan reset
        $startCycle = ($now->month >= $resetMonth) 
            ? $now->copy()->month($resetMonth)->startOfMonth() 
            : $now->copy()->subYear()->month($resetMonth)->startOfMonth();
        
        $endCycle = $startCycle->copy()->addYear()->subDay();

        // Hitung total hari cuti yang sudah APPROVED
        $usedLeave = $this->leaves()
            ->where('status', 'approved')
            ->whereBetween('start_date', [$startCycle, $endCycle])
            ->get()
            ->sum(function($leave) {
                $start = \Carbon\Carbon::parse($leave->start_date);
                $end = \Carbon\Carbon::parse($leave->end_date);
                return $start->diffInDays($end) + 1;
            });

        return $defaultQuota - $usedLeave;
    }

    public function leaves() {
        return $this->hasMany(Leave::class);
    }

    public function userShifts() {
        return $this->hasMany(UserShift::class);
    }
}
