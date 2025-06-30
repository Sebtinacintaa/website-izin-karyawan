<div class="flex justify-end pr-2">
    <a href="{{ \App\Filament\Resources\LeaveRequestResource::getUrl('edit', ['record' => $getRecord()]) }}" 
       class="fi-btn fi-btn-sm fi-btn-primary fi-color-primary fi-btn-size-sm fi-btn-color-primary flex items-center justify-center gap-1 rounded-lg px-3 py-1.5 text-sm font-semibold outline-none transition duration-75 hover:bg-primary-500 focus-visible:ring-2 focus-visible:ring-primary-600 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-500">
        {{-- Menggunakan Heroicon Blade Component --}}
        <x-heroicon-o-arrow-right-on-rectangle class="fi-btn-icon h-5 w-5" />
        Proses
    </a>
</div>
