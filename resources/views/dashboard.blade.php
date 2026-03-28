<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Need Appoval --}}
        @if(auth()->user()->role === 'admin')
            @if($pendingApprovals->count() > 0)
                <div class="bg-yellow-100 text-yellow-800 p-4 mb-6 rounded">
                    <h3 class="font-medium">Ada {{ $pendingApprovals->count() }} permintaan cuti yang menunggu persetujuan:</h3>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($pendingApprovals as $leave)
                            <li>
                                <strong>{{ $leave->user->name }}</strong> - {{ \Illuminate\Support\Carbon::parse($leave->start_date)->format('d M Y') }} s/d {{ \Illuminate\Support\Carbon::parse($leave->end_date)->format('d M Y') }}
                                <span class="text-sm text-gray-500">({{ $leave->reason }})</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('leave.approval') }}" class="inline-block mt-4 px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        Lihat Semua Permintaan
                    </a>
                </div>
            @endif
        @endif
        
        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-100 p-6 rounded-lg shadow-sm border border-blue-200 text-blue-800">
                <h4 class="font-medium">Sisa Kuota Cuti</h4>
                <p class="text-3xl font-bold">{{ auth()->user()->remaining_leave }} <span class="text-sm">Hari</span></p>
            </div>
            <div class="bg-yellow-100 p-6 rounded-lg shadow-sm border border-yellow-200 text-yellow-800">
                <h4 class="font-medium">Pending Request</h4>
                <p class="text-3xl font-bold">{{ auth()->user()->leaves()->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-green-100 p-6 rounded-lg shadow-sm border border-green-200 text-green-800">
                <h4 class="font-medium">Approved Leave</h4>
                <p class="text-3xl font-bold">{{ auth()->user()->leaves()->where('status', 'approved')->count() }}</p>
            </div>
        </div>

        {{-- Calendar --}}
        <div class="bg-white p-6 rounded-lg shadow-sm">

            {{-- Calendar Header --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-xl text-gray-700">
                    {{ $selectedDate->format('F Y') }}
                </h3>
                <div class="inline-flex rounded-md shadow-sm">
                    <a href="{{ route('dashboard', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-50">
                        &laquo; Prev
                    </a>
                    <a href="{{ route('dashboard', ['month' => now()->month, 'year' => now()->year]) }}" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-300 hover:bg-gray-50">
                        Today
                    </a>
                    <a href="{{ route('dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-50">
                        Next &raquo;
                    </a>
                </div>
            </div>

            {{-- Calendar Grid  --}}
            <div class="grid grid-cols-7 gap-2">
                {{-- Header Hari --}}
                @foreach([ 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
                    <div class="text-center font-bold text-black text-xs py-2 uppercase">{{ $dayName }}</div>
                @endforeach

                {{-- Tanggal Kosong --}}
                @php
                    // dayOfWeekIso: Senin = 1, Minggu = 7
                    $firstDayOfMonth = $calendarDates[0]->dayOfWeekIso;
                @endphp

                @for ($i = 1; $i < $firstDayOfMonth; $i++)
                    <div class="h-14 border border-transparent"></div>
                @endfor
                
                {{-- Tanggal Asli --}}                
                @foreach($calendarDates as $date)
                    @php
                        $shiftsThisDay = $allShifts->get($date->format('Y-m-d'));
                    @endphp
                    <div class="h-32 border border-gray-200 p-2 @if($date->isToday()) bg-blue-50 text-black @endif">
                        <div class="flex justify-between items-start">
                            <span class="{{ $date->isSunday() ? 'text-red-500' : 'text-gray-400' }}">
                                {{ $date->day }}
                            </span>
                        </div>
                        
                        <hr class="border-gray-200 my-1">

                        <div class="mt-2">
                            {{-- Shift --}}
                            {{-- Admin Only --}}
                            @can('admin-only')
                                @if($shiftsThisDay)
                                    <div class="px-2 py-1 rounded-md text-[10px] font-bold text-white shadow-sm" style="background-color:green">
                                        @foreach($shiftsThisDay->groupBy('shift_id') as $shiftGroup)
                                            <div class="text-md opacity-80">
                                                {{ $shiftGroup->first()->shift->name }}: {{ $shiftGroup->count() }}
                                            </div>
                                        @endforeach        
                                    </div>
                                @endif
                            @endcan

                            {{-- User Shifts --}}
                            @if(isset($userShifts[$date->format('Y-m-d')]))
                                @php $currentShift = $userShifts[$date->format('Y-m-d')]->shift; @endphp
                                <div class="mt-2">
                                    <div class="px-2 py-1 rounded-md text-[12px] font-bold text-white shadow-sm" 
                                        style="background-color: {{ $currentShift->color }}">
                                        {{ $currentShift->name }}
                                        <div class="text-[10px] opacity-80">
                                            {{ \Carbon\Carbon::parse($currentShift->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($currentShift->end_time)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Cuti --}}
                            <div class="mt-2 space-y-1">
                                @foreach($approvedLeaves as $leave)
                                    @can('admin-only')
                                        @if($date->between($leave->start_date, $leave->end_date))
                                            <div class="px-1.5 py-0.5 rounded text-xs bg-red-100 text-red-700 border border-red-200 truncate" title="{{ $leave->reason }}">
                                                {{ $leave->user->name }}
                                            </div>
                                        @endif
                                    @endcan
                                @endforeach
                            </div>

                        </div>

                    </div>
                @endforeach

            </div>
        </div>
    </div>

</x-app-layout>