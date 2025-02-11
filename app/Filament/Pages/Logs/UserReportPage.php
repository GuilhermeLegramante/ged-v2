<?php

namespace App\Filament\Pages\Logs;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class UserReportPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.user-report-page';

    protected static ?string $title = 'Documentos por Usuário';

    protected static ?string $slug = 'documentos-por-usuario';

    public $data = [];
    public $user_id;
    public $start_date;
    public $end_date;
    public $totalInserts;
    public $totalUpdates;
    public $totalDeletes;

    public static function getNavigationGroup(): ?string
    {
        return 'Relatórios';
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->columnSpanFull()
                ->label('Usuário')
                ->options(function () {
                    return DB::table('users')
                        ->pluck('name', 'id');
                })
                ->required(),
            DatePicker::make('start_date')
                ->label('Data Inicial')
                ->required()
                ->placeholder('Selecione a data inicial'),
            DatePicker::make('end_date')
                ->label('Data Final')
                ->required()
                ->placeholder('Selecione a data final'),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        $userId = $data['user_id'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        $this->totalInserts = DB::table('activity_log')
            ->where('causer_id', $userId)
            ->where('event', 'Created')
            ->where('subject_type', 'App\Models\Document')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $this->totalUpdates = DB::table('activity_log')
            ->where('causer_id', $userId)
            ->where('event', 'Updated')
            ->where('subject_type', 'App\Models\Document')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $this->totalDeletes = DB::table('activity_log')
            ->where('causer_id', $userId)
            ->where('event', 'Deleted')
            ->where('subject_type', 'App\Models\Document')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }
}
