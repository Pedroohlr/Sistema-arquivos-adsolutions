<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Subpasta;
use App\Models\Grupo;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Lista todos os clientes
     */
    public function index(Request $request)
    {
        $query = Cliente::with('subpastas.grupo');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%')
                    ->orWhere('usuario', 'like', '%' . $request->search . '%');
            });
        }

        $clientes = $query->latest()->paginate(20);

        return view('admin.usuarios.index', compact('clientes'));
    }

    /**
     * Cria um novo cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'usuario' => 'required|string|max:255|unique:clientes,usuario',
            'password' => 'required|string|min:4',
        ]);

        Cliente::create([
            'nome' => $request->nome,
            'usuario' => $request->usuario,
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Atualiza credenciais do cliente
     */
    public function update(Request $request, Cliente $usuario)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'usuario' => 'required|string|max:255|unique:clientes,usuario,' . $usuario->id,
            'password' => 'nullable|string|min:4',
        ]);

        $data = [
            'nome' => $request->nome,
            'usuario' => $request->usuario,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $usuario->update($data);

        return back()->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove um cliente
     */
    public function destroy(Cliente $usuario)
    {
        $usuario->delete();
        return back()->with('success', 'Usuário removido com sucesso!');
    }

    /**
     * Busca clientes por nome/usuário (para autocomplete)
     */
    public function search(Request $request)
    {
        $clientes = Cliente::where('nome', 'like', '%' . $request->q . '%')
            ->orWhere('usuario', 'like', '%' . $request->q . '%')
            ->limit(10)
            ->get(['id', 'nome', 'usuario']);

        return response()->json($clientes);
    }
}
