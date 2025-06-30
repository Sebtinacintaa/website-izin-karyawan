<div 
    x-data="{}" 
    x-init="$nextTick(() => {
        window.dispatchEvent(new CustomEvent('filament-notifications:init'))
    })"
>
    <x-filament-notifications::notifications />
</div>
