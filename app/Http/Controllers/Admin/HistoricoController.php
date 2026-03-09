<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Grupo;
use App\Models\Subpasta;
use Illuminate\Http\Request;

class HistoricoController extends Controller
{
    /**
     * Exibe o histórico de downloads com filtros
     */
    public function index(Request $request)
    {
        $query = Download::with(['arquivo.grupo', 'arquivo.subpasta', 'subpasta']);

        // Filtro por grupo
        if ($request->filled('grupo_id')) {
            $query->whereHas('arquivo', function ($q) use ($request) {
                $q->where('grupo_id', $request->grupo_id);
            });
        }

        // Filtro por usuário
        if ($request->filled('usuario')) {
            $query->where('usuario', 'like', '%' . $request->usuario . '%');
        }

        // Filtro por data
        if ($request->filled('data_inicio')) {
            $query->whereDate('downloaded_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('downloaded_at', '<=', $request->data_fim);
        }

        $downloads = $query->latest('downloaded_at')->paginate(50);
        $grupos = Grupo::orderBy('nome')->get();

        // Estatísticas
        $stats = [
            'total_downloads' => Download::count(),
            'downloads_hoje' => Download::whereDate('downloaded_at', today())->count(),
            'downloads_mes' => Download::whereMonth('downloaded_at', now()->month)->count(),
        ];

        return view('admin.historico.index', compact('downloads', 'grupos', 'stats'));
    }

    /**
     * Exportar relatório (CSV)
     */
    public function export(Request $request)
    {
        $query = Download::with(['arquivo', 'subpasta']);

        // Aplicar mesmos filtros
        if ($request->filled('grupo_id')) {
            $query->whereHas('arquivo', function ($q) use ($request) {
                $q->where('grupo_id', $request->grupo_id);
            });
        }

        $downloads = $query->latest('downloaded_at')->get();

        $filename = 'historico_downloads_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($downloads) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho
            fputcsv($file, ['Data/Hora', 'Arquivo', 'Usuário', 'Grupo', 'IP', 'User Agent']);

            // Dados
            foreach ($downloads as $download) {
                fputcsv($file, [
                    $download->downloaded_at->format('d/m/Y H:i:s'),
                    $download->arquivo->nome,
                    $download->usuario,
                    $download->arquivo->grupo->nome,
                    $download->ip_address,
                    $download->user_agent,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
