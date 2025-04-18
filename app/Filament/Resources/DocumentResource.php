<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\FileUploadWithPreview;
use App\Filament\Forms\DocumentForm;
use App\Filament\Forms\GeneralFields;
use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Filament\Tables\Columns;
use App\Models\Document;
use App\Tables\Columns\FileLink;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $recordTitleAttribute = 'documentType.name';

    protected static ?string $modelLabel = 'documento';

    protected static ?string $pluralModelLabel = 'documentos';

    protected static ?string $slug = 'documento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(DocumentForm::form());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('documentType.name')
                    ->label('Tipo de Documento')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('number')
                    ->label('Número')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('note')
                    ->label('Descrição')
                    ->searchable()
                    ->words(8)
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('filename')
                    ->label('Nome')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('date')
                    ->label('Data do Documento')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                FileLink::make('path')
                    ->label('Arquivo')
                    // ->alignment(Alignment::Center)
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('validity_start')
                    ->label('Início da Vigência')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('validity_end')
                    ->label('Fim da Vigência')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('folder.description')
                    ->label('Pasta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Columns::createdAt(),
                Columns::updatedAt(),
            ])
            ->filters([
                SelectFilter::make('documentType')
                    ->label('Tipo de Documento')
                    ->searchable()
                    ->relationship('documentType', 'name'),
                SelectFilter::make('people')
                    ->label('Pessoas Relacionadas')
                    ->multiple()
                    ->searchable()
                    ->relationship('people', 'name'),
                Filter::make('created_at')
                    ->label('Data de Criação')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('De'),
                        DatePicker::make('created_until')
                            ->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->actions([
                ActionGroup::make([
                    // Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
            'view' => Pages\ViewDocument::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
