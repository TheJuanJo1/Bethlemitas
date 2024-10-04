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
        $novenoA = new Group;
        $novenoA->group = '9A';
        $novenoA->save();

        $decimoA = new Group;
        $decimoA->group = '10A';
        $decimoA->save();

        $onceA = new Group;
        $onceA->group = '11A';
        $onceA->save();
    }
}
