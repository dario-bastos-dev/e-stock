<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Executa o seeder para popular as empresas.
     *
     * @return void
     */
    public function run(): void
    {
        // Limpa a pasta de logos se existir
        if (Storage::exists('public/logos')) {
            Storage::deleteDirectory('public/logos');
        }
        
        // Cria o diretório para os logos
        Storage::makeDirectory('public/logos');
        
        // 1. Empresas fixas (para dados essenciais)
        $this->createFixedCompanies();
        
        // 2. Empresas aleatórias (para desenvolvimento/testes)
        if (app()->environment(['local', 'development', 'testing'])) {
            $this->createRandomCompanies();
            
            // 3. Associa usuários aleatórios às empresas (opcional)
            $this->associateUsersToCompanies();
        }
        
        $this->command->info('Empresas criadas com sucesso!');
    }
    
    /**
     * Cria empresas com dados fixos (essenciais para o sistema).
     *
     * @return void
     */
    private function createFixedCompanies(): void
    {
        $companies = [
            [
                'name' => 'Empresa Matriz',
                'document' => '12.345.678/0001-90',
                'address' => 'Av. Paulista, 1000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'country' => 'Brasil',
                'zip_code' => '01310-100',
                'phone' => '(11) 3000-1000',
                'logo' => null, // Sem logo por padrão
            ],
            [
                'name' => 'Filial Rio de Janeiro',
                'document' => '12.345.678/0002-71',
                'address' => 'Av. Atlântica, 500',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'country' => 'Brasil',
                'zip_code' => '22010-000',
                'phone' => '(21) 3000-2000',
                'logo' => null,
            ],
        ];
        
        foreach ($companies as $companyData) {
            // Verifica se a empresa já existe para evitar duplicatas
            Company::firstOrCreate(
                ['document' => $companyData['document']], // Critério único
                $companyData // Todos os dados
            );
        }
    }
    
    /**
     * Cria empresas aleatórias para desenvolvimento e testes.
     *
     * @return void
     */
    private function createRandomCompanies(): void
    {
        // Gera 8 empresas aleatórias usando factory
        Company::factory(10)->create();
    }
    
    /**
     * Associa usuários aleatórios às empresas (relação N:N).
     * Requer uma tabela pivot company_user e usuários já criados.
     *
     * @return void
     */
    private function associateUsersToCompanies(): void
    {
        // Verifica se existem usuários antes de associar
        if (User::count() === 0) {
            $this->command->warn('Não existem usuários para associar às empresas.');
            return;
        }
        
        // Obtém todas as empresas
        $companies = Company::all();
        
        // Obtém todos os usuários
        $users = User::all();
        
        // Percorre cada empresa e associa usuários aleatórios
        foreach ($companies as $company) {
            // Associa entre 1 e 5 usuários aleatórios a cada empresa
            $randomUsers = $users->random(rand(1, min(5, $users->count())));
            
            // Para cada usuário, associamos com um papel aleatório
            $userIds = [];
            foreach ($randomUsers as $user) {
                // Define papéis possíveis: admin, manager, employee
                $roles = ['admin', 'manager', 'employee'];
                
                $userIds[$user->id] = [
                    'role' => $roles[array_rand($roles)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Sincroniza os usuários com a empresa (sem remover os existentes)
            $company->users()->syncWithoutDetaching($userIds);
        }
    }
}
