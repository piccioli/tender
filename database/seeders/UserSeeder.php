<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@tender.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Create Manager User
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@tender.com',
            'password' => bcrypt('password'),
        ]);
        $manager->assignRole('manager');

        // Create Regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@tender.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');
    }
}
