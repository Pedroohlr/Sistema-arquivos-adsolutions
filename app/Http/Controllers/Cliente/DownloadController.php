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
        
        // Verificar permissões
        $podeAcessar = false;

        // 1. Se o arquivo está na raiz do grupo do cliente
        if (is_null($arquivo->subpasta_id) && $arquivo->grupo_id === $cliente->grupo_id) {
            $podeAcessar = true;
        }

        // 2. Se o arquivo está na subpasta do cliente
        if ($arquivo->subpasta_id === $cliente->id) {
            $podeAcessar = true;
        }

        if (!$podeAcessar) {
            abort(403, 'Você não tem permissão para baixar este arquivo.');
        }

        // Registrar download no histórico
        Download::create([
            'arquivo_id' => $arquivo->id,
            'subpasta_id' => $cliente->id,
            'usuario' => $cliente->usuario,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        // Fazer download
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
