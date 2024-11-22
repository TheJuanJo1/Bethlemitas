<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Los estados se estan manejando con estos nombres, si se cambia alguno toca cambiarlo tambien en donde se esté llamando.

        $bloqueado = new State;
        $bloqueado->state = 'bloqueado';
        $bloqueado->save();

        // El estado activo se utilizará tanto para los docentes y psicoorientadores como para los estudiantes, en los estudiantes hará enfasís de que el ha sido remitido.
        $activo = new State;
        $activo->state = 'activo';
        $activo->save();

        $en_espera = new State;
        $en_espera->state = 'en espera';
        $en_espera->save();

        $en_piar = new State;
        $en_piar->state = 'en PIAR';
        $en_piar->save();

        $descartado = new State;
        $descartado->state = 'descartado';
        $descartado->save();

    }
}
