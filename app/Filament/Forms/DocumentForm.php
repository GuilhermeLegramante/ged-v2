<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;

class DocumentForm
{
    public static function form(): array
    {
        return [
            Section::make('Dados do Documento')
                ->description(
                    fn (string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                )
                ->schema([
                    Select::make('document_type_id')
                        ->label('Tipo de Documento')
                        ->relationship('documentType', 'name')
                        ->createOptionForm(DocumentTypeForm::form()),
                    TextInput::make('number')
                        ->label('Número')
                        ->maxLength(255),
                    DatePicker::make('date')
                        ->label('Data do Documento'),
                    TextInput::make('filename')
                        ->label('Nome do Documento')
                        ->maxLength(255),
                    FileUpload::make('path')
                        ->label('Arquivo')
                        ->previewable()
                        ->disk('s3')
                        ->columnSpanFull()
                        ->directory('ged'),
                    DatePicker::make('validity_start')
                        ->label('Início da Vigência'),
                    DatePicker::make('validity_end')
                        ->label('Fim da Vigência'),
                    Select::make('people')
                        ->label('Pessoas Relacionadas')
                        ->multiple()
                        ->columnSpanFull()
                        ->relationship('people', 'name')
                        ->createOptionForm(PersonForm::form()),
                    TagsInput::make('tags')
                        ->columnSpanFull()
                        ->label('Tags'),
                    GeneralFields::note(),
                ])
                ->columns(2)
        ];
    }
}
