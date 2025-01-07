<?php

namespace App\Filament\Pages;

use App\Filament\Tables\Columns;
use App\Models\Document;
use App\Tables\Columns\FileLink;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Pages\SimplePage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;

class PublicDocumentsPage extends SimplePage implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.public-documents-page';

    protected ?string $maxWidth = '7xl';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Document::query())
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
            ->actions([])
            ->bulkActions([]);
    }
}
