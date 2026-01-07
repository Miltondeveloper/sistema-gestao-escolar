<?php

namespace Modules\AccessControl\Infrastructure\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AccessControl\Domain\User;
use Modules\AccessControl\Domain\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Permissões Básicas (Exemplo)
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',
            'manage_users',
            'view_financial_reports', // Permissão sensível para teste
            'export_data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Criar Papéis (Roles)
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']); // Admin do Cliente
        $roleFinanceiro = Role::firstOrCreate(['name' => 'Financeiro']);

        // Dar permissões aos papéis
        $roleAdmin->givePermissionTo(['view_dashboard', 'manage_users']);
        $roleFinanceiro->givePermissionTo(['view_dashboard', 'view_financial_reports']);

        // 3. Criar um Cliente (Tenant) de Teste
        $tenant = Tenant::firstOrCreate([
            'domain' => 'escola-modelo.com'
        ], [
            'name' => 'Escola Modelo',
            'document' => '12345678000199',
            'is_active' => true
        ]);

        // 4. Criar Usuários para Teste de Regra de Negócio

        // USUÁRIO A: Financeiro Padrão (Tem acesso via Role)
        $userFinanceiro = User::firstOrCreate([
            'email' => 'financeiro@escola.com'
        ], [
            'name' => 'Ana do Financeiro',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
        ]);
        $userFinanceiro->assignRole($roleFinanceiro);

        // USUÁRIO B: Coordenador que precisa de UMA exceção (Sua regra de negócio!)
        // Vamos dar o cargo 'Admin' mas adicionar a permissão 'view_financial_reports' INDIVIDUALMENTE
        $userExcecao = User::firstOrCreate([
            'email' => 'coordenador@escola.com'
        ], [
            'name' => 'Carlos Coordenador',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
        ]);
        $userExcecao->assignRole($roleAdmin); 
        // A mágica acontece aqui: Permissão direta no usuário
        $userExcecao->givePermissionTo('view_financial_reports');

        // USUÁRIO C: Super Admin GLOBAL (Sem Tenant, Sem Role, Acesso via Gate::before)
        User::firstOrCreate([
            'email' => 'superadmin@plataforma.com'
        ], [
            'name' => 'Super Admin God Mode',
            'password' => Hash::make('password'),
            'tenant_id' => null, // Não pertence a cliente nenhum
        ]);
    }
}