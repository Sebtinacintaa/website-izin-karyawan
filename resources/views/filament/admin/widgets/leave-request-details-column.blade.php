<div class="flex items-center space-x-3">
    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-600 dark:text-primary-300">
        {{-- Menggunakan Heroicon Blade Component langsung --}}
        <x-heroicon-o-user class="w-4 h-4" /> 
    </div>
    <div>
        {{-- $getRecord() adalah instance model LeaveRequest untuk baris ini --}}
        <div class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $getRecord()->user->name ?? 'N/A' }}</div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ $getRecord()->leave_type ?? 'Izin' }} - 
            @if($getRecord()->start_date && $getRecord()->end_date)
                {{ \Carbon\Carbon::parse($getRecord()->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($getRecord()->end_date)->format('d M Y') }}
            @else
                Tanggal tidak spesifik
            @endif
        </div>
        <div class="text-xs text-gray-400 dark:text-gray-500">Diajukan {{ \Carbon\Carbon::parse($getRecord()->created_at)->diffForHumans() }}</div>
    </div>
</div>
