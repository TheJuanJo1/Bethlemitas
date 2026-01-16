<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $areas = [
            'Educación Religiosa',
            'Matemáticas',
            'Idioma Extranjero: inglés',
            'Lengua Castellana',
            'Ciencias económicas y políticas',
            'Biología y educación ambiental',
            'Filosofía y Teología',
            'Química',
            'Educación ética y en valores humanos',
            'Tecnología e Informática',
            'Programa Musical',
            'Física',
            'Educación Física, Recreación y Deportes',
            'Arte: Cine',
        ];

        foreach ($areas as $areaName) {
            Area::create(['name_area' => $areaName]);
        }
    }
}