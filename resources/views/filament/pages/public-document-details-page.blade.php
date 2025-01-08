<x-filament-panels::page.simple>
    <table class="table-auto w-full border-collapse border border-gray-200 dark:border-gray-700">
        <tbody>
            @isset($this->document->documentType)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Tipo de Documento
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $this->document->documentType->name }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->number)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Número
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $this->document->number }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->date)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Data do Documento
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($this->document->date)->format('d/m/Y') }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->filename)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Nome do Documento
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $this->document->filename }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->validity_start)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Início da Vigência
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($this->document->validity_start)->format('d/m/Y') }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->validity_end)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Fim da Vigência
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($this->document->validity_end)->format('d/m/Y') }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->filename)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Nome do Documento
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $this->document->filename }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->description)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Descrição
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $this->document->description }}
                    </td>
                </tr>
            @endisset

            @isset($this->document->tags)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Tags
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        @foreach ($this->document->tags as $tag)
                            {{ $tag }}
                        @endforeach
                    </td>
                </tr>
            @endisset

            @isset($this->document->path)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Arquivo
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        <a href="{{ asset('/storage/' . $this->document->path) }}" target="_blank">
                            <svg class="fi-ta-icon-item fi-ta-icon-item-size-lg h-6 w-6 fi-color-gray text-gray-400 dark:text-gray-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13">
                                </path>
                            </svg>
                        </a>
                    </td>
                </tr>
            @endisset

            @isset($this->document->people)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2 text-sm font-medium text-gray-950 dark:text-white" style="width: 20%">
                        Pessoas Relacionadas
                    </td>
                </tr>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    @php
                        $peopleCount = count($this->document->people);
                        $half = ceil($peopleCount / 2); // Divide o número total de pessoas ao meio
                    @endphp

                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300" colspan="2">
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($this->document->people->take($half) as $item)
                                <!-- Primeira metade -->
                                <div>{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300" colspan="2">
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($this->document->people->skip($half) as $item)
                                <!-- Segunda metade -->
                                <div>{{ $item->name }}</div>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endisset
        </tbody>
    </table>
</x-filament-panels::page.simple>
