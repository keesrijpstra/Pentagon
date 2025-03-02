<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Passwords
        </x-slot>
        <x-slot name="headerEnd">
            <x-filament::button wire:click="mountAction('create')" color="primary">Create Password</x-filament::button>
        </x-slot>
    </x-filament::section>

    @livewire('list-passwords')
</x-filament-panels::page>