<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('cliente.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::guard('cliente')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('cliente.dashboard'));
        }

        return back()->withErrors([
            'usuario' => 'Usuário ou senha incorretos.',
        ])->onlyInput('usuario');
    }

    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('cliente.login');
    }

    public function showPasswordForm()
    {
        return view('cliente.password');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:cliente'],
            'password' => ['required', 'confirmed', Password::min(4)],
        ], [
            'current_password.current_password' => 'A senha atual informada está incorreta.',
        ]);

        $cliente = Auth::guard('cliente')->user();
        $cliente->update([
            'password' => $data['password'],
        ]);

        return back()->with('success', 'Senha atualizada com sucesso!');
    }
}
