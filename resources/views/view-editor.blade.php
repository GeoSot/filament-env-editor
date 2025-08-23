<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </form>

    <x-filament-panels::unsaved-action-changes-alert />
</x-filament-panels::page>