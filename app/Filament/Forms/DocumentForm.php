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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
                    FileUpload::make('path')
                        ->label('Arquivo')
                        ->previewable()
                        ->downloadable()
                        ->acceptedFileTypes(['image/*', 'application/pdf'])
                        ->disk('public')
                        ->preserveFilenames()
                        ->directory('uploads-ged-v2')
                        // ->afterStateUpdated(function ($state, $set, $get, $model) {
                        //     if ($state) {
                        //         $state->store('uploads-ged-v2', 's3');
                        //     }
                        // })
                        ->afterStateUpdated(function ($state, $set, $get, $model) {
                            if ($state) {
                                // Caminho do arquivo no disco local
                                $localFilePath = $state->getFilename();

                                if (Storage::disk('public')->exists($localFilePath)) {
                                    // Tenta copiar para o S3
                                    try {
                                        $s3Path = 'uploads-ged-v2/' . $state->getFilename();
                                        Storage::disk('s3')->put($s3Path, Storage::disk('public')->get($localFilePath), 'public');

                                        // Opcional: Log para verificar se salvou corretamente no S3
                                        Log::info("Arquivo {$s3Path} salvo com sucesso no S3.");
                                    } catch (\Exception $e) {
                                        // Log de erro caso a cópia falhe
                                        Log::error("Erro ao salvar arquivo no S3: " . $e->getMessage());
                                    }
                                } else {
                                    // Log de erro caso o arquivo não exista no disco local
                                    Log::error("Arquivo não encontrado no disco local: {$localFilePath}");
                                }

                                // Atualiza a pré-visualização com o caminho no local
                                $set('document_preview', Storage::disk('public')->url($localFilePath));
                            }
                        })
                        ->columnSpanFull()
                        ->afterStateUpdated(fn($state, $get, $set) => $set('document_preview', url('/storage//' . $state->getFilename()))),
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
