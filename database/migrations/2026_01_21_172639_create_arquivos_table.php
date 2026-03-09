<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arquivos', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Nome para exibição
            $table->string('nome_original'); // Nome original do arquivo
            $table->string('caminho'); // Caminho no storage
            $table->unsignedBigInteger('tamanho'); // Tamanho em bytes
            $table->string('tipo_mime'); // Tipo MIME do arquivo
            $table->foreignId('grupo_id')->nullable()->constrained('grupos')->onDelete('cascade');
            $table->foreignId('subpasta_id')->nullable()->constrained('subpastas')->onDelete('cascade');
            $table->timestamps();
            
            // Se subpasta_id é NULL, arquivo está na raiz do grupo
            // Se subpasta_id tem valor, arquivo está dentro da subpasta
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arquivos');
    }
};
