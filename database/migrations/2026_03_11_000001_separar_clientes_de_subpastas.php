<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Criar tabela de clientes (usuários independentes)
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('usuario')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Criar tabela pivot subpasta <-> cliente (many-to-many)
        Schema::create('subpasta_cliente', function (Blueprint $table) {
            $table->foreignId('subpasta_id')->constrained('subpastas')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->primary(['subpasta_id', 'cliente_id']);
        });

        // 3. Migrar usuários existentes de subpastas para clientes
        $subpastas = DB::table('subpastas')->get();
        $subpastaParaCliente = [];
        foreach ($subpastas as $subpasta) {
            $clienteId = DB::table('clientes')->insertGetId([
                'nome'       => $subpasta->nome,
                'usuario'    => $subpasta->usuario,
                'password'   => $subpasta->password,
                'created_at' => $subpasta->created_at,
                'updated_at' => $subpasta->updated_at,
            ]);

            DB::table('subpasta_cliente')->insert([
                'subpasta_id' => $subpasta->id,
                'cliente_id'  => $clienteId,
            ]);

            $subpastaParaCliente[$subpasta->id] = $clienteId;
        }

        // 4. Migrar downloads: trocar subpasta_id por cliente_id
        Schema::table('downloads', function (Blueprint $table) {
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('cascade');
        });

        foreach ($subpastaParaCliente as $subpastaId => $clienteId) {
            DB::table('downloads')->where('subpasta_id', $subpastaId)->update(['cliente_id' => $clienteId]);
        }

        Schema::table('downloads', function (Blueprint $table) {
            $table->dropForeign(['subpasta_id']);
            $table->dropColumn('subpasta_id');
        });

        // 5. Remover colunas de autenticação da tabela subpastas
        Schema::table('subpastas', function (Blueprint $table) {
            $table->dropColumn(['usuario', 'password', 'remember_token']);
        });
    }

    public function down(): void
    {
        // Restaurar colunas em subpastas
        Schema::table('subpastas', function (Blueprint $table) {
            $table->string('usuario')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
        });

        // Reverter dados (melhor esforço)
        $pivots = DB::table('subpasta_cliente')
            ->join('clientes', 'clientes.id', '=', 'subpasta_cliente.cliente_id')
            ->select('subpasta_cliente.subpasta_id', 'clientes.usuario', 'clientes.password')
            ->get();
        foreach ($pivots as $pivot) {
            DB::table('subpastas')->where('id', $pivot->subpasta_id)->update([
                'usuario'  => $pivot->usuario,
                'password' => $pivot->password,
            ]);
        }

        Schema::dropIfExists('subpasta_cliente');
        Schema::dropIfExists('clientes');
    }
};
