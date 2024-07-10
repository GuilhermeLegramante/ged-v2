<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;

class DocumentForm
{
    public static function form(): array
    {
        return [
            Select::make('document_type_id')
                ->label('Tipo de Documento')
                ->relationship('documentType', 'name'),
            TextInput::make('number')
                ->label('Número')
                ->maxLength(255),
            DatePicker::make('date')
                ->label('Data do Documento'),
            FileUpload::make('path')
                ->label('Arquivo')
                ->disk('s3')
                ->directory('ged'),
            TextInput::make('filename')
                ->label('Nome do Documento')
                ->maxLength(255),
            DatePicker::make('validity_start')
                ->label('Início da Vigência'),
            DatePicker::make('validity_end')
                ->label('Fim da Vigência'),
            Select::make('people')
                ->label('Pessoas Relacionadas')
                ->relationship('people', 'name'),
            TagsInput::make('tags')
                ->label('Tags'),
            GeneralFields::note(),
        ];
    }
}
