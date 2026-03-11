<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Arquivo;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Baixar um arquivo
     */
    public function download(Arquivo $arquivo)
    {
        $cliente = auth()->guard('cliente')->user();

        // Verificar permissões: arquivo na raiz de um grupo acessível, ou em subpasta acessível
        $subpastaIds = $cliente->subpastas()->pluck('subpastas.id');
        $grupoIds    = $cliente->subpastas()->pluck('grupo_id');

        $podeAcessar = false;

        // Arquivo na raiz de um grupo acessível
        if (is_null($arquivo->subpasta_id) && $grupoIds->contains($arquivo->grupo_id)) {
            $podeAcessar = true;
        }

        // Arquivo em uma subpasta acessível
        if (!is_null($arquivo->subpasta_id) && $subpastaIds->contains($arquivo->subpasta_id)) {
            $podeAcessar = true;
        }

        if (!$podeAcessar) {
            abort(403, 'Você não tem permissão para baixar este arquivo.');
        }

        Download::create([
            'arquivo_id'  => $arquivo->id,
            'cliente_id'  => $cliente->id,
            'usuario'     => $cliente->usuario,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Histórico de downloads do cliente
     */
    public function historico()
    {
        $cliente = auth()->guard('cliente')->user();
        $downloads = $cliente->downloads()->with('arquivo')->latest('downloaded_at')->paginate(20);

        return view('cliente.historico', compact('downloads'));
    }
}
