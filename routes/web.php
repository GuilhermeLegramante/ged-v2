<?php

use App\Filament\Pages\CustomPage;
use App\Filament\Pages\PublicDocumentDetailsPage;
use App\Filament\Pages\PublicDocumentsPage;
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


Route::get('/documentos', PublicDocumentsPage::class)->name('public-documents');

Route::get('/documentos/{id}', PublicDocumentDetailsPage::class)->name('public-document-details');

