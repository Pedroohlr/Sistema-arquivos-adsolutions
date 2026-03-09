<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\Subpasta;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar grupos de exemplo
        $grupoGrid = Grupo::create([
            'nome' => 'GridCompany',
            'descricao' => 'Grupo de arquivos da GridCompany',
        ]);

        $grupoTech = Grupo::create([
            'nome' => 'TechSolutions',
            'descricao' => 'Grupo de arquivos da TechSolutions',
        ]);

        // Criar subpastas com usuários
        Subpasta::create([
            'grupo_id' => $grupoGrid->id,
            'nome' => 'Sistema de Cadastro',
            'usuario' => 'pedro',
            'password' => bcrypt('1234'),
        ]);

        Subpasta::create([
            'grupo_id' => $grupoGrid->id,
            'nome' => 'Controle de Estoque',
            'usuario' => 'joao',
            'password' => bcrypt('3456'),
        ]);

        Subpasta::create([
            'grupo_id' => $grupoTech->id,
            'nome' => 'Sistema Financeiro',
            'usuario' => 'maria',
            'password' => bcrypt('senha123'),
        ]);

        $this->command->info('✅ Dados de demonstração criados com sucesso!');
        $this->command->info('');
        $this->command->info('📋 Credenciais de teste:');
        $this->command->info('');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@admin.com');
        $this->command->info('  Senha: admin123');
        $this->command->info('');
        $this->command->info('Clientes:');
        $this->command->info('  Usuário: pedro | Senha: 1234');
        $this->command->info('  Usuário: joao | Senha: 3456');
        $this->command->info('  Usuário: maria | Senha: senha123');
    }
}
