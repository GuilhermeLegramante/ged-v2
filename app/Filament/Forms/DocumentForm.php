<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

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
                    FileUpload::make('path')
                        ->label('Arquivo')
                        ->previewable()
                        ->downloadable()
                        ->acceptedFileTypes(['image/*', 'application/pdf'])
                        ->disk('s3')
                        ->columnSpanFull()
                        ->afterStateUpdated(fn ($state, $get, $set) => $set('document_preview', url('/storage//' . $state->getFilename())))
                        ->directory('ged'),
                    Section::make('Pré-visualização do Arquivo')
                        ->schema([
                            ViewField::make('document_preview')
                                ->view('components.document-preview')
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
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
