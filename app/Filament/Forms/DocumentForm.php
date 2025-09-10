<?php

namespace App\Filament\Forms;

use App\Forms\Components\CameraCapture;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DocumentForm
{
    public static function form(): array
    {
        return [
            Section::make('Dados do Documento')
                ->description(
                    fn(string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                )
                ->schema([
                    // FileUpload::make('path')
                    //     ->label('Arquivo')
                    //     ->previewable()
                    //     ->downloadable()
                    //     ->columnSpanFull()
                    //     ->afterStateUpdated(fn($state, $get, $set) => $set('document_preview', url('/storage//' . $state->getFilename()))),
                    FileUpload::make('path')
                        ->label('Arquivo')
                        ->previewable()
                        ->downloadable()
                        ->columnSpanFull()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            if ($state) {
                                // gera URL pública (disco configurado no filesystem)
                                $url = Storage::disk('public')->url($state);
                                dd($url);
                                $set('document_preview', $url);
                            }
                        }),
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
                    Select::make('folder_id')
                        ->label('Pasta')
                        ->columnSpanFull()
                        ->relationship('folder', 'description')
                        ->createOptionForm(FolderForm::form()),
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
