<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class CleanupRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ms:cleanup_roles_and_permissions 
                            {--force : Force the cleanup without confirmation}
                            {--dry-run : Show what would be done without executing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup roles and permissions: remove all permissions, delete manager and user roles, remap manager to tender_editor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->info('🧹 Starting roles and permissions cleanup...');
        $this->newLine();

        // Show what will be done
        $this->showCleanupPlan();

        // Check if dry-run mode
        if ($this->option('dry-run')) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            return 0;
        }

        // Ask for confirmation unless --force is used
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to proceed with the cleanup? This action cannot be undone.')) {
                $this->info('❌ Cleanup cancelled by user.');
                return 0;
            }
        }

        $this->newLine();

        try {
            // Step 1: Remap users with 'manager' role to 'tender_editor' role
            $this->remapManagerToTenderEditor();

            // Step 2: Remove 'user' role assignments
            $this->removeUserRoleAssignments();

            // Step 3: Delete old roles (manager and user)
            $this->deleteOldRoles();

            // Step 4: Remove all permissions
            $this->removeAllPermissions();

            // Step 5: Clear role-permission relationships
            $this->clearRolePermissionRelationships();

            // Step 6: Clear model-permission relationships
            $this->clearModelPermissionRelationships();

            $this->newLine();
            $this->info('✅ Cleanup completed successfully!');
            $this->showCleanupSummary();

        } catch (\Exception $e) {
            $this->error('❌ Error during cleanup: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Show what will be done during cleanup
     */
    private function showCleanupPlan()
    {
        $this->info('📋 Cleanup Plan:');
        $this->line('   1. Remap users with "manager" role to "tender_editor" role');
        $this->line('   2. Remove all "user" role assignments');
        $this->line('   3. Delete "manager" and "user" roles');
        $this->line('   4. Remove all permissions');
        $this->line('   5. Clear role-permission relationships');
        $this->line('   6. Clear model-permission relationships');
        $this->newLine();
    }

    /**
     * Remap users with 'manager' role to 'tender_editor' role
     */
    private function remapManagerToTenderEditor()
    {
        $this->info('🔄 Step 1: Remapping manager role to tender_editor...');

        $managerRole = Role::where('name', 'manager')->first();
        $tenderEditorRole = Role::where('name', 'tender_editor')->first();

        if (!$managerRole) {
            $this->warn('   ⚠️  Manager role not found, skipping remapping');
            return;
        }

        if (!$tenderEditorRole) {
            $this->warn('   ⚠️  Tender editor role not found, skipping remapping');
            return;
        }

        // Get all users with manager role
        $usersWithManagerRole = DB::table('model_has_roles')
            ->where('role_id', $managerRole->id)
            ->get();

        if ($usersWithManagerRole->isEmpty()) {
            $this->line('   ℹ️  No users found with manager role');
            return;
        }

        $this->line("   📊 Found {$usersWithManagerRole->count()} users with manager role");

        $remappedCount = 0;
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
                $remappedCount++;
            }
        }

        // Remove manager role assignments
        DB::table('model_has_roles')->where('role_id', $managerRole->id)->delete();

        $this->line("   ✅ Remapped {$remappedCount} users from manager to tender_editor role");
    }

    /**
     * Remove 'user' role assignments
     */
    private function removeUserRoleAssignments()
    {
        $this->info('🗑️  Step 2: Removing user role assignments...');

        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            $this->warn('   ⚠️  User role not found, skipping removal');
            return;
        }

        $userAssignments = DB::table('model_has_roles')
            ->where('role_id', $userRole->id)
            ->count();

        if ($userAssignments > 0) {
            DB::table('model_has_roles')->where('role_id', $userRole->id)->delete();
            $this->line("   ✅ Removed {$userAssignments} user role assignments");
        } else {
            $this->line('   ℹ️  No user role assignments found');
        }
    }

    /**
     * Delete old roles (manager and user)
     */
    private function deleteOldRoles()
    {
        $this->info('🗑️  Step 3: Deleting old roles (manager and user)...');

        $oldRoles = Role::whereIn('name', ['manager', 'user'])->get();
        
        if ($oldRoles->isEmpty()) {
            $this->line('   ℹ️  No old roles found to delete');
            return;
        }

        $deletedCount = 0;
        foreach ($oldRoles as $role) {
            $role->delete();
            $this->line("   ✅ Deleted role: {$role->name}");
            $deletedCount++;
        }

        $this->line("   📊 Total roles deleted: {$deletedCount}");
    }

    /**
     * Remove all permissions
     */
    private function removeAllPermissions()
    {
        $this->info('🗑️  Step 4: Removing all permissions...');

        $permissionCount = Permission::count();
        
        if ($permissionCount > 0) {
            Permission::truncate();
            $this->line("   ✅ Removed {$permissionCount} permissions");
        } else {
            $this->line('   ℹ️  No permissions found to remove');
        }
    }

    /**
     * Clear role-permission relationships
     */
    private function clearRolePermissionRelationships()
    {
        $this->info('🧹 Step 5: Clearing role-permission relationships...');

        $relationshipCount = DB::table('role_has_permissions')->count();
        
        if ($relationshipCount > 0) {
            DB::table('role_has_permissions')->truncate();
            $this->line("   ✅ Cleared {$relationshipCount} role-permission relationships");
        } else {
            $this->line('   ℹ️  No role-permission relationships found');
        }
    }

    /**
     * Clear model-permission relationships
     */
    private function clearModelPermissionRelationships()
    {
        $this->info('🧹 Step 6: Clearing model-permission relationships...');

        $relationshipCount = DB::table('model_has_permissions')->count();
        
        if ($relationshipCount > 0) {
            DB::table('model_has_permissions')->truncate();
            $this->line("   ✅ Cleared {$relationshipCount} model-permission relationships");
        } else {
            $this->line('   ℹ️  No model-permission relationships found');
        }
    }

    /**
     * Show cleanup summary
     */
    private function showCleanupSummary()
    {
        $this->newLine();
        $this->info('📊 Cleanup Summary:');
        
        $remainingRoles = Role::pluck('name')->toArray();
        $remainingPermissions = Permission::count();
        
        $this->line("   • Remaining roles: " . implode(', ', $remainingRoles));
        $this->line("   • Remaining permissions: {$remainingPermissions}");
        
        $this->newLine();
        $this->info('🎯 The system now uses only the 7 defined roles without any permissions.');
    }
}
