<?php

namespace App\Filament\Pages;

use App\Filament\Forms\DocumentForm;
use App\Models\Document;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Pages\SimplePage;

class PublicDocumentDetailsPage extends SimplePage
{
    public $document;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.public-document-details-page';

    protected ?string $maxWidth = '7xl';

    protected static ?string $title = 'Detalhes do Documento';


    public function mount($id)
    {
        $this->document = Document::findOrFail($id);
    }
   
}
