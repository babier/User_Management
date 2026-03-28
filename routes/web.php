<?php

use App\Livewire;
use App\Livewire\UserManagement;
use App\Livewire\LeaveRequest;
use App\Livewire\LeaveApproval;
use App\Livewire\ShiftMaster;
use App\Livewire\ShiftManagement;
use App\Livewire\AppSettings;
use App\Models\Leave;
use App\Models\UserShift;
use App\Models\Shift;
use App\Models\User;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard/{month?}/{year?}', function (Request $request, $month = null, $year = null) {
    // Jika tidak ada parameter, gunakan bulan dan tahun sekarang
    $month = $month ?: Carbon::now()->month;
    $year = $year ?: Carbon::now()->year;
    $today = Carbon::today()->format('Y-m-d');

    $selectedDate = Carbon::create($year, $month, 1);

    // Ambil jadwal shift untuk all user pada
    $allShifts = UserShift::with('shift')
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->get()
        ->groupBy('date');
    
    // Ambil jadwal shift untuk user
    $userShifts = UserShift::with('shift')
        ->where('user_id', auth()->id())
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->get()
        ->keyBy('date');

    // Membuat array tanggal untuk bulan yang dipilih
    $calendarDates = [];
    $daysInMonth = $selectedDate->daysInMonth;
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $calendarDates[] = Carbon::create($year, $month, $i);
    }

    // Data untuk navigasi
    $prevMonth = $selectedDate->copy()->subMonth();
    $nextMonth = $selectedDate->copy()->addMonth();

    $approvedLeaves = Leave::where('status', 'approved')->get();
    $pendingApprovals = Leave::where('status', 'pending')->get();

    return view('dashboard', [
        'calendarDates' => $calendarDates,
        'approvedLeaves' => $approvedLeaves,
        'pendingApprovals' => $pendingApprovals,
        'selectedDate' => $selectedDate,
        'prevMonth' => $prevMonth,
        'nextMonth' => $nextMonth,
        'userShifts' => $userShifts,
        'allShifts' => $allShifts,
        'today' => $today,
    ]);

})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/manage-users', UserManagement::class)
    ->middleware(['auth'], 'verified', 'can:admin-only')
    ->name('users.index'); // middleware untuk membatasi akses hanya untuk admin

Route::get('/request-leave', LeaveRequest::class)->middleware(['auth'])->name('leave.request');

Route::get('/approve-leave', LeaveApproval::class)->middleware(['auth', 'can:admin-only'])->name('leave.approval');

Route::get('/master-shifts', ShiftMaster::class)
    ->middleware(['auth', 'can:admin-only'])
    ->name('shifts.master');

Route::get('/manage-shifts', ShiftManagement::class)
    ->middleware(['auth', 'can:admin-only'])
    ->name('shifts.index');

Route::get('/settings', AppSettings::class)
    ->middleware(['auth', 'can:admin-only'])
    ->name('settings.index');

require __DIR__.'/auth.php';
