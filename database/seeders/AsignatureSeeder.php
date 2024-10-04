<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asignature;

class AsignatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matematicas = new Asignature; 
        $matematicas->name_asignature = 'Matemáticas';
        $matematicas->save();

        $lengua_castellana = new Asignature; 
        $lengua_castellana->name_asignature = 'Lengua castellana';
        $lengua_castellana->save();

        $ingles = new Asignature; 
        $ingles->name_asignature = 'Inglés';
        $ingles->save();

    }
}
