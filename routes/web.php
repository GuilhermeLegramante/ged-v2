<?php

use App\Filament\Pages\CustomPage;
use App\Filament\Pages\PublicDocumentDetailsPage;
use App\Filament\Pages\PublicDocumentsPage;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/ged-v2/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/ged-v2/public/livewire/update', $handle);
});


/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/', function () {
    return redirect(route('filament.admin.pages.dashboard'));
});

Route::get('/teste-upload', function () {
    Storage::disk('s3')->put('uploads-ged-v2/123456.txt', 'Conteúdo de teste');
    dd('OK');
});

Route::get('/log-tina/{start}/{end}', function ($start, $end) {
    // Ajustando o formato para garantir que o intervalo abranja o dia todo
    $start = Carbon\Carbon::parse($start)->startOfDay(); // Começo do dia
    $end = Carbon\Carbon::parse($end)->endOfDay(); // Fim do dia

    $total = DB::table('activity_log')
        ->where('description', 'like', '%Document Updated by vallentina%')
        ->whereBetween('created_at', [$start, $end])
        ->count();

    dd($total);
});

Route::get('/conferencia-arquivos', function () {

    set_time_limit(0);

    $documents = Document::where('id', '>', 2006); // Obtém os primeiros 1000 documentos

    foreach ($documents as $key => $document) {
        $filePath = 'https://ged-saofranciscodeassis.hardsoftsistemas.com/storage/' . $document->path;

        // Verifica se o arquivo é um PDF e o link é acessível
        if (substr($filePath, -4) === '.pdf') {
            // Verifica a existência do arquivo
            $headers = get_headers($filePath);
    
            // Se o código de resposta HTTP for 404, o arquivo não existe
            if (strpos($headers[0], '404') !== false) {
                // Log do arquivo não encontrado, incluindo a data de criação do documento
                Log::info("Documento ID: {$document->id} não encontrado. Criado em: {$document->created_at}. Caminho: {$filePath}");
            }
        } else {
            Log::warning("Arquivo não encontrado: " . $filePath);
        }
    }

    dd("FIM");
});






Route::get('/documentos', PublicDocumentsPage::class)->name('public-documents');

Route::get('/documentos/{id}', PublicDocumentDetailsPage::class)->name('public-document-details');
