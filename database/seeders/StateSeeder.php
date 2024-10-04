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

        $bloqueado = new State;
        $bloqueado->state = 'bloqueado';
        $bloqueado->save();

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
