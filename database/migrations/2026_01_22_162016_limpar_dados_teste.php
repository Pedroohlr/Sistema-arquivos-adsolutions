<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Download;
use App\Models\Arquivo;
use App\Models\Subpasta;
use App\Models\Grupo;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Deletar todos os downloads
        Download::truncate();
        
        // Deletar todos os arquivos físicos e registros
        $arquivos = Arquivo::all();
        foreach ($arquivos as $arquivo) {
            // Deletar arquivo físico do storage
            if (Storage::exists($arquivo->caminho)) {
                Storage::delete($arquivo->caminho);
            }
        }
        Arquivo::truncate();
        
        // Deletar todas as subpastas (isso também deleta os usuários associados)
        Subpasta::truncate();
        
        // Deletar todos os grupos
        Grupo::truncate();
        
        // Resetar auto increment (apenas para MySQL)
        $connection = DB::getDriverName();
        if ($connection === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::statement('ALTER TABLE downloads AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE arquivos AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE subpastas AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE grupos AUTO_INCREMENT = 1;');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não há como reverter a limpeza de dados
        // Esta migration é apenas para limpar dados de teste
    }
};
