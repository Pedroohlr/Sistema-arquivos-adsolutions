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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arquivo_id')->constrained('arquivos')->onDelete('cascade');
            $table->foreignId('subpasta_id')->constrained('subpastas')->onDelete('cascade');
            $table->string('usuario'); // Nome do usuário que baixou
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('downloaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
