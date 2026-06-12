<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center gap-3">
            <x-filament::button type="submit" icon="heroicon-o-check">
                Simpan Pengaturan
            </x-filament::button>

            <x-filament::link :href="route('showcase.index')" target="_blank" icon="heroicon-o-arrow-top-right-on-square">
                Lihat halaman depan
            </x-filament::link>
        </div>
    </form>
</x-filament-panels::page>
