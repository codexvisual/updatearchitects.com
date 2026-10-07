<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $projectManager = Role::firstOrCreate(['name' => 'project-manager']);
        $staff = Role::firstOrCreate(['name' => 'staff']);

        $permissions = [
            'view projects', 'create projects', 'edit projects', 'publish projects', 'delete projects',
            'view services', 'create services', 'edit services', 'delete services',
            'view team', 'create team', 'edit team', 'delete team',
            'view offices', 'create offices', 'edit offices', 'delete offices',
            'view blog', 'create blog', 'edit blog', 'publish blog', 'delete blog',
            'view leads', 'edit leads', 'export leads',
            'view messages', 'edit messages',
            'manage pages', 'manage menus', 'manage media',
            'manage settings', 'manage seo', 'manage users', 'manage roles',
            'view activity logs',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        $superAdmin->syncPermissions($permissions);
        $admin->syncPermissions(Permission::whereIn('name', array_filter($permissions, fn ($p) => ! str_starts_with($p, 'manage users') && ! str_starts_with($p, 'manage roles'))));
        $editor->syncPermissions(Permission::whereIn('name', ['view projects', 'create projects', 'edit projects', 'publish projects', 'view services', 'create services', 'edit services', 'view team', 'create team', 'edit team', 'view offices', 'create offices', 'edit offices', 'view blog', 'create blog', 'edit blog', 'publish blog', 'view leads', 'edit leads', 'view messages', 'manage pages', 'manage menus', 'manage media']));
        $projectManager->syncPermissions(Permission::whereIn('name', ['view projects', 'create projects', 'edit projects', 'publish projects', 'view services', 'edit services', 'view team', 'edit team', 'view offices', 'view leads', 'edit leads', 'view messages']));
        $staff->syncPermissions(Permission::whereIn('name', ['view projects', 'view services', 'view team', 'view offices', 'view leads', 'view messages']));

        $user = User::firstOrCreate(
            ['email' => 'admin@updatearchitects.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );
        $user->assignRole('super-admin');
    }
}
