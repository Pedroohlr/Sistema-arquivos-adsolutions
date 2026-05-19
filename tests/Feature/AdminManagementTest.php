<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(array $attributes = []): Admin
    {
        static $sequence = 1;

        $admin = Admin::create(array_merge([
            'name' => 'Admin ' . $sequence,
            'email' => 'admin' . $sequence . '@teste.com',
            'password' => 'SenhaForte123',
        ], $attributes));

        $sequence++;

        return $admin;
    }

    public function test_admin_can_create_another_admin(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.admins.store'), [
            'name' => 'Alexandre Meneghin',
            'email' => 'alexandre.meneghin@adsolutions.com.br',
            'password' => 'SenhaForte123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'name' => 'Alexandre Meneghin',
            'email' => 'alexandre.meneghin@adsolutions.com.br',
        ]);
    }

    public function test_admin_cannot_remove_own_access(): void
    {
        $admin = $this->createAdmin();
        $this->createAdmin();

        $response = $this->from(route('admin.admins.index'))
            ->actingAs($admin, 'admin')
            ->delete(route('admin.admins.destroy', $admin));

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('admins', ['id' => $admin->id]);
    }

    public function test_last_admin_cannot_be_removed(): void
    {
        $admin = $this->createAdmin();

        $response = $this->from(route('admin.admins.index'))
            ->actingAs($admin, 'admin')
            ->delete(route('admin.admins.destroy', $admin));

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('admins', ['id' => $admin->id]);
    }

    public function test_admin_can_update_another_admin_without_changing_password(): void
    {
        $admin = $this->createAdmin();
        $target = $this->createAdmin([
            'password' => 'SenhaInicial123',
        ]);
        $oldHash = $target->password;

        $response = $this->actingAs($admin, 'admin')->put(route('admin.admins.update', $target), [
            'name' => 'Admin Editado',
            'email' => 'editado@empresa.com',
            'password' => '',
        ]);

        $response->assertRedirect();
        $target->refresh();

        $this->assertSame('Admin Editado', $target->name);
        $this->assertSame('editado@empresa.com', $target->email);
        $this->assertSame($oldHash, $target->password);
        $this->assertTrue(Hash::check('SenhaInicial123', $target->password));
    }
}