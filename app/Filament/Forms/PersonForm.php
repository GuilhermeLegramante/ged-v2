<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Leandrocfe\FilamentPtbrFormFields\Document;

class PersonForm
{
    public static function form(): array
    {
        return [
            Document::make('cpf_cnpj')
                ->label('CPF ou CNPJ')
                ->validation(false)
                ->dynamic(),
            GeneralFields::name(),
            GeneralFields::email(),
            TextInput::make('phone')
                ->tel()
                ->maxLength(255),
            GeneralFields::note(),
        ];
    }
}
