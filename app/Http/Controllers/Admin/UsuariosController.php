<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subpasta;
use App\Models\Grupo;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Lista todos os usuários com filtros
     */
    public function index(Request $request)
    {
        $query = Subpasta::with('grupo');

        // Filtro por grupo
        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }

        // Busca por nome de usuário
        if ($request->filled('search')) {
            $query->where('usuario', 'like', '%' . $request->search . '%');
        }

        $usuarios = $query->latest()->paginate(20);
        $grupos = Grupo::orderBy('nome')->get();

        return view('admin.usuarios.index', compact('usuarios', 'grupos'));
    }

    /**
     * Atualiza credenciais do usuário
     */
    public function update(Request $request, Subpasta $usuario)
    {
        $request->validate([
            'usuario' => 'required|string|max:255|unique:subpastas,usuario,' . $usuario->id,
            'password' => 'nullable|string|min:4',
        ]);

        $data = ['usuario' => $request->usuario];
        
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $usuario->update($data);

        return back()->with('success', 'Credenciais atualizadas com sucesso!');
    }
}
