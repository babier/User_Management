<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShift extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data ke kolom berikut
    protected $fillable = [
        'user_id',
        'shift_id',
        'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Master Shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}