<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard do cliente - mostra grupos disponíveis
     */
    public function index()
    {
        $cliente = auth()->guard('cliente')->user();
        
        // Busca o grupo da subpasta do cliente com relacionamentos
        $grupo = $cliente->grupo;
        
        if ($grupo) {
            $grupo->load(['arquivos', 'subpastas' => function($query) use ($cliente) {
                $query->where('id', $cliente->id);
            }]);
        }
        
        return view('cliente.dashboard', compact('grupo', 'cliente'));
    }

    /**
     * Visualiza um grupo específico
     */
    public function showGrupo(Grupo $grupo)
    {
        $cliente = auth()->guard('cliente')->user();
        
        // Verificar se o cliente tem acesso a este grupo
        if ($cliente->grupo_id !== $grupo->id) {
            abort(403, 'Você não tem permissão para acessar este grupo.');
        }

        // Arquivos na raiz do grupo (visíveis para todos os clientes do grupo)
        $arquivosRaiz = $grupo->arquivos;
        
        // Subpasta do cliente (apenas a dele)
        $minhaSubpasta = $cliente;
        
        // Arquivos da subpasta do cliente
        $meusArquivos = $cliente->arquivos;

        return view('cliente.grupo', compact('grupo', 'arquivosRaiz', 'minhaSubpasta', 'meusArquivos'));
    }
}
