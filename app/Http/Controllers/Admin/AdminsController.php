<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminsController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $admins = $query->latest()->paginate(15)->withQueryString();
        $totalAdmins = Admin::count();
        $currentAdminId = auth()->guard('admin')->id();

        return view('admin.admins.index', compact('admins', 'totalAdmins', 'currentAdminId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        Admin::create($data);

        return back()->with('success', 'Administrador criado com sucesso!');
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $admin->update($data);

        return back()->with('success', 'Administrador atualizado com sucesso!');
    }

    public function destroy(Admin $admin)
    {
        $currentAdminId = auth()->guard('admin')->id();

        if ($admin->id === $currentAdminId) {
            return back()->with('error', 'Você não pode remover seu próprio acesso.');
        }

        if (Admin::count() <= 1) {
            return back()->with('error', 'Não é possível remover o último administrador do sistema.');
        }

        $admin->delete();

        return back()->with('success', 'Administrador removido com sucesso!');
    }
}