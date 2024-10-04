<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Period;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $primero = new Period;
        $primero->period = 'I';
        $primero->save();

        $segundo = new Period;
        $segundo->period = 'II';
        $segundo->save();

        $tercero = new Period;
        $tercero->period = 'III';
        $tercero->save();
    }
}
