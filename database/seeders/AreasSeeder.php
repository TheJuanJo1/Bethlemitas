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

            'Actividades lúdicas y culturales',
            'Arte: Cine',
            'Arte: DAC',
            'Arte: DAC 2',
            'Artes: TIC y creatividad',
            'Bienestar',
            'Biología',
            'Biología y educación ambiental',
            'Bioquímica',
            'Ciencias económicas y políticas',
            'Ciencias Naturales',
            'Ciencias Naturales Y Educación Ambiental',
            'Ciencias Sociales',
            'Competencias ciudadanas, Urbanidad y civismo.',
            'Dibujo y pintura',
            'Diseño Asistido Por Computador',
            'Educación Cultural y Artística',
            'Educación ética y en valores humanos',
            'Educación Física, Recreación y Deportes',
            'Educación Musical',
            'Educación Religiosa',
            'Educación socioemocional',
            'Filosofía',
            'Filosofía y Teología',
            'Física',
            'Geometría',
            'Habilidades y Actitudes Científicas',
            'Humanidades',
            'Idioma Extranjero: inglés',
            'Inglés',
            'Lengua Castellana',
            'Lúdica',
            'Matemáticas',
            'Prematemáticas',
            'Programa Musical',
            'Química',
            'Relaciones humanas',
            'Tecnología e Informática',
        ];

        foreach ($areas as $areaName) {
            Area::create(['name_area' => $areaName]);
        }
    }
}