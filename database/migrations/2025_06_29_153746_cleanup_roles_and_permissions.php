<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Step 1: Remap users with 'manager' role to 'tender_editor' role
        $managerRole = Role::where('name', 'manager')->first();
        $tenderEditorRole = Role::where('name', 'tender_editor')->first();
        
        if ($managerRole && $tenderEditorRole) {
            // Get all users with manager role
            $usersWithManagerRole = DB::table('model_has_roles')
                ->where('role_id', $managerRole->id)
                ->get();
            
            // Assign tender_editor role to these users
            foreach ($usersWithManagerRole as $userRole) {
                // Check if user doesn't already have tender_editor role
                $existingTenderEditorRole = DB::table('model_has_roles')
                    ->where('model_id', $userRole->model_id)
                    ->where('model_type', $userRole->model_type)
                    ->where('role_id', $tenderEditorRole->id)
                    ->first();
                
                if (!$existingTenderEditorRole) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $tenderEditorRole->id,
                        'model_type' => $userRole->model_type,
                        'model_id' => $userRole->model_id,
                    ]);
                }
            }
            
            // Remove manager role assignments
            DB::table('model_has_roles')->where('role_id', $managerRole->id)->delete();
        }

        // Step 2: Remove 'user' role assignments
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            DB::table('model_has_roles')->where('role_id', $userRole->id)->delete();
        }

        // Step 3: Delete old roles (manager and user)
        Role::whereIn('name', ['manager', 'user'])->delete();

        // Step 4: Remove all permissions
        Permission::truncate();
        
        // Step 5: Clear role-permission relationships
        DB::table('role_has_permissions')->truncate();
        
        // Step 6: Clear model-permission relationships
        DB::table('model_has_permissions')->truncate();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is destructive, so we'll create a basic rollback
        // that recreates the basic structure but without the old data
        
        // Recreate basic roles (but not the old manager/user roles)
        $roles = [
            'admin' => 'Amministratore generale di sistema',
            'tender_manager' => 'Coordinatore del gruppo che gestisce i bandi',
            'tender_editor' => 'Redattori di bandi',
            'tender_pm' => 'Project manager di un bando / progetto',
            'team_member' => 'Membri del team di lavoro di Montagna Servizi',
            'team_manager' => 'Coordinatore del team di lavoro di Montagna Servizi',
            'customer_operator' => 'Relazioni con i clienti (funzionalità CRM)',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(['name' => $name]);
        }
    }
};
