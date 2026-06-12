<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transicion = new Group;
        $transicion->group = 'Transición';
        $transicion->save();

        $jardin = new Group;
        $jardin->group = 'Jardín';
        $jardin->save();

        $preJardin = new Group;
        $preJardin->group = 'Pre-Jardín';
        $preJardin->save();

        $primeroA = new Group;
        $primeroA->group = '1A';
        $primeroA->save();

        $primeroB = new Group;
        $primeroB->group = '1B';
        $primeroB->save();

        $segundoA = new Group;
        $segundoA->group = '2A';
        $segundoA->save();

        $segundoB = new Group;
        $segundoB->group = '2B';
        $segundoB->save();

        $terceroA = new Group;
        $terceroA->group = '3A';
        $terceroA->save();

        $terceroB = new Group;
        $terceroB->group = '3B';
        $terceroB->save();

        $cuartoA = new Group;
        $cuartoA->group = '4A';
        $cuartoA->save();

        $cuartoB = new Group;
        $cuartoB->group = '4B';
        $cuartoB->save();

        $quintoA = new Group;
        $quintoA->group = '5A';
        $quintoA->save();

        $quintoB = new Group;
        $quintoB->group = '5B';
        $quintoB->save();

        $sextoA = new Group;
        $sextoA->group = '6A';
        $sextoA->save();

        $sextoB = new Group;
        $sextoB->group = '6B';
        $sextoB->save();

        $septimoA = new Group;
        $septimoA->group = '7A';
        $septimoA->save();

        $septimoB = new Group;
        $septimoB->group = '7B';
        $septimoB->save();

        $octavoA = new Group;
        $octavoA->group = '8A';
        $octavoA->save();

        $octavoB = new Group;
        $octavoB->group = '8B';
        $octavoB->save();

        $novenoA = new Group;
        $novenoA->group = '9A';
        $novenoA->save();

        $novenoB = new Group;
        $novenoB->group = '9B';
        $novenoB->save();

        $decimoA = new Group;
        $decimoA->group = '10A';
        $decimoA->save();

        $decimoB = new Group;
        $decimoB->group = '10B';
        $decimoB->save();

        $decimoC = new Group;
        $decimoC->group = '10C';
        $decimoC->save();

        $onceA = new Group;
        $onceA->group = '11A';
        $onceA->save();

        $onceB = new Group;
        $onceB->group = '11B';
        $onceB->save();
    }
}
