<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SaveDocuments extends Command
{
    protected $signature = 'documents:download';
    protected $description = 'Baixa documentos e os salva no HD';

    public function handle()
    {
        $this->info("Iniciando o download dos documentos...");

        $savePath = 'C:/Users/Marca & Sinal/Downloads/backup_documentos/';
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        $documents = Document::all();

        foreach ($documents as $document) {
            $url = 'https://ged-saofranciscodeassis.hardsoftsistemas.com/storage/' . $document->path;
            $filename = basename($document->path);
            $fullPath = $savePath . $filename;

            try {
                // Baixar usando stream para evitar problemas de memória
                $response = Http::timeout(300)->sink($fullPath)->retry(3, 1000)->get($url);

                if ($response->successful()) {
                    Log::info("Documento salvo: ID {$document->id}, Caminho: $fullPath");
                    $this->info("✅ Documento ID {$document->id} salvo com sucesso.");
                } else {
                    Log::error("❌ Falha ao baixar documento ID {$document->id}: {$response->status()}");
                    $this->error("❌ Erro no download do documento ID {$document->id}.");
                }
            } catch (\Exception $e) {
                Log::error("❌ Erro ao baixar documento ID {$document->id}: " . $e->getMessage());
                $this->error("❌ Erro ao processar documento ID {$document->id}.");
            }
        }

        $this->info("✅ Download concluído!");
    }
}
