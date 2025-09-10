<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SearchDatabaseForText extends Command
{
    protected $signature = 'db:replace
                            {old : Texto antigo a procurar}
                            {new : Texto novo para substituir}';
    protected $description = 'Procura um texto em todas as tabelas/colunas do banco e substitui pelo novo valor (salvando log em CSV)';

    public function handle()
    {
        $oldText = $this->argument('old');
        $newText = $this->argument('new');
        $database = DB::getDatabaseName();

        $columns = DB::table('information_schema.columns')
            ->select('table_name', 'column_name')
            ->where('table_schema', $database)
            ->whereIn('data_type', ['char', 'varchar', 'text', 'mediumtext', 'longtext'])
            ->get();

        $rows = [];
        foreach ($columns as $col) {
            $table = $col->table_name;
            $column = $col->column_name;

            try {
                // Verifica se existe algum valor com o texto antigo
                $sql = "SELECT `$column` AS valor FROM `$table` WHERE `$column` LIKE ?";
                $results = DB::select($sql, ["%{$oldText}%"]);

                if (!empty($results)) {
                    foreach ($results as $row) {
                        $before = $row->valor;
                        $after  = str_replace($oldText, $newText, $before);

                        $rows[] = [
                            'tabela' => $table,
                            'coluna' => $column,
                            'antes'  => $before,
                            'depois' => $after,
                        ];
                    }

                    // Atualiza direto no banco
                    $updateSql = "UPDATE `$table` SET `$column` = REPLACE(`$column`, ?, ?)";
                    DB::statement($updateSql, [$oldText, $newText]);

                    $this->info("✅ Atualizado em tabela: {$table}, coluna: {$column}");
                }
            } catch (\Exception $e) {
                $this->error("Erro em {$table}.{$column}: " . $e->getMessage());
            }
        }

        if (!empty($rows)) {
            $filename = 'replace_results_' . date('Ymd_His') . '.csv';
            $handle = fopen(storage_path("app/{$filename}"), 'w');

            // Cabeçalho
            fputcsv($handle, ['tabela', 'coluna', 'antes', 'depois']);

            // Linhas
            foreach ($rows as $r) {
                fputcsv($handle, $r);
            }

            fclose($handle);

            $this->info("📂 Log salvo em: storage/app/{$filename}");
        } else {
            $this->info("⚠ Nenhum valor encontrado para substituir.");
        }
    }
}
