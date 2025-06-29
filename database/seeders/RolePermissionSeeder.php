<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define roles with their descriptions
        $roles = [
            'admin' => [
                'description' => 'Amministratore generale di sistema, accede a tutte le funzionalità'
            ],
            'tender_manager' => [
                'description' => 'Coordinatore del gruppo che gestisce i bandi'
            ],
            'tender_editor' => [
                'description' => 'Redattori di bandi'
            ],
            'tender_pm' => [
                'description' => 'Project manager di un bando / progetto'
            ],
            'team_member' => [
                'description' => 'Membri del team di lavoro di Montagna Servizi'
            ],
            'team_manager' => [
                'description' => 'Coordinatore del team di lavoro di Montagna Servizi (assegnazione dei compiti / scrum master ecc. ecc.)'
            ],
            'customer_operator' => [
                'description' => 'Relazioni con i clienti (funzionalità CRM)'
            ],
        ];

        // Create roles without permissions
        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            // Log the creation/update
            if ($role->wasRecentlyCreated) {
                $this->command->info("Created role: {$roleName} - {$roleData['description']}");
            } else {
                $this->command->info("Role already exists: {$roleName} - {$roleData['description']}");
            }
        }

        $this->command->info('Roles seeded successfully!');
    }
}
