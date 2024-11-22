<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Degree;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        $transicion = new Degree;
        $transicion->degree = 'Transición';
        $transicion->save();

        $pre_jardin = new Degree;
        $pre_jardin->degree = 'Pre-jardín';
        $pre_jardin->save();

        $jardin = new Degree;
        $jardin->degree = 'Jardín';
        $jardin->save();

        $primero = new Degree;
        $primero->degree = '1°';
        $primero->save();

        $segundo = new Degree;
        $segundo->degree = '2°';
        $segundo->save();

        $tercero = new Degree;
        $tercero->degree = '3°';
        $tercero->save();

        $cuarto = new Degree;
        $cuarto->degree = '4°';
        $cuarto->save();

        $quinto = new Degree;
        $quinto->degree = '5°';
        $quinto->save();

        $sexto = new Degree;
        $sexto->degree = '6°';
        $sexto->save();

        $septimo = new Degree;
        $septimo->degree = '7°';
        $septimo->save();

        $octavo = new Degree;
        $octavo->degree = '8°';
        $octavo->save();

        $noveno = new Degree;
        $noveno->degree = '9°';
        $noveno->save();

        $decimo = new Degree;
        $decimo->degree = '10°';
        $decimo->save();

        $once = new Degree;
        $once->degree = '11°';
        $once->save();

    }
}
