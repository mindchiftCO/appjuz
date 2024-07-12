<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::create(['name' => 'administrador']);
        Role::create(['name' => 'funcionario']);

        $admin = User::create([
            'name' => 'Juez Sebastian',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'cargo' => 'Juez Juzgado noveno civil municipal',
            'telefono' => '1234567890',
        ]);

        // Asignar rol de administrador al usuario creado
        $admin->assignRole($role);
    }
}
