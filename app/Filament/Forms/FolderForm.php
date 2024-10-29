<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FolderForm
{
    public static function form(): array
    {
        return [
            TextInput::make('description')
                ->label('Descrição')
                ->required()
                ->unique()
                ->maxLength(255),
        ];
    }
}
