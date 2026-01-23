<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Crear roles
        $coordinador = Role::create(['name' => 'coordinador']);
        $docente = Role::create(['name' => 'docente']);
        $psicoorientador = Role::create(['name' => 'psicoorientador']);
        $estudiante = Role::create(['name' => 'estudiante']);

        //Crear permisos
        Permission::create(['name' => 'create_user']);

        $coordinador->givePermissionTo(['create_user']);
        $docente->givePermissionTo(['create_user']);
        $psicoorientador->givePermissionTo(['create_user']);
    }
}
