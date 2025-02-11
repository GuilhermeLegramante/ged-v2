<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <br>
        <x-filament::button type="submit" form="submit">
            Gerar Relatório
        </x-filament::button>
    </form>
    @if (isset($totalInserts))
        <h3><strong>Cadastros:</strong> {{ $totalInserts }}</h3>
        <h3><strong>Edições:</strong> {{ $totalUpdates }}</h3>
        <h3><strong>Exclusões:</strong> {{ $totalDeletes }}</h3>
    @endif
</x-filament::page>
