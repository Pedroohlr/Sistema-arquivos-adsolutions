<?php

namespace Tests\Feature;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientePasswordTest extends TestCase
{
    use RefreshDatabase;

    private function createCliente(array $attributes = []): Cliente
    {
        static $sequence = 1;

        $cliente = Cliente::create(array_merge([
            'nome' => 'Cliente ' . $sequence,
            'usuario' => 'cliente' . $sequence,
            'password' => 'SenhaAtual123',
        ], $attributes));

        $sequence++;

        return $cliente;
    }

    public function test_cliente_can_update_own_password(): void
    {
        $cliente = $this->createCliente();

        $response = $this->actingAs($cliente, 'cliente')->put(route('cliente.password.update'), [
            'current_password' => 'SenhaAtual123',
            'password' => 'NovaSenha456',
            'password_confirmation' => 'NovaSenha456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $cliente->refresh();
        $this->assertTrue(Hash::check('NovaSenha456', $cliente->password));
    }

    public function test_cliente_cannot_update_password_with_wrong_current_password(): void
    {
        $cliente = $this->createCliente();

        $response = $this->from(route('cliente.password.edit'))
            ->actingAs($cliente, 'cliente')
            ->put(route('cliente.password.update'), [
                'current_password' => 'SenhaErrada',
                'password' => 'NovaSenha456',
                'password_confirmation' => 'NovaSenha456',
            ]);

        $response->assertRedirect(route('cliente.password.edit'));
        $response->assertSessionHasErrors('current_password');

        $cliente->refresh();
        $this->assertTrue(Hash::check('SenhaAtual123', $cliente->password));
    }
}