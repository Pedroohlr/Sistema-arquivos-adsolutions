<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Subpasta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard do cliente - mostra subpastas/grupos disponíveis
     */
    public function index()
    {
        $cliente = auth()->guard('cliente')->user();

        // Subpastas que o cliente tem acesso, com grupo e arquivos
        $subpastas = $cliente->subpastas()->with(['grupo', 'arquivos'])->get();

        // Grupos distintos acessíveis (para arquivos da raiz do grupo)
        $grupos = $subpastas->pluck('grupo')->unique('id')->filter();

        return view('cliente.dashboard', compact('subpastas', 'grupos', 'cliente'));
    }

    /**
     * Visualiza uma subpasta específica
     */
    public function showGrupo(Grupo $grupo)
    {
        $cliente = auth()->guard('cliente')->user();

        // Verificar se o cliente tem alguma subpasta neste grupo
        $minhasSubpastas = $cliente->subpastas()->where('grupo_id', $grupo->id)->with('arquivos')->get();

        if ($minhasSubpastas->isEmpty()) {
            abort(403, 'Você não tem permissão para acessar este grupo.');
        }

        // Arquivos na raiz do grupo
        $arquivosRaiz = $grupo->arquivos()->whereNull('subpasta_id')->get();

        return view('cliente.grupo', compact('grupo', 'arquivosRaiz', 'minhasSubpastas', 'cliente'));
    }
}
