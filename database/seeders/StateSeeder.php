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
        //Estado iniciar ACTIVO
        $activo = new State;
        $activo->state = 'activo';
        $activo->save();

        //Estado cuando el estudiante requiere PIAR
        $en_piar = new State;
        $en_piar->state = 'en PIAR';
        $en_piar->save();

        //Estado cuando el estudiante requiere DUA
        $en_dua = new State;
        $en_dua->state = 'en DUA';
        $en_dua->save();
    }
}
