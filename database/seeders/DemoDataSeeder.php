<?php

namespace Database\Seeders;

use App\Models\Cliente;
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

        // Criar subpastas (sem usuário - agora separados)
        $subpastaCadastro = Subpasta::create([
            'grupo_id' => $grupoGrid->id,
            'nome' => 'Sistema de Cadastro',
        ]);

        $subpastaEstoque = Subpasta::create([
            'grupo_id' => $grupoGrid->id,
            'nome' => 'Controle de Estoque',
        ]);

        $subpastaFinanceiro = Subpasta::create([
            'grupo_id' => $grupoTech->id,
            'nome' => 'Sistema Financeiro',
        ]);

        // Criar clientes e vincular às subpastas
        $pedro = Cliente::create([
            'nome' => 'Pedro',
            'usuario' => 'pedro',
            'password' => bcrypt('1234'),
        ]);
        $pedro->subpastas()->attach($subpastaCadastro->id);

        $joao = Cliente::create([
            'nome' => 'João',
            'usuario' => 'joao',
            'password' => bcrypt('3456'),
        ]);
        $joao->subpastas()->attach($subpastaEstoque->id);

        $maria = Cliente::create([
            'nome' => 'Maria',
            'usuario' => 'maria',
            'password' => bcrypt('senha123'),
        ]);
        $maria->subpastas()->attach($subpastaFinanceiro->id);

        $this->command->info('✅ Dados de demonstração criados com sucesso!');
        $this->command->info('');
        $this->command->info('📋 Credenciais de teste:');
        $this->command->info('');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@admin.com');
        $this->command->info('  Senha: admin123');
        $this->command->info('');
        $this->command->info('Clientes:');
        $this->command->info('  Usuário: pedro | Senha: 1234 (pasta: Sistema de Cadastro)');
        $this->command->info('  Usuário: joao | Senha: 3456 (pasta: Controle de Estoque)');
        $this->command->info('  Usuário: maria | Senha: senha123 (pasta: Sistema Financeiro)');
    }
}
