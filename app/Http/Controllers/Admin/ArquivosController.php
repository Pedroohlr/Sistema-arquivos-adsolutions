<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arquivo;
use App\Models\Grupo;
use App\Models\Subpasta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArquivosController extends Controller
{
    /**
     * Exibe a lista de grupos (pastas principais)
     */
    public function index()
    {
        $grupos = Grupo::withCount(['subpastas', 'todosArquivos'])->latest()->get();
        return view('admin.arquivos.index', compact('grupos'));
    }

    /**
     * Cria um novo grupo
     */
    public function storeGrupo(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $grupo = Grupo::create($request->only('nome', 'descricao'));

        return redirect()->route('admin.arquivos.grupo', $grupo->id)
            ->with('success', 'Grupo criado com sucesso!');
    }

    /**
     * Visualiza o conteúdo de um grupo
     */
    public function showGrupo(Grupo $grupo)
    {
        $grupo->load(['subpastas', 'arquivos']);
        return view('admin.arquivos.grupo', compact('grupo'));
    }

    /**
     * Atualiza um grupo
     */
    public function updateGrupo(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $grupo->update($request->only('nome', 'descricao'));

        return back()->with('success', 'Grupo atualizado com sucesso!');
    }

    /**
     * Deleta um grupo e todo seu conteúdo
     */
    public function destroyGrupo(Grupo $grupo)
    {
        // Deletar todos os arquivos do storage
        foreach ($grupo->todosArquivos as $arquivo) {
            Storage::delete($arquivo->caminho);
        }

        $grupo->delete(); // Cascata deleta subpastas, arquivos e downloads

        return redirect()->route('admin.arquivos.index')
            ->with('success', 'Grupo deletado com sucesso!');
    }

    /**
     * Cria uma nova subpasta dentro de um grupo
     */
    public function storeSubpasta(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'usuario' => 'required|string|max:255|unique:subpastas,usuario',
            'password' => 'required|string|min:4',
        ]);

        $subpasta = $grupo->subpastas()->create([
            'nome' => $request->nome,
            'usuario' => $request->usuario,
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'Subpasta e usuário criados com sucesso!');
    }

    /**
     * Atualiza uma subpasta
     */
    public function updateSubpasta(Request $request, Subpasta $subpasta)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $subpasta->update(['nome' => $request->nome]);

        return back()->with('success', 'Subpasta atualizada com sucesso!');
    }

    /**
     * Deleta uma subpasta
     */
    public function destroySubpasta(Subpasta $subpasta)
    {
        // Deletar todos os arquivos do storage
        foreach ($subpasta->arquivos as $arquivo) {
            Storage::delete($arquivo->caminho);
        }

        $subpasta->delete(); // Cascata deleta arquivos e downloads

        return back()->with('success', 'Subpasta, usuário e arquivos deletados com sucesso!');
    }

    /**
     * Upload de arquivo
     */
    public function uploadArquivo(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'subpasta_id' => 'nullable|exists:subpastas,id',
            'arquivo' => 'required|file|max:102400', // 100MB max
        ]);

        $file = $request->file('arquivo');
        $grupo = Grupo::findOrFail($request->grupo_id);

        // Gerar nome único para o arquivo
        $nomeArquivo = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $caminho = $file->storeAs('arquivos', $nomeArquivo);

        // Criar registro no banco
        $arquivo = Arquivo::create([
            'nome' => $file->getClientOriginalName(), // Nome para exibição
            'nome_original' => $file->getClientOriginalName(),
            'caminho' => $caminho,
            'tamanho' => $file->getSize(),
            'tipo_mime' => $file->getMimeType(),
            'grupo_id' => $request->grupo_id,
            'subpasta_id' => $request->subpasta_id ?: null,
        ]);

        return back()->with('success', 'Arquivo enviado com sucesso!');
    }

    /**
     * Mover/duplicar arquivo
     */
    public function moverArquivo(Request $request, Arquivo $arquivo)
    {
        $request->validate([
            'destino_subpasta_id' => 'nullable|exists:subpastas,id',
            'duplicar' => 'boolean',
        ]);

        if ($request->duplicar) {
            // Duplicar arquivo
            $novoArquivo = $arquivo->replicate();
            $novoArquivo->subpasta_id = $request->destino_subpasta_id;
            $novoArquivo->save();

            // Copiar arquivo físico
            $novoCaminho = 'arquivos/' . Str::uuid() . '.' . pathinfo($arquivo->caminho, PATHINFO_EXTENSION);
            Storage::copy($arquivo->caminho, $novoCaminho);
            $novoArquivo->update(['caminho' => $novoCaminho]);

            return back()->with('success', 'Arquivo duplicado com sucesso!');
        } else {
            // Mover arquivo
            $arquivo->update(['subpasta_id' => $request->destino_subpasta_id]);
            return back()->with('success', 'Arquivo movido com sucesso!');
        }
    }

    /**
     * Deletar arquivo
     */
    public function destroyArquivo(Arquivo $arquivo)
    {
        Storage::delete($arquivo->caminho);
        $arquivo->delete();

        return back()->with('success', 'Arquivo deletado com sucesso!');
    }
}
