<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function processarArquivo()
    {
        $path = storage_path('app/log_documentos_sem_arquivo.log'); // Caminho para o arquivo .md

        // Verifique se o arquivo existe
        if (!File::exists($path)) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        // Ler o conteúdo do arquivo .md
        $content = File::get($path);

        // Quebrar o conteúdo em linhas
        $lines = explode("\n", $content);

        // Caminho para o arquivo CSV
        $csvFile = storage_path('app/documentos_processados.csv');

        // Se o arquivo CSV não existir, cria o arquivo com cabeçalho
        if (!File::exists($csvFile)) {
            $header = ['ID', 'Filename']; // Cabeçalho do CSV
            $handle = fopen($csvFile, 'w');
            fputcsv($handle, $header); // Escreve o cabeçalho no CSV
            fclose($handle);
        }

        // Abre o arquivo CSV para adicionar os dados
        $handle = fopen($csvFile, 'a');

        // Itera sobre cada linha
        foreach ($lines as $line) {
            // Aqui estamos assumindo que a linha é do tipo:
            // [2025-02-10 13:51:32] local.INFO: 17 - 2024-07-23 11:03:14 - <URL>
            // E pegamos o número logo após "local.INFO: "
            if (strpos($line, 'local.INFO:') !== false) {
                // Pega o número após "local.INFO:"
                $parts = explode('local.INFO:', $line);
                $number = trim(explode(' -', $parts[1])[0]);

                // Document::find($number)->delete();

                // Buscar o documento correspondente ao número
                $documento = Document::where('id', $number)->first();

                if ($documento) {
                    // Grava a entrada no arquivo CSV
                    $csvData = [$documento->id, $documento->filename];
                    fputcsv($handle, $csvData);
                }
            }
        }

        // Fecha o arquivo CSV após adicionar os dados
        fclose($handle);

        return response()->json(['success' => 'Processamento concluído e CSV gerado.']);
    }

}
