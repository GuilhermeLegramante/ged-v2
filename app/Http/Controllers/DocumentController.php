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
        $path = storage_path('app/seu-arquivo.md'); // Caminho para o arquivo .md
        
        // Verifique se o arquivo existe
        if (!File::exists($path)) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        // Ler o conteúdo do arquivo .md
        $content = File::get($path);
        
        // Quebrar o conteúdo em linhas
        $lines = explode("\n", $content);
        
        // Caminho para o arquivo de log
        $logFile = storage_path('logs/documentos_processados.log');

        // Se o arquivo de log não existir, cria o arquivo
        if (!File::exists($logFile)) {
            File::put($logFile, "Log de Processamento de Documentos\n\n");
        }

        // Itera sobre cada linha
        foreach ($lines as $line) {
            // Aqui estamos assumindo que a linha é do tipo:
            // [2025-02-10 13:51:32] local.INFO: 17 - 2024-07-23 11:03:14 - <URL>
            // E pegamos o número logo após "local.INFO: "
            if (strpos($line, 'local.INFO:') !== false) {
                // Pega o número após "local.INFO:"
                $parts = explode('local.INFO:', $line);
                $number = trim(explode(' -', $parts[1])[0]);

                // Buscar o documento correspondente ao número
                $documento = Document::where('id', $number)->first();

                if ($documento) {
                    // Grava no arquivo de log as informações do documento
                    $logData = sprintf(
                        "[%s] ID: %d, Filename: %s, Created At: %s\n",
                        now()->toDateTimeString(),
                        $documento->id,
                        $documento->filename,
                        $documento->created_at->toDateTimeString()
                    );

                    // Adiciona a entrada no arquivo de log
                    File::append($logFile, $logData);
                }
            }
        }

        return response()->json(['success' => 'Processamento concluído e log gerado.']);
    }
}
