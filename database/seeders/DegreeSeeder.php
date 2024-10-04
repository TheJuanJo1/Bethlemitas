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
